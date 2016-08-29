<?php

/*
  Document   : AjusteController
  Created on : 11/11/2015, 14:08:37
  Author     : thiago
 */
class CompensacaoController extends BaseController
{

    public function actionPedido()
    {
        $pessoa = Pessoa::model()->with(array(
            'DadosFuncionais' => array(
                'joinType' => 'inner join',
                'on' => 'coalesce(DadosFuncionais.data_desligamento, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP() 
                        and coalesce(DadosFuncionais.data_aposentadoria, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP() ' .
                (isset($_GET['v']) ? 'and DadosFuncionais.nr_vinculo = ' . intval($_GET['v']) : '')
            ),
            'DadosFuncionais.GrupoEmprego' => array(
                'joinType' => 'inner join',
                'on' => "GrupoEmprego.segmento_grupo = 'T'"
            ),
            'DadosFuncionais.CatFuncional',
            'DadosFuncionais.OrgaoLotacao',
            'DadosFuncionais.OrgaoExercicio',
        ))->findByPk(Yii::app()->user->id_pessoa);

        if ($pessoa) {
            if (count($pessoa->DadosFuncionais) == 1) {
                $pessoa->DadosFuncionais = $pessoa->DadosFuncionais[0];

                if ($pessoa->DadosFuncionais->regime_trabalho == 'DE')
                    $pessoa->DadosFuncionais->regime_trabalho = 40;

                $mesAnterior = (date('m') > 1 ? date('m')-1 : 12);
                $anoAnterior = (date('m') > 1 ? date('Y') : date('Y')-1);
                $chMesAnterior = CargaHorariaMesServidor::getCargaHorariaMes($pessoa->id_pessoa, $pessoa->DadosFuncionais->nr_vinculo, $mesAnterior, $anoAnterior);
                $saldoMesAnterior = (!empty($chMesAnterior) ? $chMesAnterior->nr_minutos_saldo : 0);
                $mesAntesAnterior = ($mesAnterior > 1 ? $mesAnterior-1 : 12);
                $anoAntesAnterior = ($mesAnterior > 1 ? $anoAnterior : $anoAnterior-1);
                $chMesAntesAnterior = CargaHorariaMesServidor::getCargaHorariaMes($pessoa->id_pessoa, $pessoa->DadosFuncionais->nr_vinculo, $mesAntesAnterior, $anoAntesAnterior);
                $saldoMesAntesAnterior = (!empty($chMesAntesAnterior) ? $chMesAntesAnterior->nr_minutos_saldo : 0);
                
                // se teve saldo negativo ha 2 meses, desconta esse saldo do saldo do mes anterior
                $saldoDisponivelCompensacao = $saldoMesAnterior + ($saldoMesAntesAnterior < 0 ? $saldoMesAntesAnterior : 0);
                
                $compensacaoAteHoje = Compensacao::getCargaHorariaCompensadaMes($pessoa->id_pessoa, $pessoa->DadosFuncionais->nr_vinculo, date('m'), date('Y'));
                
                $compensacoes = new Compensacao('search');
                $compensacoes->id_pessoa = $pessoa->id_pessoa;
                $compensacoes->nr_vinculo = $pessoa->DadosFuncionais->nr_vinculo;
                
                $this->render('pedido', array(
                    'pessoa' => $pessoa,
                    'compensacoes' => $compensacoes->search(),
                    'saldoMesAnterior' => $saldoMesAnterior,
                    'saldoMesAntesAnterior' => $saldoMesAntesAnterior,
                    'saldoDisponivelCompensacao' => $saldoDisponivelCompensacao,
                    'compensacaoAteHoje' => $compensacaoAteHoje,
                ));
            }
            else {
                // mais de um vinculo
                $this->render('pedido', array(
                    'pessoa' => $pessoa,
                ));
            }
        }
        else {
            $this->render('system.cpd.views.mensagem', array('mensagem' => 'O ponto eletrônico não está liberado para o seu vínculo.', 'classe' => 'Info'));
        }
    }

    public function actionEnviarPedido()
    {
        $msg = "";
        $erro = false;
        if (!isset($_POST['data']) || (trim($_POST['data']) == "")) {
            $msg .= "Dia para o registro não selecionado. <br/>";
        }
        if (!isset($_POST['hora']) || (trim($_POST['hora']) == "")) {
            $msg .= "Hora para o registro não selecionada. <br/>";
        }
        $data = implode("-", array_reverse(explode("/", $_POST['data'])));
        if (!isset($_POST['justificativa']) || trim($_POST['justificativa']) == "") {
            $msg .= "justificativa para o registro não informada. <br/>";
        }
        if ($msg != "") {
            $erro = true;
        }
        else {
            $msg = "Registro de compensação enviado com sucesso!";
            $nrVinculo = intval($_POST['nrVinculo']);
            $pessoa = Pessoa::model()->with(array(
                'DadoFuncional' => array(
                    'joinType' => 'inner join',
                    'on' => 'DadoFuncional.nr_vinculo = ' . $nrVinculo,
                )
            ))->findByPk(Yii::app()->user->id_pessoa);

            $mesAnterior = (date('m') > 1 ? date('m')-1 : 12);
            $anoAnterior = (date('m') > 1 ? date('Y') : date('Y')-1);
            $chMesAnterior = CargaHorariaMesServidor::getCargaHorariaMes($pessoa->id_pessoa, $pessoa->DadoFuncional->nr_vinculo, $mesAnterior, $anoAnterior);
            $saldoMesAnterior = (!empty($chMesAnterior) ? $chMesAnterior->nr_minutos_saldo : 0);
            $mesAntesAnterior = ($mesAnterior > 1 ? $mesAnterior-1 : 12);
            $anoAntesAnterior = ($mesAnterior > 1 ? $anoAnterior : $anoAnterior-1);
            $chMesAntesAnterior = CargaHorariaMesServidor::getCargaHorariaMes($pessoa->id_pessoa, $pessoa->DadoFuncional->nr_vinculo, $mesAntesAnterior, $anoAntesAnterior);
            $saldoMesAntesAnterior = (!empty($chMesAntesAnterior) ? $chMesAntesAnterior->nr_minutos_saldo : 0);

            $saldoDisponivelCompensacao = $saldoMesAnterior + ($saldoMesAntesAnterior < 0 ? $saldoMesAntesAnterior : 0);

            $command = Yii::app()->db->cache(10)->createCommand(); // cache de 10 segundos
            $compensacaoAteHoje = $command
                ->select('sum(periodo_compensacao)')
                ->from('compensacao')
                ->where("
                    id_pessoa = :id_pessoa 
                    and nr_vinculo = :nr_vinculo 
                    and coalesce(indicador_certificado, 'S') = 'S' -- considera um pedido em analise na contagem
                    and month(data_compensacao) = :mes
                    and year(data_compensacao) = :ano
                ", array(
                    ':id_pessoa' => $pessoa->id_pessoa,
                    ':nr_vinculo' => $pessoa->DadoFuncional->nr_vinculo,
                    ':mes' => date('m'),
                    ':ano' => date('Y'),
                ))->queryScalar();
            
            $saldoMinutos = $saldoDisponivelCompensacao - intval($compensacaoAteHoje);
            
            // periodo em minutos
            $aux = explode(":", $_POST['hora']);
            $periodoCompensacao = $aux[0]*60 + $aux[1];
            if ($saldoMinutos >= $periodoCompensacao) {
                $compensacao = new Compensacao();
                $compensacao->data_compensacao = $data;
                $compensacao->periodo_compensacao = $periodoCompensacao;
                $compensacao->id_pessoa = $pessoa->id_pessoa;
                $compensacao->matricula = $pessoa->DadoFuncional->matricula;
                $compensacao->nr_vinculo = $pessoa->DadoFuncional->nr_vinculo;
                $compensacao->id_pessoa_registro = $pessoa->id_pessoa;
                $compensacao->data_hora_registro = new CDbExpression("CURRENT_TIMESTAMP()");
                $compensacao->ip_registro = $_SERVER['REMOTE_ADDR'];
                $compensacao->justificativa = utf8_decode($_POST['justificativa']);

                $transacao = Yii::app()->db->beginTransaction();
                try {
                    if ($compensacao->save()) {
                        // TODO procedimento para avisar a chefia
                        $transacao->commit();
                    }
                    else {
                        $transacao->rollback();
                        $erro = true;
                        $msg = "Ocorreu um erro ao salvar o registro. " . print_r($compensacao->getErrors(), true);
                    }
                }
                catch (Exception $e) {
                    $transacao->rollback();
                    $erro = true;
                    $msg = "Ocorreu um erro ao salvar o registro. " . $e->getMessage();
                }
            }
            else {
                $erro = true;
                $msg = "O tempo selecionado é maior do que o saldo de horas ainda não utilizado (".Helper::transformaEmFormatoHora($saldoMinutos).").";
            }
        }

        print CJSON::encode(array(
                'erro' => $erro,
                'mensagem' => $msg
        ));
    }

    public function actionExcluirPedido() 
    {
        if (isset($_POST['nr'])) { 
            $pedido = Compensacao::model()->findByPk($_POST['nr']);
        
            if (($pedido->id_pessoa == Yii::app()->session['id_pessoa']) && (trim($pedido->indicador_certificado) == "")) {
                $pedido->indicador_excluido = 'S';
                $pedido->id_pessoa_registro = Yii::app()->session['id_pessoa'];
                $pedido->data_hora_registro = new CDbExpression('CURRENT_TIMESTAMP()');
                if ($pedido->save('indicador_excluido, id_pessoa_registro, data_hora_registro')) {
                    print 'Registro excluído com sucesso!';
                }
                else {
                    print 'Ocorreu um erro ao excluir o pedido.'.print_r($pedido->getErrors(), true);
                }
            }
            else {
                print 'Você não pode excluir esse pedido';
            }
        }
    }
    
    public function actionPedidosAvaliacao()
    {
        $orgaosChefia = Helper::getHierarquiaOrgaosChefia(Yii::app()->user->id_pessoa);
        if (!empty($orgaosChefia)) {
            // pedidos abertos
            $orgaosChefia = Helper::coalesce(implode(',', $orgaosChefia), 0);
            $criteriaAbertos = new CDbCriteria();
            $criteriaAbertos->with = array(
                'Pessoa' => array(
                    'select' => 'nome_pessoa'
                ),
                'DadoFuncional' => array(
                    'select' => 'regime_trabalho',
                ),
                'DadoFuncional.CatFuncional' => array(
                    'select' => 'nome_categoria'
                ),
                'DadoFuncional.OrgaoExercicio' => array(
                    'select' => 'sigla_orgao, nome_orgao'
                ), 'Certificador'
            );
            $criteriaAbertos->condition = "
                t.data_hora_certificacao is null 
                and coalesce(t.indicador_excluido, 'N') = 'N'
                and t.id_pessoa <> :id_pessoa1
                and DadoFuncional.orgao_exercicio in (
                    $orgaosChefia
                )";
            $criteriaAbertos->params = array(
                ':id_pessoa1' => Yii::app()->user->id_pessoa,
            );
            
            $criteriaCertificados = new CDbCriteria();
            $criteriaCertificados->with = array(
                'Pessoa' => array(
                    'select' => 'nome_pessoa'
                ),
                'DadoFuncional' => array(
                    'select' => 'regime_trabalho',
                ),
                'DadoFuncional.CatFuncional' => array(
                    'select' => 'nome_categoria'
                ),
                'DadoFuncional.OrgaoExercicio' => array(
                    'select' => 'sigla_orgao, nome_orgao'
                ), 'Certificador'
            );
            $criteriaCertificados->condition = "
                t.data_hora_certificacao is not null 
                and coalesce(t.indicador_excluido, 'N') = 'N'
                and t.id_pessoa <> :id_pessoa1
                and DadoFuncional.orgao_exercicio in (
                    $orgaosChefia
                )";
            $criteriaCertificados->params = array(
                ':id_pessoa1' => Yii::app()->user->id_pessoa,
            );

            $this->render('pedidosAvaliacao', array(
                'pedidosAbertos' => new CActiveDataProvider('Compensacao', array(
                    'criteria' => $criteriaAbertos,
                    'sort' => array(
                        'defaultOrder' => array(
                            'data_hora_registro' => CSort::SORT_ASC,
                        ),
                    ),
                )),
                'pedidosCertificados' => new CActiveDataProvider('Compensacao', array(
                    'criteria' => $criteriaCertificados,
                    'sort' => array(
                        'defaultOrder' => array(
                            'data_hora_registro' => CSort::SORT_ASC,
                        ),
                    ),
                )),
            ));
        }
        else {
            // nao e chefe
            $this->render('system.cpd.views.mensagem', array('mensagem' => 'Você não possui cargo de chefia.', 'classe' => 'Info'));
        }
    }

    public function actionDadosPedido()
    {
        $nrCompensacao = $_POST['nr'];
        $pedido = Compensacao::model()->cache(30)->with('Pessoa', 'Certificador')->findByPk($nrCompensacao);
        
        $registrosDoDia = PontoEAjuste::model()->findAll(array(
            'condition' => "id_pessoa = :id_pessoa
                and nr_vinculo = :nr_vinculo
                and DATE_FORMAT(data_hora_ponto, '%d/%m/%Y') = :Dia
                and (
                    (tipo = 'A' and indicador_certificado = 'S') 
                    or 
                    (tipo = 'R' and not exists (
                        select 1 from ajuste A
                        where A.nr_ponto = nr_seq
                    )))",
            'params' => array(
                ':id_pessoa' => $pedido->id_pessoa,
                ':nr_vinculo' => $pedido->nr_vinculo,
                ':Dia' => date('d/m/Y', strtotime($pedido->data_compensacao)),
            ),
            'order' => 'data_hora_ponto'
        ));
        print $this->renderPartial('dadosPedido', array('pedido' => $pedido, 'registrosDoDia' => $registrosDoDia), true);
    }

    public function actionDadosPedidoCertificado()
    {
        $nrCompensacao = $_POST['nr'];
        $pedido = Compensacao::model()->cache(30)->with('Pessoa', 'Certificador')->findByPk($nrCompensacao);
        print $this->renderPartial('dadosPedidoCertificado', array('pedido' => $pedido), true);
    }
    
    public function actionCertificaPedido()
    {
        if (isset($_POST['nrPedido']) && is_numeric($_POST['nrPedido']) && in_array($_POST['certifica'], array('S', 'N'))) {
            $erro = false;
            $msg = "Registro de compensação indicador_certificado com sucesso!";
            $orgaosChefia = Helper::getHierarquiaOrgaosChefia(Yii::app()->user->id_pessoa);
            $orgaosChefia = Helper::coalesce(implode(',', $orgaosChefia), 0);
            $criteria = array(
                'condition' => "
                    t.data_hora_certificacao is null 
                    and t.id_pessoa <> :id_pessoa1
                    and DadoFuncional.orgao_exercicio in (
                        $orgaosChefia
                    )",
                'params' => array(
                    ':id_pessoa1' => Yii::app()->user->id_pessoa,
                )
            );
            
            $pedido = Compensacao::model()->with('DadoFuncional')->findByPk($_POST['nrPedido'], $criteria);               
            
            if ($pedido) {
                $pedido->justificativa_certificacao = utf8_decode($_POST['justificativa']);
                $pedido->indicador_certificado = $_POST['certifica'];
                $pedido->id_pessoa_certificacao = Yii::app()->user->id_pessoa;
                $pedido->data_hora_certificacao = new CDbExpression("CURRENT_TIMESTAMP()");
                
                if ($pedido->save(true, array('indicador_certificado', 'id_pessoa_certificacao', 'data_hora_certificacao', 'justificativa_certificacao'))) {
                    // se a certificacao esta acontecendo apos o fechamento do mes do pedido, recalcula o total de horas
                    if (($pedido->indicador_certificado == 'S') && 
                        ((date('m') > date('m', strtotime($pedido->data_compensacao))) || (date('Y') > date('Y', strtotime($pedido->data_compensacao))))) {
                        $mesAnterior = (date('m') != 1 ? date('m')-1 : 12);
                        $anoAnterior = (date('m') != 1 ? date('Y') : date('Y')-1);
                        CargaHorariaMesServidor::buscaDadosESalva($pedido->matricula, $pedido->nr_vinculo, $mesAnterior, $anoAnterior);
                    }
                }
                else {
                    $erro = false;
                    $msg = "Ocorreu um erro ao certificar o registro.".print_r($pedido->getErrors(), true);
                }
            }
            else {
                $erro = true;
                $msg = "Registro não encontrado ou não autorizado.";
            }
        }
        else {
            $erro = true;
            $msg = "Erro na passagem de parâmetros.";
        }
        
        print CJSON::encode(array(
            'erro' => $erro,
            'mensagem' => $msg
        ));
    }

    public function actionCertificaVarios()
    {
        $erro = false;
        $msg = "Pedido(s) indicador_certificado(s) com sucesso!";
        if (isset($_POST['pedidos']) && is_array($_POST['pedidos'])) {
            $orgaosChefia = Helper::getHierarquiaOrgaosChefia(Yii::app()->user->id_pessoa);
            $orgaosChefia = Helper::coalesce(implode(',', $orgaosChefia), 0);
            $criteria = array(
                'condition' => "
                    t.data_hora_certificacao is null 
                    and t.id_pessoa <> :id_pessoa1
                    and DadoFuncional.orgao_exercicio in (
                        $orgaosChefia
                    )
                    and t.nr_compensacao in (".str_replace("'", "''", implode(",", $_POST['pedidos'])).")",
                'params' => array(
                    ':id_pessoa1' => Yii::app()->user->id_pessoa,
                )
            );
            $pedidos = Compensacao::model()->with('DadoFuncional')->findAll($criteria);
            
            if (!empty($pedidos)) {
                foreach($pedidos as $pedido) {
                    $pedido->indicador_certificado = 'S';
                    $pedido->id_pessoa_certificacao = Yii::app()->user->id_pessoa;
                    $pedido->data_hora_certificacao = new CDbExpression("CURRENT_TIMESTAMP()");

                    if ($pedido->save(true, array('indicador_certificado', 'id_pessoa_certificacao', 'data_hora_certificacao'))) {
                        // se a certificacao esta acontecendo apos o fechamento do mes do pedido, recalcula o total de horas
                        if (date('m') > date('m', strtotime($pedido->data_compensacao))) {
                            $mesAnterior = (date('m') != 1 ? date('m')-1 : 12);
                            $anoAnterior = (date('m') != 1 ? date('Y') : date('Y')-1);
                            CargaHorariaMesServidor::buscaDadosESalva($pedido->matricula, $pedido->nr_vinculo, $mesAnterior, $anoAnterior);
                        }
                    }
                    else {
                        $erro = false;
                        $msg = "Ocorreu um erro ao certificar o pedido.".print_r($pedido->getErrors(), true);
                        break;
                    }
                }
            }
            else {
                $erro = true;
                $msg = "Pedido não encontrado ou não autorizado.";
            }
        }
        else {
            $erro = true;
            $msg = "Erro na passagem de parâmetros.";
        }
        
        print CJSON::encode(array(
            'erro' => $erro,
            'mensagem' => $msg
        ));
    }

}