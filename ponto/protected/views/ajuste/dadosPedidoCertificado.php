
<h3>Pedido de <?=$tipo?> nro. <?=($tipo == 'Ajuste' ? $pedido->nr_ajuste : $pedido->nr_abono)?></h3>

<label class="esquerdaAlinhado">Servidor:</label>
<span class="dadoRotulado"><?=$pedido->Pessoa->nome_pessoa?></span> <br/>
<label class="esquerdaAlinhado">Data do pedido:</label>
<span class="dadoRotulado"><?=date("d/m/Y H:i", strtotime($pedido->data_hora_registro))?></span> <br/>
<br/>
<? if ($tipo == 'Ajuste'): ?>
    <label class="esquerdaAlinhado">tipo:</label>
    <span class="dadoRotulado"><strong><?=($pedido->entrada_saida == 'E' ? 'Entrada' : 'Saída')?></strong></span> <br/>
    <label class="esquerdaAlinhado">Dia:</label>
    <span class="dadoRotulado"><strong><?=date("d/m/Y", strtotime($pedido->data_hora_ponto))?></strong></span> <br/>
    <label class="esquerdaAlinhado">Hora:</label>
    <span class="dadoRotulado"><strong><?=date("H:i", strtotime($pedido->data_hora_ponto))?></strong></span>
    <?=(!empty($pedido->Ponto) ? '(anterior '.date("H:i", strtotime($pedido->Ponto->data_hora_ponto)).')' : '')?><br/>
<? else: ?>
    <label class="esquerdaAlinhado">Dia:</label>
    <span class="dadoRotulado"><strong><?=date("d/m/Y", strtotime($pedido->data_abono))?></strong></span> <br/>
    <label class="esquerdaAlinhado">Horas:</label>
    <span class="dadoRotulado"><strong><?=Helper::transformaEmFormatoHora($pedido->periodo_abono)?></strong></span> <br/>
<? endif; ?>
<label class="esquerdaAlinhado">justificativa:</label>
<span class="dadoRotulado"><?=(trim($pedido->justificativa) != "" ? $pedido->justificativa : $pedido->JustificativaAjuste->texto_justificativa)?></span> <br/>
<label class="esquerdaAlinhado">Anexos:</label>
<span class="dadoRotulado"><?=$pedido->listaAnexos()?></span> <br/>
<br/>
<label class="esquerdaAlinhado">Certificado:</label>
<span class="dadoRotulado"><strong><?=($pedido->indicador_certificado == 'S' ? 'Sim' : 'Não')?></strong></span> <br/>
<label class="esquerdaAlinhado">Data:</label>
<span class="dadoRotulado"><strong><?=date("d/m/Y", strtotime($pedido->data_hora_certificacao))?></strong></span> <br/>
<label class="esquerdaAlinhado">Certificador:</label>
<span class="dadoRotulado"><strong><?=$pedido->Certificador->nome_pessoa?></strong></span> <br/>
<label class="esquerdaAlinhado">justificativa da certificação:</label>
<span class="dadoRotulado"><br/><?=(trim($pedido->justificativa_certificacao) != "" ? $pedido->justificativa_certificacao : "Sem justificativa")?></span> <br/>
<br/>
