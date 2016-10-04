<? 
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->baseUrl."/js/pedidosCertificados.js", CClientScript::POS_END);
?>

<fieldset class="fieldInfo">
    <legend>Instruções</legend>
    Clique no nome do servidor para ver mais informações sobre o pedido.
</fieldset>

<h2>Pedidos de ajuste certificados</h2>

<?  
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $ajustes,
        'columns' => array(
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
            array(
                'name' => 'indicador_certificado',
                'sortable' => true,
                'value' => '($data->indicador_certificado == "S" ? "<span class=\"textoVerde\">Certificado" : "<span class=\"textoVermelho\">Não certificado")."</span> por<br/>".$data->Certificador->nome_pessoa',
                'type' => 'raw',
                'header' => 'Certificado'
            ),
        ),
    ));
?>

<h2>Pedidos de abono certificados</h2>

<?  
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $abonos,
        'columns' => array(
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