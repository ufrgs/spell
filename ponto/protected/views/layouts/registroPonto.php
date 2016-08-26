<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
        <meta http-equiv="X-UA-Compatible" content="IE=100" />
        <meta http-equiv="Content-Script-Type" content="text/javascript" />
        <meta name="language" content="pt-BR" />
        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <link rel="stylesheet" type="text/css" href="<?=Yii::app()->baseUrl?>/css/registro.css"/>
        <title>Ponto Eletrônico</title>
    </head>

    <body>
        <div id="container">
            <div id="top">
                <?php
                    if (isset(Yii::app()->session['CodPessoaPonto'])): ?>
                    <a class="logoff" href="<?=Yii::app()->createUrl("registro/sair")?>"><span style="margin-top: 4px ">Sair</span></a>
                <?  endif; ?>

                <div id="acessibilidade" title="Diminuir tamanho do texto">
                    <a style="display:none" href="javascript:diminuirTexto()">
                        (&minus;)
                        <span>diminuir</span>
                    </a>
                    <a href="javascript:aumentarTexto()" title="Aumentar tamanho do texto">
                        (+)
                        <span>aumentar</span>
                    </a>
                </div>
            </div>
        <?php
            print $content;
        ?>
        </div>

        <input type="hidden" id="segundosAteAtualizar" value="<?=60-RegistroController::getData("s")?>"/>
        
        <div id="fundoModal" style="display:none" onclick="fechaModal()"></div>
        <div id="janelaModal" style="display:none"><span class="tituloCard"></span><span class="conteudo"></span><span class="botoes"></span></div>
    
        <script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/registro.js"></script>
        <script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/circle-progress.js"></script>
        <script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/jquery/jquery.maskedinput-1.2.2.js"></script>
    </body>
</html>