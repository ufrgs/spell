
<div class="card" id="divLogin">
    <div class="row">
        <img class="logo" src="<?=Yii::app()->baseUrl?>/imgs/logo.png" width="180" alt="Logo do Ponto Eletrônico"/> <br/>
    </div>
    <div id="relogio"><?=RegistroController::getData("H:i")?></div>
    <? if (isset($mensagem)): ?>
        <fieldset class="aviso">
            <?=$mensagem?>
        </fieldset>
    <? endif; ?>
    <form action="<?=Yii::app()->createUrl("registro/login")?>" method="post" onsubmit="return verificaSenhaEmBranco()">
        <label for="usuario">Número de identificação</label> <br/>
        <input type="text" maxlength="8" id="usuario" name="usuario" placeholder="número" value="<?=(isset($usuario) ? $usuario : '')?>" autocomplete="off"/> <br/>
        <label for="senha">Senha</label> <br/>
        <input type="password" id="senha" name="senha" placeholder="senha" autocomplete="off"/> <br/>
        <button><i class="fa fa-key"></i> Registrar ponto</button>
    </form>    
</div>