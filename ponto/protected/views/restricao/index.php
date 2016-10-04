<?
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/restricao.js', CClientScript::POS_END);
?>

<fieldset class="fieldInfo">
    <legend>Restrição por Órgão</legend>
    Adicionar restrição para: <input type="text" size="60" id="acOrgaos" />
    <div id="restricoesOrgao">
        <?
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $restricoesOrgao->search(),
            'filter' => $restricoesOrgao,
            'columns' => array(
                array(
                    'name' => 'sigla_orgao',
                    'sortable' => true,
                    'value' => 'CHtml::link($data->Orgao->sigla_orgao." - ".$data->Orgao->nome_orgao, 
                        "javascript:alteraRestricao($data->nr_restricao, \'".$data->Orgao->sigla_orgao." - ".$data->Orgao->nome_orgao."\', \'$data->mascara_ip_v4\', \'$data->mascara_ip_v6\')")',
                    'header' => 'Órgão',
                    'type' => 'raw',
                ),
                array(
                    'name' => 'mascara_ip_v4',
                    'sortable' => true,
                    'header' => 'Máscara IPv4'
                ),
                array(
                    'name' => 'mascara_ip_v6',
                    'sortable' => true,
                    'header' => 'Máscara IPv6',
                ),
                array(
                    'sortable' => false,
                    'value' => 'CHtml::link("excluir", "javascript:excluiRestricao($data->nr_restricao)")',
                    'header' => '',
                    'type' => 'raw',
                ),
            ),
            'ajaxUpdate' => false,
        ));
        ?>
    </div>
</fieldset>
<br/>
<fieldset class="fieldInfo">
    <legend>Restrição por pessoa</legend>
    Adicionar restrição para: <input type="text" size="60" id="acPessoas" />
    <div id="restricoesPessoa">
        <?
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $restricoesPessoa->search(),
            'filter' => $restricoesPessoa,
            'columns' => array(
                array(
                    'name' => 'nome_pessoa',
                    'sortable' => true,
                    'value' => 'CHtml::link($data->id_pessoa." - ".$data->Pessoa->nome_pessoa, 
                        "javascript:alteraRestricao($data->nr_restricao, \'".$data->id_pessoa." - ".$data->Pessoa->nome_pessoa."\', \'$data->mascara_ip_v4\', \'$data->mascara_ip_v6\')")',
                    'header' => 'Pessoa',
                    'type' => 'raw',
                ),
                array(
                    'name' => 'mascara_ip_v4',
                    'sortable' => true,
                    'header' => 'Máscara IPv4'
                ),
                array(
                    'name' => 'mascara_ip_v6',
                    'sortable' => true,
                    'header' => 'Máscara IPv6',
                ),
                array(
                    'sortable' => false,
                    'value' => 'CHtml::link("excluir", "javascript:excluiRestricao($data->nr_restricao)")',
                    'header' => '',
                    'type' => 'raw',
                ),
            ),
            'ajaxUpdate' => false,
        ));
        ?>
    </div>
</fieldset>

<div id="modal" style="display: none">
    <form id="formRestricao">
        Restrição para <strong id="nomeRestricao"></strong> <br/>
        <br/>
        Máscara IP v4: <input type="text" size="19" maxlength="18" id="ipv4" name="ipv4"/> <br/>
        Máscara IP v6: <input type="text" size="46" maxlength="45" id="ipv6" name="ipv6"/> <br/>
        <br/>
        <input type="hidden" id="id_orgao" name="id_orgao"/>
        <input type="hidden" id="id_pessoa" name="id_pessoa"/>
        <input type="hidden" id="CodRestricao" name="CodRestricao"/>
        <input type="button" value="Salvar restrição" onclick="salvarRestricao()"/>
    </form>
</div>