<? 
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->baseUrl."/js/pedidoAjuste.js", CClientScript::POS_END);

if (count($pessoa->DadosFuncionais) == 1): ?>


<div id="usuario">
    <h2>Dados do servidor</h2>
    <div class="info">
        <strong><?=$pessoa->nome_pessoa?></strong> <br/>
        <label>Cargo:</label> <?=$pessoa->DadosFuncionais->CatFuncional->nome_categoria?> <br/>
        <label>Regime de trabalho:</label> <?=$pessoa->DadosFuncionais->regime_trabalho?>h <br/>
        <label>Lotação:</label> <?=$pessoa->DadosFuncionais->OrgaoLotacao->nome_orgao?> <br/>
        <label>Exercício:</label> <?=$pessoa->DadosFuncionais->OrgaoExercicio->nome_orgao?>
    </div>
</div>
<br/>
<fieldset class="fieldInfo comIcone">
    <legend>Que tipo de ajuste devo solicitar?</legend>
    <ul>
        <li><strong>Entrada</strong>: solicite um ajuste de <em>entrada</em> quando, por algum motivo, não tiver feito um registro de entrada em algum turno; </li>
        <li><strong>Saída</strong>: solicite um ajuste de <em>saída</em> quando, por algum motivo, não tiver feito um registro de saída em algum turno;</li>
        <li><strong>Período</strong>: solicite um ajuste de <em>período</em> quando, por algum motivo, não tiver atuado no local de exercício durante um turno, 
            <u>mas tiver atuado em atividade de trabalho na Universidade</u>, por exemplo, se tiver participado de reunião de um turno inteiro 
            fora do local de exercício;</li>
        <li><strong>Abono de carga horária</strong>: solicite um <em>abono</em> de um número de horas quando tiver participado de alguma capacitação ou evento
            ou, ainda, tiver se afastado do local de exercício para <u>alguma atividade não relacionada ao trabalho na Universidade</u>, 
            por exemplo, se comparecer a uma consulta médica.</li>
    </ul>
    <br/>
    <strong>Importante:</strong> para <em>alterar</em> o horário de um registro de entrada, clique sobre o horário a ser alterado na tela de 
    <a href="<?=Yii::app()->createUrl("acompanhamento/index")?>">acompanhamento</a>.
</fieldset>
<br/>
<div>
    <div>
        <button onclick="novoAjuste()">Solicitar inclusão de ajuste</button>
    </div>
    <br/>
    <h2>Meus pedidos de ajuste</h2>
    <?  
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $ajustes,
            'columns' => array(
                array(
                    'name' => 'data_hora_registro',
                    'sortable' => true,
                    'value' => 'date("d/m/Y H:i", strtotime($data->data_hora_registro))',
                    'header' => 'Data do pedido',
                    'type' => 'raw',
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
                    'name' => 'just',
                    'value' => '(trim($data->justificativa) != "" ? $data->justificativa : $data->JustificativaAjuste->texto_justificativa).(trim($data->justificativa_certificacao) != "" ? "<br/><br/><strong>justificativa da ".(trim($data->indicador_certificado) == "N" ? "não" : "")." certificação:</strong><br/>".$data->justificativa_certificacao : "")',
                    'type' => 'raw',
                    'header' => 'justificativa',
                ),
                array(
                    'value' => '$data->listaAnexos()',
                    'header' => 'Anexos',
                    'type' => 'raw',
                ),
                array(
                    'value' => '(trim($data->indicador_certificado) != "" ? (trim($data->indicador_certificado) == "S" ? "<span class=\"textoVerde\">Certificado</span>" : "<span class=\"textoVermelho\">Não certificado</span>")." por<br/>".$data->Certificador->nome_pessoa : "Pendente")',
                    'header' => 'Status',
                    'type' => 'raw',
                ),
                array(
                    'value' => '(trim($data->indicador_certificado) == "" ? CHtml::link("excluir", "javascript:excluir($data->nr_ajuste, \'ajuste\')") : "")',
                    'header' => '',
                    'type' => 'raw',
                ),
            ),
            'ajaxUpdate' => false,
        ));
        ?>
    <h2>Meus pedidos de abono</h2>
    <?  
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $abonos,
            'columns' => array(
                array(
                    'name' => 'data_hora_registro',
                    'sortable' => true,
                    'value' => 'date("d/m/Y H:i", strtotime($data->data_hora_registro))',
                    'header' => 'Data do pedido',
                    'type' => 'raw',
                ),
                array(
                    'name' => 'data_abono',
                    'sortable' => true,
                    'value' => 'date("d/m/Y", strtotime($data->data_abono))',
                    'header' => 'Dia'
                ),
                array(
                    'name' => 'periodo_abono',
                    'sortable' => true,
                    'value' => 'Helper::transformaEmFormatoHora($data->periodo_abono)',
                    'header' => 'Horas'
                ),
                array(
                    'value' => '(trim($data->justificativa) != "" ? $data->justificativa : $data->JustificativaAjuste->texto_justificativa).(trim($data->justificativa_certificacao) != "" ? "<br/><br/><strong>justificativa da ".(trim($data->indicador_certificado) == "N" ? "não" : "")." certificação:</strong><br/>".$data->justificativa_certificacao : "")',
                    'type' => 'raw',
                    'header' => 'justificativa',
                ),
                array(
                    'value' => '$data->listaAnexos()',
                    'header' => 'Anexos',
                    'type' => 'raw',
                ),
                array(
                    'value' => '(trim($data->indicador_certificado) != "" ? (trim($data->indicador_certificado) == "S" ? "<span class=\"textoVerde\">Certificado</span>" : "<span class=\"textoVermelho\">Não certificado</span>")." por<br/>".$data->Certificador->nome_pessoa : "Pendente")',
                    'header' => 'Status',
                    'type' => 'raw',
                ),
                array(
                    'value' => '(trim($data->indicador_certificado) == "" ? CHtml::link("excluir", "javascript:excluir($data->nr_abono, \'abono\')") : "")',
                    'header' => '',
                    'type' => 'raw',
                ),
            ),
            'ajaxUpdate' => false,
        ));
        ?>
</div>

<div id="divPedido">
    <progress style="display:none"></progress>
    <fieldset id="mensagens" class="fieldAlerta" style="display:none"></fieldset>
    <form enctype="multipart/form-data" id="formPedido">
        <input type="hidden" id="nrPonto" name="nrPonto" value="<?=(isset($_GET['n']) ? $_GET['n'] : '')?>"/>
        <input type="hidden" id="tipoEDataAjuste" value="<?=(isset($_GET['td']) ? $_GET['td'] : '')?>"/>
        <input type="hidden" id="nrVinculo" name="nrVinculo" value="<?=$pessoa->DadosFuncionais->nr_vinculo?>"/>
        <label class="esquerdaAlinhado maior">Tipo:</label>
        <input type="radio" name="tipo" id="tipoE" value="E" <?=(!empty($registroAjustar) && $registroAjustar->entrada_saida == 'E' ? 'checked="checked"' : '')?>/> <label for="tipoE">Entrada</label> &nbsp; &nbsp;
        <input type="radio" name="tipo" id="tipoS" value="S" <?=(!empty($registroAjustar) && $registroAjustar->entrada_saida == 'S' ? 'checked="checked"' : '')?>/> <label for="tipoS">Saída</label> &nbsp; &nbsp;
        <input type="radio" name="tipo" id="tipoP" value="P" /> <label for="tipoP">Período</label> &nbsp; &nbsp;
        <input type="radio" name="tipo" id="tipoA" value="A" /> <label for="tipoA">Abono de carga horária</label> <br/>
        <br/>
        <label class="esquerdaAlinhado maior" style="clear:left" for="data">Data:</label>
        <input type="text" size="11" name="data" id="data" value="<?=(!empty($registroAjustar) ? date("d/m/Y", strtotime($registroAjustar->data_hora_ponto)) : '')?>"/> <br/>
        <label class="esquerdaAlinhado maior" for="hora" id="lblHora">Hora:</label>
        <input type="text" size="5" name="hora" id="hora" value="<?=(!empty($registroAjustar) ? date("H:i", strtotime($registroAjustar->data_hora_ponto)) : '')?>"/> <br/>
        <div id="divHoraSaida" style="display:none">
            <label class="esquerdaAlinhado maior" for="horaSaida">Hora de Saída:</label>
            <input type="text" size="5" name="horaSaida" id="horaSaida" /> <br/>
        </div>
        <label class="esquerdaAlinhado maior" for="justificativa">Justificativa:</label>
        <select name="justificativa" id="justificativa" onchange="verificaJustificativa(this.value)">
            <option value=""> -------------- </option>
            <? foreach ($justificativas as $justificativa): ?>
                <option class="opt_<?=$justificativa->tipo_justificativa?>" value="<?=$justificativa->nr_justificativa?>"><?=$justificativa->texto_justificativa?> </option>
            <? endforeach; ?>
            <option value="o">Outra </option>
        </select><br/>
        <label class="esquerdaAlinhado maior"></label>
        <div style="display:none" id="divOutraJustificativa">
            <textarea id="outraJustificativa" name="outraJustificativa" rows="3" cols="60"></textarea> <br/>
        </div>
        <label style="clear:left" class="esquerdaAlinhado maior" for="anexos">Anexos:</label>
        <input type="file" multiple="multiple" id="anexos" name="anexos[]"/> <br/>
        <label class="esquerdaAlinhado maior"></label>
        <div id="botaoEnviar">
            <input type="button" value="Enviar" onclick="enviaSolicitacao()"/>
        </div>
        <input type="hidden" id="registroAnterior" value="<?=(!empty($registroAjustar) ? $registroAjustar->entrada_saida.date("d/m/Y", strtotime($registroAjustar->data_hora_ponto)).date("H:i", strtotime($registroAjustar->data_hora_ponto)) : '')?>"/>
    </form>
</div>

<div id="fundoModal" style="display:none" onclick="fechaModal()"></div>
<div id="janelaModal" style="display:none"><span class="tituloCard"></span><span class="conteudo"></span><span class="botoes"></span></div>

<? else: // selecionar vinculo ?>

    <label for="selVinculo">Selecione um vínculo:</label> <br/>
    <select id="selVinculo">
        <option value=""> -------------------- </option>
        <? foreach ($pessoa->DadosFuncionais as $vinculo): ?>
            <option value="<?=$vinculo->nr_vinculo?>"><?=$vinculo->CatFuncional->nome_categoria?> </option>
        <? endforeach; ?>
    </select>

<? endif; 