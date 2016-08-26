<? 
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->baseUrl."/js/pedidosAvaliacao.js", CClientScript::POS_END);
?>

<fieldset class="fieldInfo">
    <legend>Instruções</legend>
    Clique no nome do servidor para ver mais informações sobre o pedido.
</fieldset>

<h2>Pedidos de ajuste</h2>

<?  
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $ajustes,
        'columns' => array(
            array(
                'header' => '',
                'value' => 'CHtml::checkBox("certificarAjuste", false, array("value" => $data->nr_ajuste))',
                'type' => 'raw',
            ),
            array(
                'name' => 'Pessoa.nome_pessoa',
                'sortable' => true,
                'value' => 'CHtml::link($data->Pessoa->nome_pessoa, "javascript:verPedido($data->nr_ajuste, \'ajuste\')")',
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
                'name' => 'entrada_saida',
                'sortable' => true,
                'value' => '($data->entrada_saida == "E" ? "Entrada" : "Saída")',
                'header' => 'Registro'
            ),
            array(
                'name' => 'data_hora_ponto',
                'sortable' => true,
                'value' => 'date("d/m/Y", strtotime($data->data_hora_ponto))',
                'header' => 'Dia'
            ),
            array(
                'value' => 'date("H:i", strtotime($data->data_hora_ponto))',
                'header' => 'Hora',
            ),
            array(
                'value' => '(trim($data->justificativa) != "" ? $data->justificativa : $data->JustificativaAjuste->texto_justificativa)',
                'header' => 'justificativa',
            ),
        ),
    ));
?>

<button onclick="certificarSelecionados('Ajuste')">Certificar pedidos de ajuste selecionados</button>

<h2>Pedidos de abono</h2>

<?  
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $abonos,
        'columns' => array(
            array(
                'header' => '',
                'value' => 'CHtml::checkBox("certificarAbono", false, array("value" => $data->nr_abono))',
                'type' => 'raw',
            ),
            array(
                'name' => 'Pessoa.nome_pessoa',
                'sortable' => true,
                'value' => 'CHtml::link($data->Pessoa->nome_pessoa, "javascript:verPedido($data->nr_abono, \'abono\')")',
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
                'name' => 'data_abono',
                'sortable' => true,
                'value' => 'date("d/m/Y", strtotime($data->data_abono))',
                'header' => 'Dia'
            ),
            array(
                'name' => 'periodo_abono',
                'value' => 'Helper::transformaEmFormatoHora($data->periodo_abono)',
                'header' => 'Horas',
            ),
            array(
                'value' => '(trim($data->justificativa) != "" ? $data->justificativa : $data->JustificativaAjuste->texto_justificativa)',
                'header' => 'justificativa',
            ),
        ),
    ));
?>

<button onclick="certificarSelecionados('Abono')">Certificar pedidos de abono selecionados</button>

<div id="modal"></div>