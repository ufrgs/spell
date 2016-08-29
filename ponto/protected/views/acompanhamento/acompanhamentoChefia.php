<? 
$cs = Yii::app()->getClientScript();
$cs->registerCssFile(Yii::app()->baseUrl."/css/acompanhamento.css");
$cs->registerCssFile(Yii::app()->baseUrl."/css/calendario.css");
$cs->registerScriptFile(Yii::app()->baseUrl."/js/acompanhamentoChefia.js", CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->baseUrl."/js/circle-progress.js", CClientScript::POS_END);
?>
<label>Selecione o servidor:</label>
<input type="text" size="60" id="acServidor" /> <br/>
<br/>
<input type="hidden" id="pessoaAcompanhamento" value="<?=$pessoaAcompanhamento?>" />
<input type="hidden" id="mesParametro" value="<?=isset($_GET['m']) ? $_GET['m'] : date('m')?>" />
<input type="hidden" id="anoParametro" value="<?=isset($_GET['a']) ? $_GET['a'] : date('Y')?>" />
<input type="hidden" id="abaAtiva" value="<?=$abaAtiva?>" />
<div id="abasTipoAcompanhamento" <?=(empty($acompanhamento) ? 'style="display:none"' : '')?>>
    <span name="acompanhamento" class="aba clicavel <?=($abaAtiva == 'acompanhamento' ? 'ativa' : '')?>" onclick="mudaAba('acompanhamento')">Tabela</span>
    <span name="calendario" class="aba clicavel <?=($abaAtiva == 'calendario' ? 'ativa' : '')?>" onclick="mudaAba('calendario')">Calendário</span>
</div>
<div id="acompanhamento"><?=$acompanhamento?></div>