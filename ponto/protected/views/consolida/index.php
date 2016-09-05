<?php
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->baseUrl."/js/consolida.js", CClientScript::POS_END);
?>
<div id="mensagem"></div>

<fieldset>
    <legend>Consolida carga horária</legend>
    <label for="ano" class="esquerdaAlinhado">Ano</label>
    <select id="ano">
        <? for ($i = date("Y"); $i >= 2016; $i--):
            print '<option value="'.$i.'">'.$i.'</option>';
        endfor; ?>
    </select> &nbsp;
    <label for="mes">Mês</label>
    <select id="mes">
        <? for ($i = 12; $i >= 1; $i--):
            print '<option value="'.$i.'" '.($i == (date("m") == 1 ? 12 : date("m")-1) ? 'selected="selected"' : '').'>'.$i.'</option>';
        endfor; ?>
    </select> <br/>
    <br/>
    <button class="semRotuloEsquerda" onclick="servidores()">Todos servidores</button> <br/>
    <br/>
    
    <fieldset class="expansivel">
        <legend>De um lote de servidores</legend>
        <div class="conteudo">
            <label for="servidores">matricula;nr_vinculo (um conjunto por linha)</label> <br/>
            <textarea id="servidores" cols="50" rows="5"></textarea> <br/>
            <button onclick="lote()">Processar lote</button> <br/>
        </div>
    </fieldset>
</fieldset>
