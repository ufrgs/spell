<?php
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->baseUrl."/js/relatorio.js", CClientScript::POS_END);

print CHtml::form(Yii::app()->createUrl('relatorio/exibeCargaHorariaConsolidada'), 'get');

print CHtml::label('Selecione o órgão', 'orgao', array('class' => 'esquerdaAlinhado maior'));
print CHtml::dropDownList('orgao', '', CHtml::listData($orgaos, 'id_orgao', 'nome_orgao'), array(
    'onchange' => 'selecionaOrgao()',
    'empty' => '------------------'
));

print CHtml::tag('div', array('id' => 'periodos'), '', true);

print CHtml::endForm();