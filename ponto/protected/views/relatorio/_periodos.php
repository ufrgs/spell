<? if (!empty($periodos)): ?>
    <label class="esquerdaAlinhado maior" for="periodo">Selecione o período</label>
    <select id="periodo" name="periodo">
    <? foreach ($periodos as $periodo): ?>
        <option value="<?=$periodo['mes']?>/<?=$periodo['ano']?>"><?=$periodo['mes']?>/<?=$periodo['ano']?></option>
    <? endforeach; ?>
    </select> <br/>

    <button class="semRotuloEsquerda maior">Exibir</button>
<? else: ?>
    <fieldset class="fieldInfo">Nenhum período disponível para esse órgão.</fieldset>
<? endif; ?>
