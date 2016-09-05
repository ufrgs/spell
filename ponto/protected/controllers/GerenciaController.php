<?php

/* 
    Document   : GerenciaController
    Created on : 01/12/2015, 11:37:08
    Author     : thiago
*/

class GerenciaController extends BaseController
{
    public function actionIndex()
    {
        $registros = new Ponto('search');
        if (isset($_GET['Ponto'])) {
            $registros->attributes = array_map("utf8_decode", $_GET['Ponto']);  //Campos do modelo
        }
        
        $ajustes = new Ajuste('search');
        if (isset($_GET['Ajuste'])) {
            $ajustes->attributes = array_map("utf8_decode", $_GET['Ajuste']);  //Campos do modelo
        }
        
        $abonos = new Abono('search');
        if (isset($_GET['Abono'])) {
            $abonos->attributes = array_map("utf8_decode", $_GET['Abono']);  //Campos do modelo
        }

        $this->render('index', array(
            'registros' => $registros,
            'ajustes' => $ajustes,
            'abonos' => $abonos,
            'acompanhamento' => '',
        ));
    }
    
    public function actionExcluirPedido() 
    {
        if (isset($_POST['nr'], $_POST['tipo']) && in_array($_POST['tipo'], array('ajuste', 'abono'))) { 
            if ($_POST['tipo'] == 'ajuste') {
                $pedido = Ajuste::model()->findByPk($_POST['nr']);
                $chave = 'nr_ajuste';
                $dataPedido = $pedido->data_hora_ponto;
            }
            else {
                $pedido = Abono::model()->findByPk($_POST['nr']);
                $chave = 'nr_abono';
                $dataPedido = $pedido->data_abono;
            }
        
            $matriculaServidor = $pedido->matricula;
            $nrVinculo = $pedido->nr_vinculo;
            ArquivoAjuste::model()->deleteAllByAttributes(array($chave => $_POST['nr']));
            if ($pedido->delete()) {
                // se a exclusao esta acontecendo apos o fechamento do mes do pedido, recalcula o total de horas
                if ((date('m') > date('m', strtotime($dataPedido))) || (date('Y') > date('Y', strtotime($dataPedido)))) {
                    $mesAnterior = (date('m') != 1 ? date('m')-1 : 12);
                    $anoAnterior = (date('m') != 1 ? date('Y') : date('Y')-1);
                    CargaHorariaMesServidor::buscaDadosESalva($matriculaServidor, $nrVinculo, $mesAnterior, $anoAnterior);
                }
                print 'Pedido excluído com sucesso!';
            }
            else {
                print 'Ocorreu um erro ao excluir o pedido.'.print_r($pedido->getErrors(), true);
            }
        }
    }
    
    public function actionServidores($term)
    {
        $term = strtoupper(str_replace("'", "''", Helper::tiraAcento(trim($term))));
        $pessoas = Pessoa::model()->with(array(
            'DadoFuncional' => array(
                'select' => '',
                'on' => 'coalesce(DadoFuncional.data_desligamento, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP() 
                        and coalesce(DadoFuncional.data_aposentadoria, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP()',
                'joinType' => 'inner join'
            )
        ))->findAll(array(
            'select' => 't.id_pessoa, t.nome_pessoa, t.nome_pessoa',
            'condition' => "
                ( t.nome_pessoa like '%$term%' COLLATE utf8_general_ci or LTRIM(CAST(t.id_pessoa AS char(12))) = '$term' )
                and t.id_pessoa <> :id_pessoa",
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
        //Yii::app()->end();
    }
    
    public function actionCorrigePendencias()
    {
        $sql = "update PENDENCIASISTEMACHEFIA set
                    DataCancelamentoPendencia = CURRENT_TIMESTAMP()
                where
                    PENDENCIASISTEMACHEFIA.DataResolucaoPendencia is null
                    and (
                        (	PENDENCIASISTEMACHEFIA.IDServico = 33
                            and not exists (
                                select 1 
                                from ajuste
                                where
                                    matricula = PENDENCIASISTEMACHEFIA.matricula
                                    and nr_vinculo = PENDENCIASISTEMACHEFIA.nr_vinculo
                                    and data_hora_certificacao is null
                                union
                                select 1 
                                from abono
                                where
                                    matricula = PENDENCIASISTEMACHEFIA.matricula
                                    and nr_vinculo = PENDENCIASISTEMACHEFIA.nr_vinculo
                                    and data_hora_certificacao is null
                            )
                        )
                        or (PENDENCIASISTEMACHEFIA.IDServico = 40
                            and not exists (
                                select 1 
                                from compensacao
                                where
                                    matricula = PENDENCIASISTEMACHEFIA.matricula
                                    and nr_vinculo = PENDENCIASISTEMACHEFIA.nr_vinculo
                                    and data_hora_certificacao is null
                            )
                        )
                    )";
        $query = Yii::app()->db->createCommand($sql)->execute();
        if ($query) {
            return 'Pendências corrigidas com sucesso!';
        }
        else {
            return 'Ocorreu um erro...';
        }
    }
}
