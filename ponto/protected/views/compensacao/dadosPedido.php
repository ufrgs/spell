
<h3>Registro de Compensacao nro. <?=$pedido->nr_compensacao?></h3>

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
    <input type="hidden" id="nrPedido" value="<?=$pedido->nr_compensacao?>"/>
    <button onclick="certificarPedido('S')">Certificar</button>
    <button class="btPerigo" onclick="certificarPedido('N')">Não Certificar</button>
</fieldset>
