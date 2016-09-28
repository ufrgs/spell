<?if ($empty===true):?>
<div>
    <p><strong>Não há horário de expediente definido para o seu órgão de exercício.</strong></p>
</div>
<?else:?>
<div>
    <p><strong>
    <?php
        echo $orgao->nome_orgao."<br>";
    ?>
    </strong></p>
    
    <!-- DIAS UTEIS -->
    <br>
    <div id="divMensagemRetorno"></div>
    <form method="POST" id="formHorarios" action="<?=Yii::app()->createUrl('horarios/salvarHorarios')?>">
        <table class="modelo1" style="text-align: center">
            <thead>
                <tr>
                    <th colspan="3">Dias úteis</th>
                </tr>
                <tr>
                    <th style="width: 75px"></th>
                    <th style="width: 200px">Início do Expediente</th>
                    <th style="width: 200px">Fim do Expediente</th>
                </tr>
            </thead>
            <tr>
                <td>Atual:</td>
                <td><?= Helper::HorarioOrgao($definicao->hora_inicio_expediente_hora) ?></td>
                <td><?= Helper::HorarioOrgao($definicao->hora_fim_expediente_hora) ?></td>
            </tr>
            <? if($podeEditar): ?>
            <tr>
                <td rowspan="2">Novo:</td>
                <td>
                    <input type="text" size="5" id="inhora_inicio_expediente" name="DefinicoesOrgao[hora_inicio_expediente_hora]" class="hora" value="<?=$definicao->hora_inicio_expediente_hora?>" onchange="validaCampo(this)"/> <br/>
                </td>
                <td>
                    <input type="text" size="5" id="inhora_fim_expediente" name="DefinicoesOrgao[hora_fim_expediente_hora]" class="hora" value="<?=$definicao->hora_fim_expediente_hora?>" onchange="validaCampo(this)"/> <br/>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="hora">
                    <span style="float:left; width: 10%; display: block;"><?=$aLimitesHorario['hora_inicio_expediente']?></span>
                    <div style="margin-left:7px;float:left; width: 76%; display: block;" id="sliderHorarioExpediente"></div>
                    <span style="margin-left:7px; float:left; width: 10%; display: block;"><?=$aLimitesHorario['hora_fim_expediente']?></span>
                    <input type="hidden" value="<?=$aLimitesHorario['hora_inicio_expediente']?>" id="inLimitehora_inicio_expediente">
                    <input type="hidden" value="<?=$aLimitesHorario['hora_fim_expediente']?>" id="inLimitehora_fim_expediente">
                </td>
            </tr>
            <? endif; ?>
        </table>
        <br>
        
        <!-- SABADO -->
        <? if(isset($aLimitesHorario, $aLimitesHorario['Sabado'])): ?>
            <br>
            <table class="modelo1" style="text-align: center">
                <thead>
                    <tr>
                        <th colspan="3">
                            <?if($podeEditar): 
                                echo(CHtml::checkBox('checkboxSabado', $definicao->sabado, array("onClick" => "checkSabado()")));
                            endif;?>
                            Sábado
                        </th>
                    </tr>
                    <tr>                
                        <th style="width: 75px"></th>
                        <th style="width: 200px">Início do Expediente</th>
                        <th style="width: 200px">Fim do Expediente</th>
                    </tr>
                </thead>
                <tr>
                    <td>Atual:</td>
                    <td><?= Helper::HorarioOrgao($definicao->hora_inicio_expediente_sabado_hora) ?></td>
                    <td><?= Helper::HorarioOrgao($definicao->hora_fim_expediente_sabado_hora) ?></td>
                </tr>
                <? if($podeEditar): ?>
                    <tr>                    
                        <td rowspan="2">Novo:</td>
                        <td>
                            <input type="text" size="5" name="DefinicoesOrgao[hora_inicio_expediente_sabado_hora]" id="inhora_inicio_expediente_sabado" class="sabado" value="<?=$definicao->hora_inicio_expediente_sabado_hora?>" onchange="validaCampo(this)"/>
                        </td>
                        <td>
                            <input type="text" size="5" name="DefinicoesOrgao[hora_fim_expediente_sabado_hora]" id="inhora_fim_expediente_sabado" class="sabado" value="<?=$definicao->hora_fim_expediente_sabado_hora?>" onchange="validaCampo(this)"/>
                        </td>


                    </tr>
                    <tr>
                        <td colspan="2" class="hora">
                            <span style="float:left; width: 10%; display: block;"><?=$aLimitesHorario['hora_inicio_expediente_sabado']?></span>
                            <div style="margin-left:7px;float:left; width: 76%; display: block;" id="sliderHorarioSabado"></div>
                            <span style="margin-left:7px; float:left; width: 10%; display: block;"><?=$aLimitesHorario['hora_fim_expediente_sabado']?></span>
                            <input type="hidden" value="<?=$aLimitesHorario['hora_inicio_expediente_sabado']?>" id="inLimitehora_inicio_expediente_sabado">
                            <input type="hidden" value="<?=$aLimitesHorario['hora_fim_expediente_sabado']?>" id="inLimitehora_fim_expediente_sabado">
                        </td>
                    </tr>
                <? endif; ?>
            </table>
            <br>
        <?endif?>
        <!-- DOMINGO -->
        <? if(isset($aLimitesHorario, $aLimitesHorario['Domingo'])): ?>        
            <br>
            <table class="modelo1" style="text-align: center">
                <thead>
                    <tr>
                        <th colspan="3">
                            <?if($podeEditar): 
                                echo(CHtml::checkBox('checkboxDomingo', $definicao->domingo, array("onClick" => "checkDomingo()")));
                            endif;?>
                            Domingo
                        </th>
                    </tr>
                    <tr>
                        <th style="width: 75px"></th>
                        <th style="width: 200px">Início do Expediente</th>
                        <th style="width: 200px">Fim do Expediente</th>
                    </tr>
                </thead>
                <tr>
                    <td>Atual:</td>
                    <td><?= Helper::HorarioOrgao($definicao->hora_inicio_expediente_domingo_hora) ?></td>
                    <td><?= Helper::HorarioOrgao($definicao->hora_fim_expediente_domingo_hora) ?></td>
                </tr>
                <? if($podeEditar): ?>
                    <tr>
                    
                        <td rowspan="2">Novo:</td>
                        <td>
                            <input type="text" size="5" name="DefinicoesOrgao[hora_inicio_expediente_domingo_hora]" id="inhora_inicio_expediente_domingo" class="domingo" value="<?=$definicao->hora_inicio_expediente_domingo_hora?>" onchange="validaCampo(this)"/> 
                        </td>
                        <td>
                            <input type="text" size="5" name="DefinicoesOrgao[hora_fim_expediente_domingo_hora]" id="inhora_fim_expediente_domingo" class="domingo" value="<?=$definicao->hora_fim_expediente_domingo_hora?>" onchange="validaCampo(this)"/> 
                        </td>                    
                    </tr>
                    <tr>
                        <td colspan="2" class="hora">
                            <span style="float:left; width: 10%; display: block;"><?=$aLimitesHorario['hora_inicio_expediente_domingo']?></span>
                            <div style="margin-left:7px;float:left; width: 76%; display: block;" id="sliderHorarioDomingo"></div>
                            <span style="margin-left:7px; float:left; width: 10%; display: block;"><?=$aLimitesHorario['hora_fim_expediente_domingo']?></span>
                            <input type="hidden" value="<?=$aLimitesHorario['hora_inicio_expediente_domingo']?>" id="inLimitehora_inicio_expediente_domingo">
                            <input type="hidden" value="<?=$aLimitesHorario['hora_fim_expediente_domingo']?>" id="inLimitehora_fim_expediente_domingo">
                        </td>
                    </tr>
                <? endif; ?>
            </table>
        <?endif?>
        <div>
            <input type="hidden" name="Orgao" id="Orgao" value="<?=$orgao->id_orgao?>" />
            <input type="hidden" name="podeEditar" value="<?=$podeEditar?>" />
            <?php
                if ($podeEditar)
                {
                    echo CHtml::submitButton(
                        'Salvar'
                    );
                    
                }
            ?>
            <p></p>
        </div>
    </form>
</div>
<?endif;?>