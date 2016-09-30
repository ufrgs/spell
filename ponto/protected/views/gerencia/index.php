<? 
$cs = Yii::app()->getClientScript();
$cs->registerCssFile(Yii::app()->baseUrl."/css/acompanhamento.css");
$cs->registerScriptFile(Yii::app()->baseUrl."/js/gerencia.js", CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->baseUrl."/js/circle-progress.js", CClientScript::POS_END);
?>

<style type="text/css">
.ui-tabs .ui-tabs-nav {
    clear: none;
    height: 28px;
}
</style>
<?php

ob_start();
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $registros->search(array(
        'pagination' => array('pageSize' => 20),
    )),
    'filter' => $registros,
    'columns' => array(
        array(
            'name' => 'nr_ponto',
            'sortable' => true,
            'header' => '#',
        ),
        array(
            'name' => 'id_pessoa',
            'sortable' => true,
            'header' => 'Número',
        ),
        array(
            'name' => 'Pessoa.nome_pessoa',
            'value' => '$data->Pessoa->nome_pessoa',
            'sortable' => true,
            'header' => 'Nome',
            'type' => 'raw',
        ),
        array(
            'name' => 'matricula',
            'sortable' => true,
            'header' => 'Matrícula',
        ),
        array(
            'name' => 'nr_vinculo',
            'sortable' => true,
            'header' => 'Vínculo',
        ),
        array(
            'name' => 'data_hora_ponto',
            'value' => 'date("d/m/Y H:i", strtotime($data->data_hora_ponto))',
            'type' => 'raw',
            'sortable' => true,
            'header' => 'Registro',
        ),
        array(
            'name' => 'entrada_saida',
            'sortable' => true,
            'header' => 'tipo',
        ),
        array(
            'name' => 'id_pessoa_registro',
            'sortable' => true,
            'header' => 'Cartão Reg.',
        ),
        array(
            'name' => 'data_hora_registro',
            'sortable' => true,
            'value' => 'date("d/m/Y H:i:s", strtotime($data->data_hora_registro))',
            'type' => 'raw',
            'header' => 'Data Registro',
        ),
        array(
            'name' => 'ip_registro',
            'sortable' => true,
            'header' => 'IP',
        ),
        array(
            'name' => 'ambiente_registro',
            'sortable' => true,
            'header' => 'Agente',
        ),
    ),
));
$conteudoRegistros = ob_get_clean();

ob_start();
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $ajustes->search(array(
        'pagination' => array(
            'pageSize' => 20,
        ))),
    'filter' => $ajustes,
    'ajaxUpdate' => false,
    'columns' => array(
        array(
            'name' => 'nr_ajuste',
            'sortable' => true,
            'header' => '#',
        ),
        array(
            'name' => 'id_pessoa',
            'sortable' => true,
            'header' => 'Cartão',
        ),
        array(
            'name' => 'Pessoa.nome_pessoa',
            'value' => '$data->Pessoa->nome_pessoa',
            'sortable' => true,
            'header' => 'Nome',
            'type' => 'raw',
        ),
        array(
            'name' => 'matricula',
            'sortable' => true,
            'header' => 'Matrícula',
        ),
        array(
            'name' => 'nr_vinculo',
            'sortable' => true,
            'header' => 'Vínculo',
        ),
        array(
            'name' => 'data_hora_ponto',
            'sortable' => true,
            'value' => 'date("d/m/Y H:i", strtotime($data->data_hora_ponto))',
            'type' => 'raw',
            'header' => 'Registro',
        ),
        array(
            'name' => 'entrada_saida',
            'sortable' => true,
            'header' => 'tipo',
        ),
        array(
            'name' => 'data_hora_registro',
            'sortable' => true,
            'value' => 'date("d/m/Y H:i:s", strtotime($data->data_hora_registro))',
            'type' => 'raw',
            'header' => 'Data Criação',
        ),
        array(
            'name' => 'ip_registro',
            'sortable' => true,
            'header' => 'IP',
        ),
        array(
            'name' => 'justificativa',
            'sortable' => true,
            'header' => 'justificativa',
        ),
        array(
            'name' => 'nr_justificativa',
            'sortable' => true,
            'header' => 'Just. Sel.',
        ),
        array(
            'name' => 'id_pessoa_certificacao',
            'sortable' => true,
            'header' => 'Certificador',
        ),
        array(
            'name' => 'Certificador.nome_pessoa',
            'value' => '!empty($data->Certificador) ? $data->Certificador->nome_pessoa : ""',
            'sortable' => true,
            'header' => 'Nome Certificador',
            'type' => 'raw',
        ),
        array(
            'name' => 'data_hora_certificacao',
            'sortable' => true,
            'value' => '!empty($data->data_hora_certificacao) ? date("d/m/Y H:i:s", strtotime($data->data_hora_certificacao)) : ""',
            'type' => 'raw',
            'header' => 'Data Certificação',
        ),
        array(
            'name' => 'indicador_certificado',
            'sortable' => true,
            'header' => 'Certificado',
        ),
        array(
            'name' => 'justificativa_certificacao',
            'sortable' => true,
            'header' => 'Just. Certificação',
        ),
        array(
            'name' => 'nr_ponto',
            'value' => '(trim($data->nr_ponto) != "" ? $data->nr_ponto."<br/>(".date("d/m/Y H:i", strtotime($data->Ponto->data_hora_ponto)).")" : "")',
            'type' => 'raw',
            'sortable' => true,
            'header' => 'Ponto Corrigido',
        ),
        array(
            'value' => 'CHtml::link("excluir", "javascript:excluir($data->nr_ajuste, \'ajuste\')")',
            'type' => 'raw',
            'header' => ''
        )
    ),
));
$conteudoAjustes = ob_get_clean();

ob_start();
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $abonos->search(array(
        'pagination' => array(
            'pageSize' => 20,
        ))),
    'filter' => $abonos,
    'ajaxUpdate' => false,
    'columns' => array(
        array(
            'name' => 'nr_abono',
            'sortable' => true,
            'header' => '#',
        ),
        array(
            'name' => 'id_pessoa',
            'sortable' => true,
            'header' => 'Cartão',
        ),
        array(
            'name' => 'Pessoa.nome_pessoa',
            'value' => '$data->Pessoa->nome_pessoa',
            'sortable' => true,
            'header' => 'Nome',
            'type' => 'raw',
        ),
        array(
            'name' => 'matricula',
            'sortable' => true,
            'header' => 'Matrícula',
        ),
        array(
            'name' => 'nr_vinculo',
            'sortable' => true,
            'header' => 'Vínculo',
        ),
        array(
            'name' => 'data_abono',
            'sortable' => true,
            'value' => 'date("d/m/Y", strtotime($data->data_abono))',
            'type' => 'raw',
            'header' => 'Data',
        ),
        array(
            'name' => 'periodo_abono',
            'sortable' => true,
            'value' => '(intval($data->periodo_abono/60)).":".($data->periodo_abono%60).(($data->periodo_abono%60) < 10 ? "0" : "")',
            'type' => 'raw',
            'header' => 'Tempo',
        ),
        array(
            'name' => 'id_pessoa_registro',
            'sortable' => true,
            'header' => 'Registro',
        ),
        array(
            'name' => 'data_hora_registro',
            'sortable' => true,
            'value' => 'date("d/m/Y H:i:s", strtotime($data->data_hora_registro))',
            'type' => 'raw',
            'header' => 'Data Registro',
        ),
        array(
            'name' => 'ip_registro',
            'sortable' => true,
            'header' => 'IP',
        ),
        array(
            'name' => 'justificativa',
            'sortable' => true,
            'header' => 'Agente',
        ),
        array(
            'name' => 'nr_justificativa',
            'sortable' => true,
            'header' => 'Just. Sel.',
        ),
        array(
            'name' => 'id_pessoa_certificacao',
            'sortable' => true,
            'header' => 'Certificador',
        ),
        array(
            'name' => 'Certificador.nome_pessoa',
            'value' => '!empty($data->Certificador) ? $data->Certificador->nome_pessoa : ""',
            'sortable' => true,
            'header' => 'Nome Certificador',
            'type' => 'raw',
        ),
        array(
            'name' => 'data_hora_certificacao',
            'sortable' => true,
            'value' => '!empty($data->data_hora_certificacao) ? date("d/m/Y H:i", strtotime($data->data_hora_certificacao)) : ""',
            'type' => 'raw',
            'header' => 'Data Certificação',
        ),
        array(
            'name' => 'indicador_certificado',
            'sortable' => true,
            'header' => 'Certificado',
        ),
        array(
            'name' => 'justificativa_certificacao',
            'sortable' => true,
            'header' => 'Just. Certificação',
        ),
        array(
            'value' => 'CHtml::link("excluir", "javascript:excluir($data->nr_abono, \'abono\')")',
            'type' => 'raw',
            'header' => ''
        )
    ),
));
$conteudoAbonos = ob_get_clean();

$this->widget('zii.widgets.jui.CJuiTabs', array(
    'tabs' => array(
        'Registros' => $conteudoRegistros,
        'Ajustes' => $conteudoAjustes,
        'Abonos' => $conteudoAbonos,
    ),
    'options' => array(
        'collapsible' => true,
        'selected' => (isset($_GET['Ajuste']) || isset($_GET['Ajuste_sort']) ? 1 : (isset($_GET['Abono']) || isset($_GET['Abono_sort']) ? 2 : 0)),
    ),
));

?>

<h1>Acompanhamento de registros</h1>

<label>Selecione o servidor:</label>
<input type="text" size="60" id="acServidor" /> <br/>
<br/>
<div id="acompanhamento"></div>
<input type="hidden" id="pessoaAcompanhamento" value="<?=Yii::app()->session['CodPessoaAcompanhamentoPontoGerencia']?:0?>"/>