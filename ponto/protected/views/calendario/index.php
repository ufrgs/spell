<style type="text/css" media="print">
thead {display: table-header-group;}
.escondeNaImpressao, .escondeNaImpressao * {
    display: none !important;
}
</style>
<? 
if (!$viaAjax) {
    $cs = Yii::app()->getClientScript();
    $cs->registerCssFile(Yii::app()->baseUrl."/css/calendario.css");
    $cs->registerScriptFile(Yii::app()->baseUrl."/js/calendario.js", CClientScript::POS_END);
}

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
    <input type="hidden" id="nrVinculo" value="<?=$pessoa->DadosFuncionais->nr_vinculo?>"/>
    <br/>
    <button class="escondeNaImpressao" onclick="window.print()">Imprimir</button>
</div>

<div id="divCalendario">

    <h2>Calendário</h2>
   
    <label for="ano">ano:</label>
    <select id="ano">
        <? foreach ($anos as $ano): ?>
            <option value="<?=$ano?>" <?=($ano == $anoSelecionado ? 'selected="selected"' : '')?>><?=$ano?></option>
        <? endforeach; ?>
    </select> &nbsp;
    <label for="mes">mes:</label>
    <select id="mes">
        <? foreach ($meses as $mes): ?>
            <option value="<?=$mes?>" <?=($mes == $mesSelecionado ? 'selected="selected"' : '')?>><?=$mes?></option>
        <? endforeach; ?>
    </select> <br/>
    <br/>
    <table class="calendario">
        <thead>
            <tr>
                <th class="domingo">Dom</th>
                <th>Seg</th>
                <th>Ter</th>
                <th>Qua</th>
                <th>Qui</th>
                <th>Sex</th>
                <th>Sáb</th>
            </tr>
        </thead>
        <tbody>
        <? if ($calendarioMesSelecionado[1]['DiaSemana'] != 1): // se o mes nao comeca no domingo ?>
            <tr>
                <td class="vazia" colspan="<?=$calendarioMesSelecionado[1]['DiaSemana']-1?>"></td>
        <? endif; ?>
        <? foreach ($calendarioMesSelecionado as $dia => $dados):
            if (($dia != 1) && ($dados['DiaSemana'] == 1)): ?>
                </tr><tr>
            <? endif;  ?>
            <td class="dia<?=$dados['DiaSemana']?> <?=($dados['Feriado'] ? 'feriado' : '')?> <?=($dados['EmAfastamento'] ? 'afastado' : '')?>">
                <span class="numeroDia"><?=$dia?></span>
                <? if ($dados['EmAfastamento']): ?>
                    <span class="afastamentos"><?=$dados['Afastamentos']?></span>
                <? else: ?>
                    <? if ($dados['MinutosRegistro'] > 0): ?>
                        <label>Trabalhado</label>
                        <span class="dado"><?=Helper::transformaEmFormatoHora($dados['MinutosRegistro'])?></span> 
                    <? endif; ?>
                    <? if ($dados['MinutosAbono'] > 0): ?>
                        <label>Abonos</label>
                        <span class="dado <?=($dados['AbonoPendente'] ? 'textoAmarelo" title="Pedido de abono pendente de certificação' : '')?>"><?=Helper::transformaEmFormatoHora($dados['MinutosAbono'])?></span> 
                    <? endif; ?>
                    <? if ($dados['MinutosCompensacao'] > 0): ?>
                        <label>Compensações</label>
                        <span class="dado <?=($dados['CompensacaoPendente'] ? 'textoAmarelo" title="Pedido de compensação pendente de certificação' : '')?>"><?=Helper::transformaEmFormatoHora($dados['MinutosCompensacao'])?></span> 
                    <? endif; ?>
                <? endif; ?>
            </td>
        <? endforeach; ?>
            </tr>
        </tbody>
    </table> <br/>
    <br/>
    <table class="modelo1">
        <tbody>
            <? if ($saldoMesAnterior < 0): ?>
                <tr>
                    <td class="alinhaDireita" colspan="4">
                        Saldo de horas do mês antes do anterior:
                        <?=($compensacaoMesAnterior > 0 ? '<br/>- Compensação do saldo no mês anterior:<br/>Saldo final do mês antes do anterior:' : '')?>
                    </td>
                    <td class="alinhaDireita">
                        <span class="<?=($saldoMesAntesAnterior < 0 ? 'textoVermelho' : 'textoVerde')?>"><?=Helper::transformaEmFormatoHora($saldoMesAntesAnterior)?></span>
                        <?=($compensacaoMesAnterior > 0 ? '<br/>'.Helper::transformaEmFormatoHora($compensacaoMesAnterior).'<br/>'.Helper::transformaEmFormatoHora($saldoMesAntesAnterior-$compensacaoMesAnterior) : '')?>
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