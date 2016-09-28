<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Controlador utilizado para permitir ao usuário visualizar seus horários em
 * formato de calendário.
 * 
 * Aqui é implementada a listagem e filtragem de horários por um determinado
 * período. Também são mostrados quantias gerais relacionados aos horários como
 * saldo de horas do mês anterior, total de horas no mês e carga horária 
 * prevista.
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage controllers
 * @version v1.0
 * @since v1.0
 */
class CalendarioController extends BaseController
{

    /**
     * Action utilizada para listagem da tela principal do calendário.
     * 
     * Aqui é feita uma chamada ao método privado exibeCalendario() para melhoria
     * na legibilidade do código.
     */
    public function actionIndex()
    {
        $this->exibeCalendario(Yii::app()->user->id_pessoa);
    }
    
    /**
     * Action utilizada para alguém com cargo de chefia visualizar os horários
     * de um funcionário.
     * 
     * Esse método verifica se o usuário logado possui cargo de chefia em algum 
     * órgão. Em caso positivo é feita uma requisição para o controlador
     * AcompanhamentoController e exibida a tela para listagem de horários do
     * servidor selecionado pela chefia.
     * 
     * Uma mensagem de erro é exibida caso o usuário não tenha cargo de chefia.
     */
    public function actionAcompanhamentoChefia() 
    {
        $orgaosChefiados = RestricaoRelogio::getOrgaosChefia(Yii::app()->session['id_pessoa']);
        if (!empty($orgaosChefiados)) {
            $this->render('/acompanhamento/acompanhamentoChefia', array(
                'acompanhamento' => (isset(Yii::app()->session['CodPessoaAcompanhamentoPonto']) ? 
                    $this->exibeCalendario(Yii::app()->session['CodPessoaAcompanhamentoPonto'], true) : ''),
                'pessoaAcompanhamento' => (isset(Yii::app()->session['CodPessoaAcompanhamentoPonto']) ? Yii::app()->session['CodPessoaAcompanhamentoPonto'] : ''),
                'abaAtiva' => 'calendario',
            ));
        }
        else {
            // nao e chefe
            $this->render('/registro/mensagem', array('mensagem' => 'Você não possui cargo de chefia.', 'classe' => 'Info'));
        }
    }
 
    /**
     * Action utilizada para permitir que um gerente visuaize os registros de um
     * funcionário específico passando seu id para um campo de busca.
     * 
     * O id do funcionário deve ser passado utilizando o método POST e sendo 
     * referenciado com a chave "p".
     * 
     * Caso o usuário que requisitou os dados não tenha permissão para acessar
     * os registros de outro funcionário uma mensagem de erro é exibida.
     */
    public function actionPessoa() {
        $id_pessoa = intval($_POST['p']);
        
        $restricaoChefia = "";
        $gerencia = Yii::app()->user->checkAccess(APLICACAO_GERENCIA);
        if (!$gerencia) {
            $restricaoChefia = "and (
                    DadoFuncional.orgao_exercicio in (select id_orgao from fn_hierarquia_orgao_funcoes_pessoa (".Yii::app()->user->id_pessoa.")) 
                    or DadoFuncional.orgao_exercicio in (
                        select id_orgao from fn_permissoes (".Yii::app()->user->id_pessoa.", 'RH', 'rh702',null) 
                        union select id_orgao from fn_permissoes (".Yii::app()->user->id_pessoa.", 'RH', 'rh003', null)
                    ) 
                    or DadoFuncional.orgao_exercicio in (
                        select id_orgao 
                        from TABELAS_AUXILIARES..ADOrgaoDirigenteExercicio TAUX
                            inner join SERVIDOR S on S.matricula = TAUX.matricula  
                        where S.id_pessoa = ".Yii::app()->user->id_pessoa."
                    ) 
                )";
        }
        
        $pessoa = Pessoa::model()->with(array(
            'DadoFuncional' => array(
                'select' => '',
                'on' => 'coalesce(DadoFuncional.data_desligamento, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP() 
                        and coalesce(DadoFuncional.data_aposentadoria, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP()',
                'joinType' => 'inner join'
            )
        ))->findByPk($id_pessoa, array(
            'select' => 't.id_pessoa, t.nome_pessoa, t.nome_pessoa',
            'condition' => "
                t.id_pessoa <> :id_pessoa
                $restricaoChefia ",
            'params' => array(
                ':id_pessoa' => Yii::app()->user->id_pessoa,
            ),
        ));
        if ($pessoa) {
            if ($gerencia) {
                Yii::app()->session['CodPessoaAcompanhamentoPontoGerencia'] = $pessoa->id_pessoa;
            }
            Yii::app()->session['CodPessoaAcompanhamentoPonto'] = $pessoa->id_pessoa;
            print $this->exibeCalendario($id_pessoa, true);
        }
        else {
            $this->render('/registro/mensagem', array('mensagem' => 'Você não tem permissão para ver os registros desse servidor.', 'classe' => 'Info'));
        }
    }
    
    /**
     * Método auxiliar para apresentação do calendário na tela.
     * 
     * Esse método busca os registros de um usário com base no id passado por 
     * parâmetro e renderiza a página com tais informações.
     * 
     * O método pode fazer isso de forma síncrona ou assíncrona a depender do
     * valor da variável $viaAjax. Esse parâmetro define de que forma os dados 
     * serão exibidos na tela. Com valor <code>false</code> é renderizada 
     * utilizando o método <code>render()</code> e caso contrário é renderizada
     * com o método <code>renderPartial()</code> para utilização das informações
     * em um modal.
     * 
     * @param int $id_pessoa Chave primária da classe Pessoa
     * @param boolean $viaAjax Variável para definir o tipo de requisição
     */
    private function exibeCalendario($id_pessoa, $viaAjax = false) 
    {    
        $pessoa = Pessoa::model()->with(array(
                'DadosFuncionais' => array(
                    'joinType' => 'inner join',
                    'on' => 'coalesce(DadosFuncionais.data_desligamento, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP() 
                            and coalesce(DadosFuncionais.data_aposentadoria, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP() '.
                            (isset($_REQUEST['v']) ? 'and DadosFuncionais.nr_vinculo = '.intval($_REQUEST['v']) : '')
                ),
                'DadosFuncionais.GrupoEmprego' => array(
                    'joinType' => 'inner join',
                    'on' => "GrupoEmprego.segmento_grupo = 'T'"
                ),
                'DadosFuncionais.CatFuncional',
                'DadosFuncionais.OrgaoLotacao',
                'DadosFuncionais.OrgaoExercicio',
            ))->findByPk($id_pessoa);

        if ($pessoa) {            
            if (count($pessoa->DadosFuncionais) == 1) {
                $pessoa->DadosFuncionais = $pessoa->DadosFuncionais[0];
                
                if ($pessoa->DadosFuncionais->regime_trabalho == 'DE')
                    $pessoa->DadosFuncionais->regime_trabalho = 40;
                
                $sql = "select distinct year(data_hora_ponto) ano
                        from v_ponto_e_ajuste
                        where id_pessoa = :id_pessoa
                            and nr_vinculo = :nr_vinculo
                        order by 1 desc ";
                $anos = Yii::app()->db->createCommand($sql)->queryColumn(array(
                    ':id_pessoa' => $id_pessoa,
                    ':nr_vinculo' => $pessoa->DadosFuncionais->nr_vinculo
                ));
                $anoSelecionado = intval(isset($_REQUEST['a']) ? $_REQUEST['a'] : (isset($anos[0]) ? $anos[0] : date("Y")));
                if (!empty($anos) && ($anoSelecionado > $anos[0])) {
                    $anoSelecionado = $anos[0];
                }
                $meses = array();
                $sql = "select distinct month(data_hora_ponto) mes
                        from v_ponto_e_ajuste
                        where id_pessoa = :id_pessoa
                            and nr_vinculo = :nr_vinculo
                            and year(data_hora_ponto) = :ano
                        order by 1 desc ";
                $meses = Yii::app()->db->createCommand($sql)->queryColumn(array(
                    ':id_pessoa' => $id_pessoa,
                    ':nr_vinculo' => $pessoa->DadosFuncionais->nr_vinculo,
                    ':ano' => $anoSelecionado,
                ));
                $mesSelecionado = intval(isset($_REQUEST['m']) ? $_REQUEST['m'] : (isset($meses[0]) ? $meses[0] : date("m")));
                if (!empty($meses) && ($mesSelecionado > $meses[0])) {
                    $mesSelecionado = $meses[0];
                }
                $anoAnterior = ($mesSelecionado != 1 ? $anoSelecionado : $anoSelecionado-1);
                $mesAnterior = ($mesSelecionado != 1 ? $mesSelecionado-1 : 12);
                $chMesAnterior = CargaHorariaMesServidor::getCargaHorariaMes($pessoa->id_pessoa, $pessoa->DadosFuncionais->nr_vinculo, $mesAnterior, $anoAnterior);
                $saldoMesAnterior = (!empty($chMesAnterior) ? $chMesAnterior->nr_minutos_saldo : 0);
                
                // busca carga horaria do mes antes do anterior e possivel compensacao feita no mes anterior
                $anoAntesAnterior = ($mesAnterior != 1 ? $anoAnterior : $anoAnterior-1);
                $mesAntesAnterior = ($mesAnterior != 1 ? $mesAnterior-1 : 12);
                $chMesAntesAnterior = CargaHorariaMesServidor::getCargaHorariaMes($pessoa->id_pessoa, $pessoa->DadosFuncionais->nr_vinculo, $mesAntesAnterior, $anoAntesAnterior);
                $saldoMesAntesAnterior = (!empty($chMesAntesAnterior) ? $chMesAntesAnterior->nr_minutos_saldo : 0);
                $compensacaoMesAnterior = Compensacao::getCargaHorariaCompensadaMes($pessoa->id_pessoa, $pessoa->DadosFuncionais->nr_vinculo, $mesAnterior, $anoAnterior, false);
                
                $diasUteisMes = Ponto::getNrDiasUteis($mesSelecionado, $anoSelecionado);
                $cargaHorariaMesSelecionado = ($pessoa->DadosFuncionais->regime_trabalho/5)*$diasUteisMes; // regime de trabalho / 5 * numero de dias uteis no mes
                // PROVISORIO
                // Devido a quarta-feira de cinzas, que e de meio turno, diminui 4 horas se o mes e fevereiro
                if ($mesSelecionado == 2)
                    $cargaHorariaMesSelecionado -= 4;
                
                $calendarioMesSelecionado = Ponto::getCalendarioMes($mesSelecionado, $anoSelecionado);                
                $abonosMesSelecionado = Abono::getAbonosMes($id_pessoa, $pessoa->DadosFuncionais->nr_vinculo, $mesSelecionado, $anoSelecionado);
                $compensacoesMesSelecionado = Compensacao::getCompensacoesMes($id_pessoa, $pessoa->DadosFuncionais->nr_vinculo, $mesSelecionado, $anoSelecionado);
                
                $registrosMesSelecionado = PontoEAjuste::getRegistrosMes($id_pessoa, $pessoa->DadosFuncionais->nr_vinculo, $mesSelecionado, $anoSelecionado);
                
                // faz a contabilizacao dos registros
                $ultimoTipoRegistro = 'S';
                $ultimoRegistro = NULL;
                $ultimoDia = 0;
                $jornadaMensal = 0;
                $jornadaDiaria = array();
                foreach ($registrosMesSelecionado as $registro) {
                    $mudouDia = ($ultimoDia != date("d/m/Y", strtotime($registro->data_hora_ponto)));
                    if ($registro->entrada_saida == 'S') {
                        if ($ultimoTipoRegistro == 'E') {
                            if ($mudouDia) { //saida apos entrada, mas em outro dia
                                $jornadaDoTurno = (strtotime(date("Y-m-d", strtotime($ultimoRegistro))." 23:59:59")-strtotime($ultimoRegistro)) /60;
                                if (!isset($jornadaDiaria[ date('d/m/Y', strtotime($ultimoRegistro)) ])) {
                                    $jornadaDiaria[ date('d/m/Y', strtotime($ultimoRegistro)) ] = 0;
                                }
                                $jornadaDiaria[ date('d/m/Y', strtotime($ultimoRegistro)) ] += $jornadaDoTurno;
                                $jornadaMensal += $jornadaDoTurno;
                                $ultimoRegistro = date('Y-m-d', strtotime($registro->data_hora_ponto))." 00:00:00"; 
                            }
                            $jornadaDoTurno = (strtotime($registro->data_hora_ponto)-strtotime($ultimoRegistro))/60;
                            if (!isset($jornadaDiaria[ date('d/m/Y', strtotime($registro->data_hora_ponto)) ])) {
                                $jornadaDiaria[ date('d/m/Y', strtotime($registro->data_hora_ponto)) ] = 0;
                            }
                            $jornadaDiaria[ date('d/m/Y', strtotime($registro->data_hora_ponto)) ] += $jornadaDoTurno;
                            $jornadaMensal += $jornadaDoTurno;
                        }
                    }
                    $ultimoTipoRegistro = $registro->entrada_saida;
                    $ultimoRegistro = $registro->data_hora_ponto;
                    $ultimoDia = date("d/m/Y", strtotime($registro->data_hora_ponto));
                }
                // seta valores (exceto o de afastamento) para o calendario do mes selecionado
                for ($i = 1; $i <= count($calendarioMesSelecionado); $i++) {
                    // se tem registro para esse dia, seta o valor
                    if (isset($jornadaDiaria[ $calendarioMesSelecionado[$i]['Data'] ])) {
                        $calendarioMesSelecionado[$i]['MinutosRegistro'] = $jornadaDiaria[ $calendarioMesSelecionado[$i]['Data'] ];
                    }
                    // se tem abono para esse dia, seta o valor
                    if (isset($abonosMesSelecionado['diasComAbono'][ $calendarioMesSelecionado[$i]['Data'] ])) {
                        $calendarioMesSelecionado[$i]['MinutosAbono'] = $abonosMesSelecionado['diasComAbono'][ $calendarioMesSelecionado[$i]['Data'] ];
                        $calendarioMesSelecionado[$i]['AbonoPendente'] = $abonosMesSelecionado['diasComAbonoPendente'][ $calendarioMesSelecionado[$i]['Data'] ];
                    }
                    // se tem compensacao para esse dia, seta o valor
                    if (isset($compensacoesMesSelecionado['diasComCompensacao'][ $calendarioMesSelecionado[$i]['Data'] ])) {
                        $calendarioMesSelecionado[$i]['MinutosCompensacao'] = $compensacoesMesSelecionado['diasComCompensacao'][ $calendarioMesSelecionado[$i]['Data'] ];
                        $calendarioMesSelecionado[$i]['CompensacaoPendente'] = $compensacoesMesSelecionado['diasComCompensacaoPendente'][ $calendarioMesSelecionado[$i]['Data'] ];
                    }
                }
                
                $horasAfastamento = 0;
                $afastamentos = Abono::getAfastamentos($pessoa->DadosFuncionais->matricula, $pessoa->DadosFuncionais->nr_vinculo, $mesSelecionado, $anoSelecionado);
                if (!empty($afastamentos)) {
                    // seta valores de afastamentos para o calendario do mes selecionado
                    foreach ($afastamentos as $afastamento) {
                        $data = explode('/', $afastamento['data_inicio']);
                        $diaInicio = intval($data[0]);
                        // marca o dia de inicio ate o dia de fim como afastado
                        for ($i = $diaInicio; $i < ($diaInicio + $afastamento['nr_dias']); $i++) {
                            $calendarioMesSelecionado[$i]['EmAfastamento'] = true;
                            $calendarioMesSelecionado[$i]['Afastamentos'] .= $afastamento['nome_frequencia'].'<br/>';
                        }
                        $horasAfastamento += ($afastamento['nr_dias_uteis'] * $pessoa->DadosFuncionais->regime_trabalho/5);
                    }
                }
                $dados = array(
                    'anos' => $anos,
                    'anoSelecionado' => $anoSelecionado,
                    'meses' => $meses,
                    'mesSelecionado' => $mesSelecionado,
                    'saldoMesAnterior' => $saldoMesAnterior,
                    'saldoMesAntesAnterior' => $saldoMesAntesAnterior,
                    'compensacaoMesAnterior' => (!empty($compensacaoMesAnterior) ? $compensacaoMesAnterior : 0),
                    'calendarioMesSelecionado' => $calendarioMesSelecionado,
                    'totalRegistros' => $jornadaMensal,
                    'totalAbono' => $abonosMesSelecionado['totalAbono'],
                    'totalCompensacao' => $compensacoesMesSelecionado['totalCompensacao'],
                    'pessoa' => $pessoa,
                    'cargaHorariaMesSelecionado' => $cargaHorariaMesSelecionado,
                    'afastamentos' => $afastamentos,
                    'horasAfastamento' => $horasAfastamento*60, // em minutos
                    'viaAjax' => $viaAjax,
                );
                if (!$viaAjax) {
                    $this->render('index', $dados);
                }
                else {
                    return $this->renderPartial('index', $dados, true);
                }
                
            }
            else {
                // mais de um vinculo
                $dados = array(
                    'pessoa' => $pessoa,
                    'viaAjax' => $viaAjax,
                );
                if (!$viaAjax) {
                    $this->render('index', $dados);
                }
                else {
                    return $this->renderPartial('index', $dados, true);
                }
            }
        }
        else {
            $this->render('/registro/mensagem', array('mensagem' => 'O ponto eletrônico não está liberado para o seu vínculo.', 'classe' => 'Info'));
        }
    }
}