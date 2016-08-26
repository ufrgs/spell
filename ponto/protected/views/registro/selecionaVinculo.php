
<label for="selVinculo">Selecione um vínculo:</label> <br/>
<select id="selVinculo">
    <option value=""> -------------------- </option>
    <? foreach ($pessoa->DadosFuncionais as $vinculo): ?>
        <option value="<?=$vinculo->nr_vinculo?>"><?=$vinculo->CatFuncional->nome_categoria?> </option>
    <? endforeach; ?>
</select>