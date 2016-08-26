<? 
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->baseUrl."/js/compensacao.js", CClientScript::POS_END);
?>

<fieldset class="fieldInfo">
    <legend>Instruções</legend>
    Clique no nome do servidor para ver mais informações sobre o pedido.
</fieldset>

<h2>Registros de compensação</h2>

<?  
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $pedidosAbertos,
        'columns' => array(
            array(
                'header' => '',
                'value' => 'CHtml::checkBox("certificarCompensacao", false, array("value" => $data->nr_compensacao))',
                'type' => 'raw',
            ),
            array(
                'name' => 'Pessoa.nome_pessoa',
                'sortable' => true,
                'value' => 'CHtml::link($data->Pessoa->nome_pessoa, "javascript:verPedido($data->nr_compensacao)")',
                'header' => 'Servidor',
                'type' => 'raw',
            ),
            array(
                'name' => 'nome_categoria',
                'sortable' => true,
                'value' => '$data->DadoFuncional->CatFuncional->nome_categoria." - ".$data->DadoFuncional->regime_trabalho."h<br/>".$data->DadoFuncional->OrgaoExercicio->nome_orgao',
                'type' => 'raw',
                'header' => 'Cargo',
            ),
            array(
                'name' => 'data_compensacao',
                'sortable' => true,
                'value' => 'date("d/m/Y", strtotime($data->data_compensacao))',
                'header' => 'Dia'
            ),
            array(
                'name' => 'periodo_compensacao',
                'value' => 'Helper::transformaEmFormatoHora($data->periodo_compensacao)',
                'header' => 'Horas',
            ),
            array(
                'value' => '$data->justificativa',
                'header' => 'justificativa',
            ),
        ),
    ));
?>

<button onclick="certificarSelecionados()">Certificar registros de compensação selecionados</button><br/>
<br/>
<h2>Registros de compensações certificados</h2>

<?  
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $pedidosCertificados,
        'columns' => array(
            array(
                'name' => 'Pessoa.nome_pessoa',
                'sortable' => true,
                'value' => 'CHtml::link($data->Pessoa->nome_pessoa, "javascript:verPedidoCertificado($data->nr_compensacao)")',
                'header' => 'Servidor',
                'type' => 'raw',
            ),
            array(
                'name' => 'nome_categoria',
                'sortable' => true,
                'value' => '$data->DadoFuncional->CatFuncional->nome_categoria." - ".$data->DadoFuncional->regime_trabalho."h<br/>".$data->DadoFuncional->OrgaoExercicio->nome_orgao',
                'type' => 'raw',
                'header' => 'Cargo',
            ),
            array(
                'name' => 'data_compensacao',
                'sortable' => true,
                'value' => 'date("d/m/Y", strtotime($data->data_compensacao))',
                'header' => 'Dia'
            ),
            array(
                'name' => 'periodo_compensacao',
                'value' => 'Helper::transformaEmFormatoHora($data->periodo_compensacao)',
                'header' => 'Horas',
            ),
            array(
                'value' => '$data->justificativa',
                'header' => 'justificativa',
            ),
            array(
                'name' => 'indicador_certificado',
                'sortable' => true,
                'value' => '($data->indicador_certificado == "S" ? "Certificado" : "Não certificado")." por<br/>".$data->Certificador->nome_pessoa',
                'type' => 'raw',
                'header' => 'Certificado'
            ),
        ),
    ));
?>

<div id="modal"></div>