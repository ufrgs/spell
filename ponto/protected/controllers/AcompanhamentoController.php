<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Controlador utilizado para permitir ao usuário visualizar seus dados pessoais.
 * 
 * Aqui são definidas as rotas para exibição das informações sobre o usuário, 
 * como nome, cargo e os registros de horários.
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage controllers
 * @version v1.0
 * @since v1.0
 */
class AcompanhamentoController extends BaseController
{

    /**
     * Método responsável por exibir a tela princial do sistema. Pode ser 
     * visualizada através do menu Acompanhamento de Registros.
     */
    public function actionIndex()
    {
        // print_r(Yii::app()->user); die;
        $this->exibeAcompanhamento(Yii::app()->user->id_pessoa);
    }
    
    /**
     * Action reponsável por habilitar o acompanhamento de horários de um 
     * servidor por seu chefe.
     * 
     * O método verifica se o id do usuário autenticado está associado à chefia 
     * de um órgão. Se houver ao menos um órgão associado a opção Acompanhamento
     * da Chefia será exibida no menu.
     * 
     * Caso o usuário não possua o cargo de chefia é exibida uma mensagem de erro.
     */
    public function actionAcompanhamentoChefia() 
    {
        $orgaosChefiados = RestricaoRelogio::getOrgaosChefia(Yii::app()->session['id_pessoa']);
        if (!empty($orgaosChefiados)) {
            $this->render('acompanhamentoChefia', array(
                'acompanhamento' => (isset(Yii::app()->session['CodPessoaAcompanhamentoPonto']) && !isset($_GET['p']) ? 
                    $this->exibeAcompanhamento(Yii::app()->session['CodPessoaAcompanhamentoPonto'], true) : ''),
                'pessoaAcompanhamento' => (isset($_GET['p']) && is_numeric($_GET['p']) ? $_GET['p'] : 
                    (isset(Yii::app()->session['CodPessoaAcompanhamentoPonto']) ? Yii::app()->session['CodPessoaAcompanhamentoPonto'] : '')),
                'abaAtiva' => 'acompanhamento',
            ));
        } else {
            // nao e chefe
            $this->render('/registro/mensagem', array('mensagem' => 'Você não possui cargo de chefia.', 'classe' => 'Info'));
        }
    }
    
    /**
     * Action utilizada para permitir que um gerente visuaize os registros de um
     * funcionário específico passando seu id.
     * 
     * O id do funcionário deve ser passado utilizando o método POST e sendo 
     * referenciado com a chave "p".
     * 
     * Caso o usuário que requisitou os dados não tenha permissão para acessar
     * os registros de outro funcionário uma mensagem de erro é exibida.
     */
    public function actionPessoa()
        {
        $id_pessoa = intval($_POST['p']);
        
        $restricaoChefia = "";
        $gerencia = Yii::app()->user->checkAccess(APLICACAO_GERENCIA);
        if (!$gerencia) {
            $orgaosPermissaoAcompanhamento = Helper::coalesce(implode(',', Helper::getHierarquiaOrgaosPermissao(Yii::app()->user->id_pessoa, APLICACAO_ACOMPANHAMENTO)), 0);
            $orgaosChefia = Helper::coalesce(implode(',', Helper::getHierarquiaOrgaosChefia(Yii::app()->user->id_pessoa)), 0);
            $restricaoChefia = "and (
                    DadoFuncional.orgao_exercicio in (
                        $orgaosChefia
                    ) 
                    or DadoFuncional.orgao_exercicio in (
                        $orgaosPermissaoAcompanhamento
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
            print $this->exibeAcompanhamento($id_pessoa, true);
        } else {
            // nao tem permissao
            print $this->renderPartial('/registro/mensagem', array('mensagem' => 'Você não tem permissão para ver os registros desse servidor.', 'classe' => 'Info'), true);
        }
    }
    
    /**
     * Action responsável por buscar todas os usuários subordinados com nome ou 
     * id parecido com um termo passado por parâmetro.
     * 
     * A comparação é feita no banco de dados utilizando o operador LIKE.
     * 
     * Os resultados são devolvidos em formato JSON utilizando a instrução
     * <code>print CJSON::encode($opcoes)</code> como no exemplo abaixo:
     * 
     * <code>
     * {
     *  "id": 0,
     *  "label": "0 - Nome",
     *  "text": "0 - Nome"
     * }
     * </code>
     * 
     * @param string $term Texto a ser usado na comparação com o nome e o id
     * @return string JSON contendo os resultados encontrados
     */
    public function actionSubordinados($term)
    {
        $term = strtoupper(str_replace("'", "''", Helper::tiraAcento(trim($term))));
        $orgaosPermissaoAcompanhamento = Helper::coalesce(implode(',', Helper::getHierarquiaOrgaosPermissao(Yii::app()->user->id_pessoa, APLICACAO_ACOMPANHAMENTO)), 0);
        $orgaosChefia = Helper::coalesce(implode(',', Helper::getHierarquiaOrgaosChefia(Yii::app()->user->id_pessoa)), 0);
        $pessoas = Pessoa::model()->with(array(
            'DadoFuncional' => array(
                'select' => '',
                'on' => 'coalesce(DadoFuncional.data_desligamento, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP() 
                        and coalesce(DadoFuncional.data_aposentadoria, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP()',
                'joinType' => 'inner join'
            )
        ))->findAll(array(
            'select' => 't.id_pessoa, t.nome_pessoa',
            'condition' => "
                ( t.nome_pessoa like '%$term%' COLLATE utf8_general_ci or LTRIM(CAST(t.id_pessoa AS char(12))) = '$term' )
                and t.id_pessoa <> :id_pessoa
                and (
                    DadoFuncional.orgao_exercicio in (
                        $orgaosChefia
                    ) 
                    or DadoFuncional.orgao_exercicio in (
                        $orgaosPermissaoAcompanhamento
                    ) 
                )",
            'params' => array(
                ':id_pessoa' => Yii::app()->user->id_pessoa,
            ),
            'order' => 't.nome_pessoa'
        ));
        
        $opcoes = array();
        if (!empty($pessoas)) {
            foreach ($pessoas as $pessoa) {
                $opcoes[] = array(
                    'id' => $pessoa->id_pessoa,
                    'label' => $pessoa->id_pessoa." - ".$pessoa->nome_pessoa,
                    'text' => $pessoa->id_pessoa." - ".$pessoa->nome_pessoa
                );
            }
        }
        else {
            $opcoes[] = array(
                'id' => '',
                'label' => 'Nenhum servidor encontrado',
                'text' => 'Nenhum servidor encontrado'
            );
        }

        print CJSON::encode($opcoes);
    }
    
    /**
     * Método utilizado para reaproveitamento de código. Ele busca os dados 
     * básicos do usuário através de sua chave primária e mostra suas informações 
     * na tela.
     * 
     * Caso nenhum objeto da classe {@see Pessoa} seja encontrada a partir do
     * valor do parâmetro $id_pessoa uma mensagem de erro é exibida.
     * 
     * Por padrão o valor do parâmetro $viaAjax é FALSE, indicando que uma 
     * requisição síncrona será feita. Dessa forma é utilizado o método render 
     * para mostrar a tela. Caso o parâmetro tenha valor TRUE o processo será
     * feito de forma assíncrona utilizando o método renderPartial.
     * 
     * @param int $id_pessoa Chave primária da classe Pessoa
     * @param boolean $viaAjax Variável para definir o tipo de requisição
     */
    private function exibeAcompanhamento($id_pessoa, $viaAjax = false) 
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

                $diasUteisMes = Ponto::getNrDiasUteis(date("m"), date("Y"));
                $diasUteisMesAteHoje = Ponto::getNrDiasUteis(date("m"), date("Y"), true);
                $cargaHorariaDiaria = ($pessoa->DadosFuncionais->regime_trabalho/5); // regime de trabalho / 5 
                $cargaHorariaSemanal = $pessoa->DadosFuncionais->regime_trabalho; // regime de trabalho
                $cargaHorariaMensal = ($pessoa->DadosFuncionais->regime_trabalho/5)*$diasUteisMes; // regime de trabalho / 5 * numero de dias uteis no mes
                $cargaHorariaMensalAteHoje = ($pessoa->DadosFuncionais->regime_trabalho/5)*$diasUteisMesAteHoje; // regime de trabalho / 5 * numero de dias uteis no mes ate hoje
                // PROVISORIO
                // Devido a quarta-feira de cinzas, que e de meio turno, diminui 4 horas se o mes e fevereiro
                if (date("m") == 2)
                    $cargaHorariaMensal -= 4;
                
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
                // busca carga horaria do mes anterior
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
                
                // cria um array com todos os dias do mes
                $registrosDia = Ponto::getCalendarioMes($mesSelecionado, $anoSelecionado);
                $abonos = Abono::getAbonosMes($id_pessoa, $pessoa->DadosFuncionais->nr_vinculo, $mesSelecionado, $anoSelecionado);
                $diasComAbono = $abonos['diasComAbono'];
                $diasComAbonoPendente = $abonos['diasComAbonoPendente'];
                $totalAbono = $abonos['totalAbono'];
                $compensacoes = Compensacao::getCompensacoesMes($id_pessoa, $pessoa->DadosFuncionais->nr_vinculo, $mesSelecionado, $anoSelecionado);
                $diasComCompensacao = $compensacoes['diasComCompensacao'];
                $diasComCompensacaoPendente = $compensacoes['diasComCompensacaoPendente'];
                $totalCompensacao = $compensacoes['totalCompensacao'];
                $diaUltimoAbonoCompensacao = 0;
                // seta os abonos e compensacoes nos dias do calendario
                for ($i = 1; $i <= count($registrosDia); $i++) {
                    // se tem abono para esse dia, seta o valor
                    if (isset($diasComAbono[ $registrosDia[$i]['Data'] ])) {
                        $registrosDia[$i]['MinutosAbono'] = $diasComAbono[ $registrosDia[$i]['Data'] ];
                        $registrosDia[$i]['AbonoPendente'] = $diasComAbonoPendente[ $registrosDia[$i]['Data'] ];
                        $diaUltimoAbonoCompensacao = $i;
                    }
                    // se tem compensacao para esse dia, seta o valor
                    if (isset($diasComCompensacao[ $registrosDia[$i]['Data'] ])) {
                        $registrosDia[$i]['MinutosCompensacao'] = $diasComCompensacao[ $registrosDia[$i]['Data'] ];
                        $registrosDia[$i]['CompensacaoPendente'] = $diasComCompensacaoPendente[ $registrosDia[$i]['Data'] ];
                        $diaUltimoAbonoCompensacao = $i;
                    }
                }
                $registros = PontoEAjuste::getRegistrosMes($id_pessoa, $pessoa->DadosFuncionais->nr_vinculo, $mesSelecionado, $anoSelecionado);
                
                // preenche o subarray de registros por dia no calendario
                $ultimoTipoRegistro = '';
                $ultimoRegistro = NULL;
                $ultimoDia = 0;
                $ultimaSaida = 0;
                $jornadaDiaria = 0;
                $jornadaMensal = 0;
                $mudouDia = false;
                foreach ($registros as $registro) {
                    $mudouDia = ($ultimoDia != date("d/m/Y", strtotime($registro->data_hora_ponto)));
                    // se e um registro de entrada
                    if ($registro->entrada_saida == 'E') {
                        // pega o ultimo dia, caso precise registrar uma saida em branco ou a jornada total do dia anterior
                        $dia = intval(date('d', strtotime($ultimoRegistro)));
                        if ($ultimoTipoRegistro == 'E'){ //Entrada-Entrada 
                            $registrosDia[$dia]['Registros'][] = array(
                                'tipo' => 'S',
                                'registro' => NULL,
                                'dataAuxiliar' => ($mudouDia ? $ultimoRegistro : $registro->data_hora_ponto),
                                'id_pessoa' => $registro->id_pessoa,
                                'tempoTrabalhado' => '?',
                            );
                            $ultimaSaida = 0;
                            $ultimoDia = date("d/m/Y", strtotime($registro->data_hora_ponto));
                        }
                        if ($mudouDia && ($ultimoDia != 0)){ // mudou o dia, zera a jornada diaria
                            $jornadaMensal += $jornadaDiaria;
                            // marca a jornada diaria do dia anterior
                            $registrosDia[$dia]['MinutosRegistro'] = $jornadaDiaria;
                            $jornadaDiaria = 0;
                        }
                        if (($ultimoTipoRegistro == 'S') && (date("d/m/Y", strtotime($ultimoRegistro)) == date("d/m/Y", strtotime($registro->data_hora_ponto)))){  // se existe uma saida anterior no mesmo dia
                            $tempoIntervalo = (strtotime($registro->data_hora_ponto)-strtotime($ultimoRegistro))/60;
                        }
                        else {
                            $tempoIntervalo = ($mudouDia ? '' : '?');
                        }
                        $dia = intval(date('d', strtotime($registro->data_hora_ponto)));
                        $registrosDia[$dia]['Registros'][] = array(
                            'tipo' => 'E',
                            'registro' => $registro,
                            'dataAuxiliar' => $registro->data_hora_ponto,
                            'id_pessoa' => $registro->id_pessoa,
                            'tempoIntervalo' => $tempoIntervalo,
                        );
                    } else { // se e um registro de saida
                        if ($ultimoTipoRegistro == 'S') { //Saida-Saida 
                            if ($mudouDia && ($ultimoDia != 0)){ // mudou o dia, zera a jornada diaria
                                $jornadaMensal += $jornadaDiaria;
                                // marca a jornada diaria do dia anterior
                                $dia = intval(date('d', strtotime($ultimoRegistro)));
                                $registrosDia[$dia]['MinutosRegistro'] = $jornadaDiaria;
                                $jornadaDiaria = 0;
                            }
                            $dia = intval(date('d', strtotime($registro->data_hora_ponto)));
                            $registrosDia[$dia]['Registros'][] = array(
                                'tipo' => 'E',
                                'registro' => NULL,
                                'dataAuxiliar' => $registro->data_hora_ponto,
                                'id_pessoa' => $registro->id_pessoa,
                                'tempoIntervalo' => '?',
                            );
                        } elseif ($mudouDia) { //saida apos entrada, mas em outro dia
                            // como mudou o dia, vai gerar registros para os dois dias que o servidor passou em trabalho
                            // fecha um registro de saida as 23:59 do primeiro dia
                            $jornadaDoTurno = (strtotime(date("Y-m-d", strtotime($ultimoRegistro))." 23:59:59")-strtotime($ultimoRegistro)) /60;
                            $jornadaDiaria += $jornadaDoTurno;
                            $dia = intval(date('d', strtotime($ultimoRegistro)));
                            $registrosDia[$dia]['Registros'][] = array(
                                'tipo' => 'S',
                                'registro' => '-',
                                'dataAuxiliar' => $registro->data_hora_ponto,
                                'id_pessoa' => $registro->id_pessoa,
                                'tempoTrabalhado' => $jornadaDoTurno,
                            );
                            $jornadaMensal += $jornadaDiaria;
                            $registrosDia[$dia]['MinutosRegistro'] = $jornadaDiaria;
                            $jornadaDiaria = 0;
                            // comeca um novo registro as 0 horas do segundo dia
                            $ultimoRegistro = date('Y-m-d', strtotime($registro->data_hora_ponto))." 00:00:00";
                            $dia = intval(date('d', strtotime($registro->data_hora_ponto)));
                            $registrosDia[$dia]['Registros'][] = array(
                                'tipo' => 'E',
                                'registro' => '-',
                                'dataAuxiliar' => $registro->data_hora_ponto,
                                'id_pessoa' => $registro->id_pessoa,
                                'tempoIntervalo' => '-',
                            );
                        }
                        if ($ultimoTipoRegistro == 'E') {
                            $jornadaDoTurno = (strtotime($registro->data_hora_ponto)-strtotime($ultimoRegistro))/60;
                            $jornadaDiaria += $jornadaDoTurno; 
                        } else {
                            $jornadaDoTurno = '?';
                        }
                        $dia = intval(date('d', strtotime($registro->data_hora_ponto)));
                        $registrosDia[$dia]['Registros'][] = array(
                            'tipo' => 'S',
                            'registro' => $registro,
                            'dataAuxiliar' => $registro->data_hora_ponto,
                            'id_pessoa' => $registro->id_pessoa,
                            'tempoTrabalhado' => $jornadaDoTurno,
                        );
                        $ultimaSaida = $registro->data_hora_ponto;
                    }
                    $ultimoDia = date("d/m/Y", strtotime($registro->data_hora_ponto));
                    $ultimoTipoRegistro = $registro->entrada_saida;
                    $ultimoRegistro = $registro->data_hora_ponto;  
                }
                if ($ultimoTipoRegistro == 'E') {
                    $dia = intval(date('d', strtotime($ultimoRegistro)));
                    $registrosDia[$dia]['Registros'][] = array(
                        'tipo' => 'S',
                        'registro' => '',
                        'dataAuxiliar' => $registro->data_hora_ponto,
                        'id_pessoa' => $registro->id_pessoa,
                        'tempoTrabalhado' => '?',
                    );
                }
                $jornadaMensal += $jornadaDiaria;
                $diaUltimoRegistro = intval(date('d', strtotime($ultimoRegistro)));
                $registrosDia[$diaUltimoRegistro]['MinutosRegistro'] = $jornadaDiaria;

                // busca os afastamentos
                $horasAfastamento = 0;
                $horasAfastamentoAteHoje = 0;
                $afastamentos = Abono::getAfastamentos($pessoa->DadosFuncionais->matricula, $pessoa->DadosFuncionais->nr_vinculo, $mesSelecionado, $anoSelecionado);
                if (!empty($afastamentos)) {
                    foreach ($afastamentos as $afastamento) {
                        $horasAfastamento += ($afastamento['nr_dias_uteis'] * $pessoa->DadosFuncionais->regime_trabalho/5);
                        $horasAfastamentoAteHoje += ($afastamento['NrDiasUteisAteHoje'] * $pessoa->DadosFuncionais->regime_trabalho/5);
                    }
                }
                $dados = array(
                    'anos' => $anos,
                    'anoSelecionado' => $anoSelecionado,
                    'meses' => $meses,
                    'mesSelecionado' => $mesSelecionado,
                    'registrosDia' => $registrosDia,
                    'diaUltimoRegistro' => ($diaUltimoRegistro > $diaUltimoAbonoCompensacao ? $diaUltimoRegistro : $diaUltimoAbonoCompensacao),
                    'totalRegistros' => $jornadaMensal,
                    'totalAbono' => $totalAbono,
                    'totalCompensacao' => $totalCompensacao,
                    'pessoa' => $pessoa,
                    'jornadaDiaria' => Ponto::getJornada('D', $pessoa->DadosFuncionais->nr_vinculo, $id_pessoa)/60, // em minutos
                    'jornadaSemanal' => Ponto::getJornada('S', $pessoa->DadosFuncionais->nr_vinculo, $id_pessoa)/60, // em minutos
                    'jornadaMensal' => Ponto::getJornada('M', $pessoa->DadosFuncionais->nr_vinculo, $id_pessoa)/60, // em minutos
                    'cargaHorariaDiaria' => $cargaHorariaDiaria,
                    'cargaHorariaSemanal' => $cargaHorariaSemanal,
                    'cargaHorariaMensal' => $cargaHorariaMensal,
                    'cargaHorariaMensalAteHoje' => $cargaHorariaMensalAteHoje,
                    'cargaHorariaMesSelecionado' => $cargaHorariaMesSelecionado,
                    'saldoMesAnterior' => $saldoMesAnterior,
                    'saldoMesAntesAnterior' => $saldoMesAntesAnterior,
                    'compensacaoMesAnterior' => (!empty($compensacaoMesAnterior) ? $compensacaoMesAnterior : 0),
                    'afastamentos' => $afastamentos,
                    'horasAfastamento' => $horasAfastamento*60, // em minutos
                    'horasAfastamentoAteHoje' => $horasAfastamentoAteHoje*60, // em minutos
                    'viaAjax' => $viaAjax,
                );
                if (!$viaAjax) {
                    $this->render('index', $dados);
                } else {
                    return $this->renderPartial('index', $dados, true);
                }
            } else {
                // mais de um vinculo
                $dados = array(
                    'pessoa' => $pessoa,
                    'viaAjax' => $viaAjax,
                );
                if (!$viaAjax) {
                    $this->render('index', $dados);
                } else {
                    return $this->renderPartial('index', $dados, true);
                }
            }
        } else {
            $this->render('/registro/mensagem', array('mensagem' => 'O ponto eletrônico não está liberado para o seu vínculo.', 'classe' => 'Info'));
        }
    }
}