<header class="row">
	<div class="col s12 m5">
		<img id="logo" src="<?=Yii::app()->baseUrl?>/imgs/logoPonto.png" alt="Logo do Ponto Eletrônico da UFRGS"/>
	</div>

	<? if (count($pessoa->DadosFuncionais) == 1): ?>

	<div class="col s12 m7">
		<div id="divUsuario" class="flow-text">
			<h5><?=$pessoa->nome_pessoa?></h5>
			<ul>
				<li>
					Cargo: <?=$pessoa->DadosFuncionais->CatFuncional->nome_categoria?> - <span><?=$pessoa->DadosFuncionais->regime_trabalho?>h</span>
				</li>
				<li>
					Lotação: <?=$pessoa->DadosFuncionais->OrgaoLotacao->nome_orgao?>
				</li>
				<li>
					Exercício: <?=$pessoa->DadosFuncionais->OrgaoExercicio->nome_orgao?>
				</li>
			</ul>	
			<picture class="foto">
			    <source srcset="<?=Yii::app()->createUrl("registro/foto")?>" media="(max-width: 768px)" media="(max-width: 768px)">
			    <source srcset="<?=Yii::app()->createUrl("registro/foto")?>" media="(max-width: 768px)">
			    <img srcset="/fotos/<?=$pessoa->id_pessoa?>.<?=$pessoa->tipo_foto?>" media="(max-width: 768px)" alt="Foto de <?=$pessoa->nome_pessoa?>">
			</picture>
		</div>	
	</div>
</header>
<div class="row">
	<div class="col s12 m5" id="divRegistrosHoje">		
		<div class="rounded">
			<table class="centered">
				<thead>
					<tr>
						<th colspan="2">
							<span class="flow-text">Acompanhamento da jornada</span>
						</th>
					</tr>
					<? if (!empty($registrosHoje)): ?>
					<th>
						ENTRADA
					</th>
					<th>
						SAÍDA
					</th>
				</thead>
                                    <?php
                                    $indUltReg = 'S';
                                    foreach ($registrosHoje as $registro){                                    
                                        if($registro->entrada_saida == 'E'){
                                            echo "<tr><td>".date('H:i', strtotime($registro->data_hora_ponto))."</td>";                                        
                                        }elseif ($registro->entrada_saida == 'S' && $indUltReg == 'S'){
                                            echo "<tr><td></td><td>".date('H:i', strtotime($registro->data_hora_ponto))."</td></tr>";
                                        }else{
                                            echo "<td>".date('H:i', strtotime($registro->data_hora_ponto))."</td></tr>";
                                        }
                                        $indUltReg = $registro->entrada_saida;
                                    }
                                    ?>
				
				<? else: ?>
					</thead>
						<tr><td colspan="2">Não existem registros.</td></tr>
				<? endif; ?>		
			</table>
		</div>
		
		<div id="dia">	
			<div class="floating">
				<div class="text">dia</div>
			</div>
			
                        <div class="expand <?=(($jornadaDiaria/60) > $cargaHorariaDiaria) ? "limite" : ""?> " style="width:calc(<?=100/$cargaHorariaDiaria*($jornadaDiaria/60)?>% - 72px)">
                            <span class="text">
                                <!--
                                Se a quantidade de horas trabalhadas ultrapassar 21% do carga horaria
                                ele exibe o numero de horas
                                esse teste evita que o numero seja exibido quando nao ha espaco suficiente
                                -->
                                <? if (100/$cargaHorariaDiaria*($jornadaDiaria/60) > 21): ?>
                                    <?=Helper::transformaEmFormatoHora($jornadaDiaria)?>h
                                <? endif; ?>
                            </span>
			</div>
		</div>	

		<div id="semana">
			<div class="floating">
				<div class="text">semana</div>
			</div>
			<div class="expand <?=(($jornadaSemanal/60) > $cargaHorariaSemanal) ? "limite" : ""?>" style="width:calc(<?=100/$cargaHorariaSemanal*($jornadaSemanal/60)?>% - 72px)">
				<span class="text">
                                    <? if (100/$cargaHorariaSemanal*($jornadaSemanal/60) > 21): ?>
                                        <?=Helper::transformaEmFormatoHora($jornadaSemanal)?>h
                                    <? endif; ?>
                                </span>
			</div>
		</div>
			
		<div id="mes">	
			<div class="floating">
				<div class="text">mês</div>
			</div>			
			<div class="expand <?=(($jornadaMensal/60) > $cargaHorariaMensal) ? "limite" : ""?>" style="width:calc(<?=100/$cargaHorariaMensal*($jornadaMensal/60)?>% - 72px)">
                    
				<span class="text">
                                    <? if (100/$cargaHorariaMensal*($jornadaMensal/60) > 21): ?>
                                        <?=Helper::transformaEmFormatoHora($jornadaMensal)?>h
                                    <? endif; ?>
                                </span>
			</div>
		</div>	

		<? if ($jornadaDiaria > $cargaHorariaDiaria*60): ?>
		<div id="aviso">
			<p><span class="atencao">Atenção!</span><br>
			Você ultrapassou o limite de horas <span>diárias</span>.</p>
		</div>
	<? endif; ?>
	</div>

	<div class="col s12 m7" >
		<div id="divRegistro">
			<input type="hidden" id="nrVinculo" value="<?=$pessoa->DadosFuncionais->nr_vinculo?>"/>
            <input type="hidden" id="tipoUltimoRegistro" value="<?=(!empty($registrosHoje) ? $registrosHoje[count($registrosHoje)-1]->entrada_saida : 'S')?>"/>

			<div class="center"><?=Helper::diaDeHoje()?></div>
			<div id="relogio"><?=RegistroController::getData("H:i")?></div>
			<div id="botoes" class="center buttons flow-text">
				<button id="btEntrada" onclick="registraEntrada()" class="waves-effect waves-light btn-large green darken-4">ENTRADA</button>
				<button id="btSaida" onclick="registraSaida()" class="waves-effect waves-light btn-large red darken-4 waves-red">SAÍDA</button>	
			</div>
		</div>
		<p id="mensagemAjuste" class="center">Para realizar ajustes e acompanhamento, acesse o Portal de Serviços.</p>
	</div>
</div>

<? endif; ?>