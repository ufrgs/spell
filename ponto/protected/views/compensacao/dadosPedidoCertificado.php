
<h3>Registro de Compensação nro. <?=$pedido->nr_compensacao?></h3>

<label class="esquerdaAlinhado">Servidor:</label>
<span class="dadoRotulado"><?=$pedido->Pessoa->nome_pessoa?></span> <br/>
<label class="esquerdaAlinhado">Data do pedido:</label>
<span class="dadoRotulado"><?=date("d/m/Y H:i", strtotime($pedido->data_hora_registro))?></span> <br/>
<br/>
<label class="esquerdaAlinhado">Dia:</label>
<span class="dadoRotulado"><strong><?=date("d/m/Y", strtotime($pedido->data_compensacao))?></strong></span> <br/>
<label class="esquerdaAlinhado">Horas:</label>
<span class="dadoRotulado"><strong><?=Helper::transformaEmFormatoHora($pedido->periodo_compensacao)?></strong></span> <br/>
<label class="esquerdaAlinhado">justificativa:</label>
<span class="dadoRotulado"><?=$pedido->justificativa?></span> <br/>
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
