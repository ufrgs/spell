<?php

/* 
    Document   : exibicao
    Created on : 09/05/2016, 15:55:51
    Author     : thiago
*/

print CHtml::tag('h1', array(), $orgao->nome_orgao, true);
print CHtml::tag('h2', array(), 'Relatório de Registro em Ponto Eletrônico - '.$periodo, true);

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProviderRegistros,
    'columns' => array(
        array(
            'name' => 'Pessoa.nome_pessoa',
            'value' => 'CHtml::link($data->Pessoa->nome_pessoa, Yii::app()->createUrl("acompanhamento/acompanhamentoChefia", array("p" => $data->id_pessoa, "v" => $data->nr_vinculo, "a" => '.$ano.', "m" => '.$mes.'))).($data->DadoFuncional->orgao_exercicio != '.$orgao->id_orgao.' ? "<br/>(".$data->DadoFuncional->OrgaoExercicio->nome_orgao.")" : "")',
            'sortable' => true,
            'type' => 'raw',
            'header' => 'Nome Servidor'
        ),
        array(
            'name' => 'DadoFuncional.CatFuncional.nome_categoria',
            'sortable' => true,
            'header' => 'Cargo',
        ),
        array(
            'name' => 'DadoFuncional.regime_trabalho',
            'sortable' => true,
            'header' => 'Regime<br/>Trabalho',
            'htmlOptions' => array('class' => 'textoCentralizado'),
        ),
        array(
            'name' => 'nr_minutos_trabalho',
            'sortable' => true,
            'value' => 'Helper::transformaEmFormatoHora($data->nr_minutos_trabalho)',
            'header' => 'Tempo<br/>Trabalho',
            'htmlOptions' => array('class' => 'textoADireita'),
        ),
        array(
            'name' => 'nr_minutos_abono',
            'sortable' => true,
            'value' => 'Helper::transformaEmFormatoHora($data->nr_minutos_abono)',
            'header' => 'Tempo<br/>Abono',
            'htmlOptions' => array('class' => 'textoADireita'),
        ),
        array(
            'name' => 'nr_minutos_previsto',
            'sortable' => true,
            'value' => 'Helper::transformaEmFormatoHora($data->nr_minutos_previsto)',
            'header' => 'Tempo<br/>Previsto',
            'htmlOptions' => array('class' => 'textoADireita'),
        ),
        array(
            'name' => 'nr_minutos_saldo',
            'sortable' => true,
            'value' => 'Helper::transformaEmFormatoHora($data->nr_minutos_saldo)',
            'header' => 'Saldo',
            'cssClassExpression' => '($data->nr_minutos_saldo < 0 ? "italico" : "textoVerde")." textoADireita"',
            //'htmlOptions' => array('class' => 'textoADireita'),
        ),
        array(
            'name' => 'CargaHorariaMesAnterior',
            'sortable' => true,
            'value' => 'Helper::transformaEmFormatoHora($data->getSaldoMesAnterior())',
            'header' => 'Saldo Mês<br/>Anterior',
            'cssClassExpression' => '($data->getSaldoMesAnterior() < 0 ? "italico" : "textoVerde")." textoADireita"',
            //'htmlOptions' => array('class' => 'textoADireita'),
        ),
    ),
    'ajaxUpdate' => true,
));

print CHtml::tag('h2', array(), 'Servidores sem Registro em Ponto Eletrônico - '.$periodo, true);

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProviderSemRegistros,
    'columns' => array(
        array(
            'name' => 'Pessoa.nome_pessoa',
            'value' => '$data->Pessoa->nome_pessoa.($data->orgao_exercicio != '.$orgao->id_orgao.' ? "<br/>(".$data->OrgaoExercicio->nome_orgao.")" : "")',
            'sortable' => true,
            'type' => 'raw',
            'header' => 'Nome Servidor'
        ),
        array(
            'name' => 'CatFuncional.nome_categoria',
            'sortable' => true,
            'header' => 'Cargo',
        ),
        array(
            'name' => 'regime_trabalho',
            'sortable' => true,
            'header' => 'Regime<br/>Trabalho',
            'htmlOptions' => array('class' => 'textoCentralizado'),
        ),
    ),
    'ajaxUpdate' => true,
));

print CHtml::link('Voltar', Yii::app()->createUrl('relatorio/index'), array('class' => 'button'));