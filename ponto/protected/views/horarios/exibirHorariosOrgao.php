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
    
    <!-- DIAS ÚTEIS -->
    <br>
    <form method="POST" action="<?=Yii::app()->createUrl('horarios/salvarHorarios')?>">
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
                <td><?= Helper::HorarioOrgao($definicao->HoraInicioExpediente_hora) ?></td>
                <td><?= Helper::HorarioOrgao($definicao->HoraFimExpediente_hora) ?></td>
            </tr>
            <? if($podeEditar): ?>
            <tr>
                <td>Novo:</td>
                <td>
                    <input type="text" size="5" name="DefinicoesOrgao[HoraInicioExpediente_hora]" class="hora" value="<?=$definicao->HoraInicioExpediente_hora?>" onchange="validaCampo(this)"/> <br/>
                </td>
                <td>
                    <input type="text" size="5" name="DefinicoesOrgao[HoraFimExpediente_hora]" class="hora" value="<?=$definicao->HoraFimExpediente_hora?>" onchange="validaCampo(this)"/> <br/>
                </td>
            </tr>
            <? endif; ?>
        </table>
        <br>
        
        <!-- SÁBADO -->
        <?if((!$podeEditar)&&(!$definicao->sabado)):?>
        <?else:?>
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
                    <td><?= Helper::HorarioOrgao($definicao->HoraInicioExpedienteSabado_hora) ?></td>
                    <td><?= Helper::HorarioOrgao($definicao->HoraFimExpedienteSabado_hora) ?></td>
                </tr>
                <tr>
                    <? if($podeEditar): ?>
                    <td>Novo:</td>
                    <td>
                        <input type="text" size="5" name="DefinicoesOrgao[HoraInicioExpedienteSabado_hora]" class="sabado" value="<?=$definicao->HoraInicioExpedienteSabado_hora?>" onchange="validaCampo(this)"/>
                    </td>
                    <td>
                        <input type="text" size="5" name="DefinicoesOrgao[HoraFimExpedienteSabado_hora]" class="sabado" value="<?=$definicao->HoraFimExpedienteSabado_hora?>" onchange="validaCampo(this)"/>
                    </td>
                    <? endif; ?>
                </tr>
            </table>
            <br>
        <?endif?>
        <!-- DOMINGO -->
        <?if((!$podeEditar)&&(!$definicao->domingo)):?>
        <?else:?>
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
                    <td><?= Helper::HorarioOrgao($definicao->HoraInicioExpedienteDomingo_hora) ?></td>
                    <td><?= Helper::HorarioOrgao($definicao->HoraFimExpedienteDomingo_hora) ?></td>
                </tr>
                <tr>
                    <? if($podeEditar): ?>
                    <td>Novo:</td>
                    <td>
                        <input type="text" size="5" name="DefinicoesOrgao[HoraInicioExpedienteDomingo_hora]" class="domingo" value="<?=$definicao->HoraInicioExpedienteDomingo_hora?>" onchange="validaCampo(this)"/> 
                    </td>
                    <td>
                        <input type="text" size="5" name="DefinicoesOrgao[HoraFimExpedienteDomingo_hora]" class="domingo" value="<?=$definicao->HoraFimExpedienteDomingo_hora?>" onchange="validaCampo(this)"/> 
                    </td>
                    <? endif; ?>
                </tr>
            </table>
        <?endif?>
        <div>
            <input type="hidden" name="Orgao" id="Orgao" value="<?=$orgao->id_orgao?>" />
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