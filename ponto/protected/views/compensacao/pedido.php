<? 
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->baseUrl."/js/compensacao.js", CClientScript::POS_END);

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
    <legend>O que é um registro de compensação?</legend>
    Um registro de compensação é uma forma de aproveitar as horas excedentes do mês anterior para abonar horas do mês atual. <br/>
    Para fazer isso, indique o dia em que você irá aproveitar as horas excedentes e a quantidade de horas a ser utilizada e aguarde a certificação do pedido. <br/>
    Para ter certeza que a compensação será certificada, é recomendado que haja um acerto prévio com a chefia.
</fieldset>
<br/>
<? if ($saldoMesAntesAnterior < 0): ?>
    Saldo de horas do mês anterior: <?=Helper::transformaEmFormatoHora($saldoMesAnterior)?> <br/>
    Saldo de horas de dois mês atrás: <?=Helper::transformaEmFormatoHora($saldoMesAntesAnterior)?> <br/>
<? endif; ?>
<strong>Saldo de horas disponível para compensação: <?=Helper::transformaEmFormatoHora($saldoDisponivelCompensacao)?></strong> <br/>
<strong>Horas compensadas até o momento: <?=Helper::transformaEmFormatoHora($compensacaoAteHoje)?></strong> (<?=Helper::transformaEmFormatoHora($saldoDisponivelCompensacao - $compensacaoAteHoje)?> restante) <br/>
<input type="hidden" id="saldoMinutos" value="<?=($saldoDisponivelCompensacao-$compensacaoAteHoje)?>"/>
<input type="hidden" id="saldoFormatado" value="<?=Helper::transformaEmFormatoHora($saldoDisponivelCompensacao-$compensacaoAteHoje)?>"/>
<br/>
<div>
    <? if (($saldoDisponivelCompensacao - $compensacaoAteHoje) > 0): ?>
    <div>
        <button onclick="novoRegistro()">Solicitar registro de compensação</button>
    </div>
    <? endif; ?>
    <br/>
    <h2>Registros de compensação efetuados</h2>
    <?  
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $compensacoes,
            'columns' => array(
                array(
                    'name' => 'data_hora_registro',
                    'sortable' => true,
                    'value' => 'date("d/m/Y H:i", strtotime($data->data_hora_registro))',
                    'header' => 'Data do pedido',
                    'type' => 'raw',
                ),
                array(
                    'name' => 'data_compensacao',
                    'sortable' => true,
                    'value' => 'date("d/m/Y", strtotime($data->data_compensacao))',
                    'header' => 'Dia'
                ),
                array(
                    'name' => 'periodo_compensacao',
                    'sortable' => true,
                    'value' => 'Helper::transformaEmFormatoHora($data->periodo_compensacao)',
                    'header' => 'Horas'
                ),
                array(
                    'name' => 'justificativa',
                    'header' => 'justificativa',
                    'value' => '$data->justificativa.(trim($data->justificativa_certificacao) != "" ? "<br/><br/><strong>justificativa da ".(trim($data->indicador_certificado) == "N" ? "não" : "")." certificação:</strong><br/>".$data->justificativa_certificacao : "")',
                    'type' => 'raw',
                ),
                array(
                    'value' => '(trim($data->indicador_certificado) != "" ? (trim($data->indicador_certificado) == "S" ? "<span class=\"textoVerde\">Certificado</span>" : "<span class=\"textoVermelho\">Não indicador_certificado</span>")." por<br/>".$data->Certificador->nome_pessoa : "Pendente")',
                    'header' => 'Status',
                    'type' => 'raw',
                ),
                array(
                    'value' => '(trim($data->indicador_certificado) == "" ? CHtml::link("excluir", "javascript:excluir($data->nr_compensacao)") : "")',
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
    <form id="formPedido">
        <input type="hidden" id="nrVinculo" name="nrVinculo" value="<?=$pessoa->DadosFuncionais->nr_vinculo?>"/>
        <label class="esquerdaAlinhado maior" style="clear:left" for="data">Data:</label>
        <input type="text" size="11" name="data" id="data" /> <br/>
        <label class="esquerdaAlinhado maior" for="hora" id="lblHora">Horas:</label>
        <input type="text" size="5" name="hora" id="hora" /> <br/>
        <label class="esquerdaAlinhado maior" for="justificativa">justificativa:</label>
        <textarea id="justificativa" name="justificativa" rows="3" cols="60"></textarea> <br/>
        <label class="esquerdaAlinhado maior"></label>
        <div id="botaoEnviar">
            <input type="button" value="Enviar" onclick="enviaSolicitacao()"/>
        </div>
    </form>
</div>

<div id="fundoModal" style="display:none" onclick="fechaModal()"></div>
<div id="janelaModal" style="display:none"><span class="tituloCard"></span><span class="conteudo"></span><span class="botoes"></span></div>

<? else: // selecionar vínculo ?>

    <label for="selVinculo">Selecione um vínculo:</label> <br/>
    <select id="selVinculo">
        <option value=""> -------------------- </option>
        <? foreach ($pessoa->DadosFuncionais as $vinculo): ?>
            <option value="<?=$vinculo->nr_vinculo?>"><?=$vinculo->CatFuncional->nome_categoria?> </option>
        <? endforeach; ?>
    </select>

<? endif; 