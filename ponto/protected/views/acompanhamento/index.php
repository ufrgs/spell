<style type="text/css" media="print">
thead {display: table-header-group;}
.escondeNaImpressao, .escondeNaImpressao * {
    display: none !important;
}
</style>
<? 
if (!$viaAjax) {
    $cs = Yii::app()->getClientScript();
    $cs->registerCssFile(Yii::app()->baseUrl."/css/acompanhamento.css");
    $cs->registerScriptFile(Yii::app()->baseUrl."/js/acompanhamento.js", CClientScript::POS_END);
    $cs->registerScriptFile(Yii::app()->baseUrl."/js/circle-progress.js", CClientScript::POS_END);
}

if (count($pessoa->DadosFuncionais) == 1): ?>

<div class="coluna50" id="usuario">
    <h2>Dados do servidor</h2>
    <div class="info">
        <strong><?=$pessoa->nome_pessoa?></strong> <br/>
        <label>Cargo:</label> <?=$pessoa->DadosFuncionais->CatFuncional->nome_categoria?> <br/>
        <label>Regime de trabalho:</label> <?=$pessoa->DadosFuncionais->regime_trabalho?>h <br/>
        <label>Lotação:</label> <?=$pessoa->DadosFuncionais->OrgaoLotacao->nome_orgao?> <br/>
        <label>Exercício:</label> <?=$pessoa->DadosFuncionais->OrgaoExercicio->nome_orgao?>
    </div>
    <input type="hidden" id="nrVinculo" value="<?=$pessoa->DadosFuncionais->nr_vinculo?>"/>
    <br/>
    <button class="escondeNaImpressao" onclick="window.print()">Imprimir</button>
</div>

<div class="coluna50 escondeNaImpressao" id="divProgressos">
<? if ($mesSelecionado == date("m")): ?>
        <h2>Acompanhamento da jornada de <?=date("m/Y")?></h2>
        <div class="progresso">
            <label>Hoje</label>
            <div class="circulo" id="progressoDiario" value="<?=$jornadaDiaria/60/$cargaHorariaDiaria?>">
                <span><?=Helper::transformaEmFormatoHora($jornadaDiaria)."<br/>/ ".$cargaHorariaDiaria."h"?></span>
            </div>
        </div>
        <div class="progresso">
            <label>Semanal</label>
            <div class="circulo" id="progressoSemanal" value="<?=$jornadaSemanal/60/$cargaHorariaSemanal?>">
                <span><?=Helper::transformaEmFormatoHora($jornadaSemanal)."<br/>/ ".$cargaHorariaSemanal."h"?></span>
            </div>
        </div>
        <div class="progresso">
            <label>Mensal até hoje</label>
            <div class="circulo" id="progressoMensalAteHoje" value="<?=($jornadaMensal+$horasAfastamentoAteHoje)/60/$cargaHorariaMensalAteHoje?>">
                <span><?=Helper::transformaEmFormatoHora($jornadaMensal+$horasAfastamentoAteHoje)."<br/>/ ".$cargaHorariaMensalAteHoje."h"?></span>
            </div>
        </div>
        <div class="progresso">
            <label>Mensal</label>
            <div class="circulo" id="progressoMensal" value="<?=($jornadaMensal+$horasAfastamento)/60/$cargaHorariaMensal?>">
                <span><?=Helper::transformaEmFormatoHora($jornadaMensal+$horasAfastamento)."<br/>/ ".$cargaHorariaMensal."h"?></span>
            </div>
        </div>
<? endif; ?>
</div>

<div id="divRegistros">

    <h2>Registros</h2>
   
    <label for="ano">Ano:</label>
    <select id="ano">
        <? foreach ($anos as $ano): ?>
            <option value="<?=$ano?>" <?=($ano == $anoSelecionado ? 'selected="selected"' : '')?>><?=$ano?></option>
        <? endforeach; ?>
    </select> &nbsp;
    <label for="mes">Mês:</label>
    <select id="mes">
        <? foreach ($meses as $mes): ?>
            <option value="<?=$mes?>" <?=($mes == $mesSelecionado ? 'selected="selected"' : '')?>><?=$mes?></option>
        <? endforeach; ?>
    </select>
    <? if (!empty($registrosDia)): ?>
        <table id="tabelaRegistros" class="modelo1">
            <thead>
                <tr>
                    <th>Dia</th>
                    <th>Intervalo</th>
                    <th>Entrada</th>
                    <th>Saída</th>
                    <th>Tempo trabalhado</th>
                </tr>
            </thead>
            <tbody>
            <? for ($i = 1; $i <= $diaUltimoRegistro; $i++):
                // se nao existe registro, abono ou compensacao no dia, nao mostra 
                if (empty($registrosDia[$i]['Registros']) && (@$registrosDia[$i]['MinutosAbono'] == 0) && (@$registrosDia[$i]['MinutosCompensacao'] == 0)):
                    continue;    
                endif;
                ?>
                    <tr>
                        <td><strong><?=str_pad($i, 2, "0", STR_PAD_LEFT).'/'.str_pad($mesSelecionado, 2, "0", STR_PAD_LEFT)?></strong></td>
                        <? if (!empty($registrosDia[$i]['Registros'])):
                            $primeiro = true; 
                            foreach ($registrosDia[$i]['Registros'] as $registro): ?>
                                <? if ($registro['tipo'] == 'E'):
                                    // se nao e o primero registro do dia, abre nova linha na tabela
                                    if (!$primeiro): ?>
                                        <tr>
                                            <td></td>
                                    <? endif;
                                    if (is_numeric($registro['tempoIntervalo'])): ?>
                                        <td class="centro <?=($registro['tempoIntervalo'] < 60 ? 'textoVermelho" title="Intervalo inferior a 1 hora' : 
                                                ($registro['tempoIntervalo'] > 180 ? 'textoVermelho" title="Intervalo superior a 3 horas' : ''))?>">
                                            <?=Helper::transformaEmFormatoHora($registro['tempoIntervalo'])?>
                                        </td>
                                    <? else: ?>
                                        <td class="centro"><?=$registro['tempoIntervalo']?></td>
                                    <? endif;
                                endif; ?>
                                <td>
                                    <? if (($registro['registro'] === '') || ($registro['registro'] === '-')):
                                        print $registro['registro'];
                                    else:
                                        print Helper::formataHorarioAcompanhamento($registro['registro'], $registro['dataAuxiliar'], $registro['tipo'], $registro['id_pessoa']);
                                    endif; ?>
                                </td>
                                <? if ($registro['tipo'] == 'S'):
                                    if (is_numeric($registro['tempoTrabalhado'])): ?>
                                        <td class="centro <?=($registro['tempoTrabalhado'] > 360 ? 'textoVermelho" title="Turno de trabalho superior a 6 horas' : '')?>">
                                            <?=Helper::transformaEmFormatoHora($registro['tempoTrabalhado'])?>
                                        </td>
                                    <? else: ?>
                                        <td class="centro"><?=$registro['tempoTrabalhado']?></td>
                                    <? endif;
                                endif;
                                // se nao e o primero registro do dia, fecha nova linha na tabela
                                if (($registro['tipo'] == 'S') && !$primeiro): ?>
                                    </tr>
                                <? endif;
                                $primeiro = false;
                            endforeach;
                        else: ?>
                            <td colspan="4"></td>
                        <? endif; ?>
                    </tr>
                    <tr>
                        <td class="alinhaDireita" colspan="4">
                            <? if ($registrosDia[$i]['MinutosRegistro'] > 0): ?>
                                Total trabalhado:<br/>
                            <? endif; ?>
                            <? if ($registrosDia[$i]['MinutosAbono'] > 0): ?>
                                Horas abonadas:<br/>
                            <? endif; ?>
                            <? if ($registrosDia[$i]['MinutosCompensacao'] > 0): ?>
                                Horas compensadas:<br/>
                            <? endif; ?>
                        </td>
                        <td class="alinhaDireita">
                            <? if ($registrosDia[$i]['MinutosRegistro'] > 0): ?>
                                <span <?=($registrosDia[$i]['MinutosRegistro'] > 600 ? 'class="textoVermelho" title="Jornada diária superior a 10 horas"' : '')?>><?=Helper::transformaEmFormatoHora($registrosDia[$i]['MinutosRegistro'])?></span><br/>
                            <? endif; ?>
                            <? if ($registrosDia[$i]['MinutosAbono'] > 0): ?>
                                <span <?=($registrosDia[$i]['AbonoPendente'] ? 'class="textoAmarelo" title="Pedido de abono pendente de certificação"' : '')?>><?=Helper::transformaEmFormatoHora($registrosDia[$i]['MinutosAbono'])?></span><br/>
                            <? endif; ?>
                            <? if ($registrosDia[$i]['MinutosCompensacao'] > 0): ?>
                                <span <?=($registrosDia[$i]['CompensacaoPendente'] ? 'class="textoAmarelo" title="Pedido de compensação pendente de certificação"' : '')?>><?=Helper::transformaEmFormatoHora($registrosDia[$i]['MinutosCompensacao'])?></span><br/>
                            <? endif; ?>
                        </td>
                    </tr>
            <? endfor; ?>
                <tr>
                    <td colspan="7">&nbsp;</td>
                </tr>
                <? if ($saldoMesAnterior < 0): ?>
                    <tr>
                        <td class="alinhaDireita" colspan="4">
                            Saldo de horas do mês antes do anterior:
                            <?=($compensacaoMesAnterior > 0 ? '<br/>- Compensação do saldo no mês anterior:<br/>Saldo final do mês antes do anterior:' : '')?>
                        </td>
                        <td class="alinhaDireita">
                            <span class="<?=($saldoMesAntesAnterior < 0 ? 'textoVermelho' : 'textoVerde')?>"><?=Helper::transformaEmFormatoHora($saldoMesAntesAnterior)?></span>
                            <?=($compensacaoMesAnterior > 0 ? '<br/>'.Helper::transformaEmFormatoHora($compensacaoMesAnterior).Helper::transformaEmFormatoHoramatoHora($saldoMesAntesAnterior-$compensacaoMesAnterior) : '')?>
                        </td>
                    </tr>
                <? endif; ?>
                <tr>
                    <td class="alinhaDireita" colspan="4">
                        Saldo de horas do mês anterior:
                    </td>
                    <td class="alinhaDireita">
                        <span class="<?=($saldoMesAnterior < 0 ? 'textoVermelho' : 'textoVerde')?>"><?=Helper::transformaEmFormatoHora($saldoMesAnterior)?></span>
                    </td>
                </tr>
                <tr>
                    <td class="alinhaDireita" colspan="4">
                        Total trabalhado no mês: 
                    </td>
                    <td class="alinhaDireita">
                        <span><?=Helper::transformaEmFormatoHora($totalRegistros)?></span>
                    </td>
                </tr>
                <? if (($totalAbono > 0) || ($totalCompensacao > 0) || ($horasAfastamento > 0)):
                    if ($totalAbono > 0): ?>
                    <tr>    
                        <td class="alinhaDireita" colspan="4">
                            Total de horas abonadas: 
                        </td>
                        <td class="alinhaDireita">
                            <span><?=Helper::transformaEmFormatoHora($totalAbono)?></span> 
                        </td>
                    </tr>
                    <? endif; 
                    if ($totalCompensacao > 0): ?>
                    <tr>    
                        <td class="alinhaDireita" colspan="4">
                            Total de horas compensadas: 
                        </td>
                        <td class="alinhaDireita">
                            <span><?=Helper::transformaEmFormatoHora($totalCompensacao)?></span> 
                        </td>
                    </tr>
                    <? endif; 
                    if ($horasAfastamento > 0): ?>
                    <tr>    
                        <td class="alinhaDireita" colspan="4">
                            Total de horas em afastamentos&sup1;: 
                        </td>
                        <td class="alinhaDireita">
                            <span><?=Helper::transformaEmFormatoHora($horasAfastamento)?></span> 
                        </td>
                    </tr>
                    <? endif; ?>
                    <tr>    
                        <td class="alinhaDireita" colspan="4">
                            <strong>Total geral do mês: </strong>
                        </td>
                        <td class="alinhaDireita">
                            <strong><span><?=Helper::transformaEmFormatoHora($totalRegistros+$totalAbono+$totalCompensacao+$horasAfastamento)?></span></strong> 
                        </td>
                    </tr>
                <? endif; ?>
                <tr>    
                    <td class="alinhaDireita" colspan="4">
                        Carga horária prevista no mês: 
                    </td>
                    <td class="alinhaDireita">
                        <span><?=Helper::transformaEmFormatoHora($cargaHorariaMesSelecionado*60)?></span> 
                    </td>
                </tr>
                <? if ((date('Y') > $anoSelecionado) || (date('m') > $mesSelecionado)): // so mostra o saldo final se for de um mes ja fechado ?>
                    <tr>    
                        <td class="alinhaDireita" colspan="4">
                            Saldo do mês: 
                        </td>
                        <td class="alinhaDireita">
                            <?
                            $saldo = ($totalRegistros+$totalAbono+$totalCompensacao+$horasAfastamento) - ($cargaHorariaMesSelecionado*60);
                            if (($saldo > 0) && ($totalCompensacao > 0)) {
                                if ($saldo > $totalCompensacao) {
                                    // saldo de horas excedente é maior do que a compensação 
                                    $saldo -= $totalCompensacao;
                                }
                                else {
                                    // compensação é considerada só até zerar o saldo
                                    $saldo = 0;
                                }
                            }
                            ?>
                            <strong><span class="<?=($saldo < 0 ? 'textoVermelho' : 'textoVerde')?>"><?=Helper::transformaEmFormatoHora($saldo)?></span></strong>
                        </td>
                    </tr>       
                    <? if (($saldo < 0) || ($saldoMesAnterior < 0)): ?>
                        <tr>    
                            <td class="alinhaDireita" colspan="4">
                                Saldo dos dois últimos meses: 
                            </td>
                            <td class="alinhaDireita">
                                <?
                                $saldo = $saldo + $saldoMesAnterior;
                                ?>
                                <strong><span class="<?=($saldo < 0 ? 'textoVermelho' : 'textoVerde')?>"><?=Helper::transformaEmFormatoHora($saldo)?></span></strong>
                            </td>
                        </tr>       
                    <? endif; ?>
                <? endif; ?>
            </tbody>
        </table>
    <? else: ?>
        Não existem registros.
    <? endif; ?>
    
    <h2>&sup1; Afastamentos registrados (férias, licenças, etc.)</h2>
    <? if (!empty($afastamentos)): ?>
        <table id="tabelaRegistros" class="modelo1">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Data de Início</th>
                    <th>Data de Fim</th>
                    <th>Nro. Dias</th>
                </tr>
            </thead>
            <tbody>
            <? foreach ($afastamentos as $afastamento):  ?>
                <tr>
                    <td><?=$afastamento['nome_frequencia']?></td>
                    <td><?=$afastamento['data_inicio']?></td>
                    <td><?=$afastamento['data_fim']?></td>
                    <td><?=$afastamento['nr_dias']?></td>
                </tr>
            <? endforeach; ?>
            </tbody>
        </table>
    <? else: ?>
        Não existem afastamentos registrados.
    <? endif; ?>
</div>

<? else: // selecionar vinculo ?>

    <label for="selVinculo">Selecione um vínculo:</label> <br/>
    <select id="selVinculo">
        <option value=""> -------------------- </option>
        <? foreach ($pessoa->DadosFuncionais as $vinculo): ?>
            <option value="<?=$vinculo->nr_vinculo?>"><?=$vinculo->CatFuncional->nome_categoria?> </option>
        <? endforeach; ?>
    </select>

<? endif; 