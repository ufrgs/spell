
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

<? if (!empty($registrosDoDia)): ?>
    <fieldset>
        <legend>Registros do dia</legend>
        <table id="tabelaRegistros" class="modelo1">
        <thead>
            <tr>
                <th></th>
                <th>Intervalo</th>
                <th>Entrada</th>
                <th>Saída</th>
                <th>Tempo trabalhado</th>
            </tr>
        </thead>
        <tbody>
        <? 
        $ultimoTipoRegistro = 'S';
        $ultimoRegistro = NULL;
        $ultimaSaida = 0;
        $jornadaDiaria = 0;
        foreach ($registrosDoDia as $registro): 
            if ($registro->entrada_saida == 'E'):
                if ($ultimoTipoRegistro == 'E'): //Entrada-Entrada ?>
                        <td><?=Helper::formataHorarioAcompanhamento(NULL, $ultimoRegistro, 'S', $registro->id_pessoa)?></td>
                        <td class="centro">?</td>
                    </tr>
            <?      $ultimaSaida = 0;
                endif;  ?>
                <tr>     
                    <td></td>
                    <? if (($ultimoTipoRegistro == 'S') && (date("d/m/Y", strtotime($ultimoRegistro)) == date("d/m/Y", strtotime($registro->data_hora_ponto)))):  // se existe uma saida anterior no mesmo dia
                        $tempoIntervalo = (strtotime($registro->data_hora_ponto)-strtotime($ultimoRegistro))/60;  ?>
                        <td <?=($tempoIntervalo < 60 ? 'class="textoVermelho centro" title="Intervalo inferior a 1 hora"' : 
                                ($tempoIntervalo > 180 ? 'class="textoVermelho centro" title="Intervalo superior a 3 horas"' : 'class="centro"'))?>><?=Helper::transformaEmFormatoHora($tempoIntervalo)?></td>    
                    <? else: ?>
                        <td class="centro">?</td>
                    <? endif; ?>
                    <td><?=Helper::formataHorarioAcompanhamento($registro, $ultimoRegistro, NULL, $registro->id_pessoa)?></td>
        <?  else:
                if ($ultimoTipoRegistro == 'S'): //Saida-Saida  ?>
                    <tr>
                        <td></td>
                        <td class="centro">?</td><!-- Intervalo desconhecido -->
                        <td><?=Helper::formataHorarioAcompanhamento(NULL, $registro->data_hora_ponto, 'E', $registro->id_pessoa)?></td>
            <?  endif; ?>
                <td><?=Helper::formataHorarioAcompanhamento($registro, $ultimoRegistro, NULL, $registro->id_pessoa)?></td>    
                <? if ($ultimoTipoRegistro == 'E'):
                    $jornadaDoTurno = (strtotime($registro->data_hora_ponto)-strtotime($ultimoRegistro))/60;
                    $jornadaDiaria += $jornadaDoTurno; ?>
                    <td class="centro"><span <?=($jornadaDoTurno > 360 ? 'class="textoVermelho" title="Turno de trabalho superior a 6 horas"' : '')?>><?=Helper::transformaEmFormatoHora($jornadaDoTurno)?></span></td>    
                <? else: ?>
                    <td class="centro">?</td>
                <? endif; ?>
                </tr>
        <?      $ultimaSaida = $registro->data_hora_ponto;
            endif;
            $ultimoTipoRegistro = $registro->entrada_saida;
            $ultimoRegistro = $registro->data_hora_ponto;
        endforeach;
        if ($ultimoTipoRegistro == 'E'): ?>
                    <td></td>
                    <td class="centro">?</td>
                </tr>
        <? endif;
            Helper::mostraTotalTrabalhado($jornadaDiaria, array(), date("d/m/Y", strtotime($ultimoRegistro))) ?>
            </tbody>
        </table>
    </fieldset>
<? endif; ?>

<br/>
<fieldset class="fieldInfo">
    <legend>Certificação</legend>
    <label id="lblJustificativa">justificativa</label> <br/>
    <textarea id="justificativa" cols="60" rows="4"></textarea> <br/>
    <input type="hidden" id="nrPedido" value="<?=($tipo == 'Ajuste' ? $pedido->nr_ajuste : $pedido->nr_abono)?>"/>
    <button onclick="certificarPedido('S', '<?=strtolower($tipo)?>')">Certificar</button>
    <button class="btPerigo" onclick="certificarPedido('N', '<?=strtolower($tipo)?>')">Não Certificar</button>
</fieldset>
