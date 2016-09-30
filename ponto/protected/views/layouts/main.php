<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
        <meta http-equiv="X-UA-Compatible" content="IE=100" />
        <meta http-equiv="Content-Script-Type" content="text/javascript" />
        <meta name="language" content="pt-BR" />
        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <title>Ponto Eletrônico</title>
    </head>

    <body>
        <div id="container">
            <header>
                <span class="tituloGeral">SPELL &mdash; Acompanhamento e Ajustes</span>
                <?php
                    if (isset(Yii::app()->session['id_pessoa'])): ?>
                        <a class="logoff" href="<?=Yii::app()->createUrl("tempLogin/sair")?>"><span style="margin-top: 4px ">Sair (<?=Yii::app()->user->nome_pessoa?>)</span></a>
                <?  endif; ?>
            </header>
            <main>
                <?php
                $tituloPagina = "Ponto Eletrônico";
                if (isset(Yii::app()->session['id_pessoa'], Yii::app()->controller->menu)): ?>
                    <nav class="escondeNaImpressao">
                        <span><?=Yii::app()->controller->menu['label']?></span>
                        <ul>
                        <? foreach (Yii::app()->controller->menu['items'] as $menu):
                            if (!isset($menu['visible']) || $menu['visible']): ?>
                                <li class="<?=($menu['active'] ? 'ativo' : '')?>">
                                    <a href="<?=(is_array($menu['url']) ? Yii::app()->createUrl($menu['url'][0]) : $menu['url'])?>">
                                        <?=$menu['label']?>
                                    </a>
                                </li>
                            <? if ($menu['active']):
                                    $tituloPagina = $menu['label'];
                                endif;
                            endif;
                        endforeach; ?>
                        </ul>
                    </nav>
                <? endif; ?>    
                <section>
                    <h1><?=$tituloPagina?></h1>
                <?php
                    print $content;
                ?>
                </section>
            </main>
        </div>
        <div id="fundoModal" style="display:none" onclick="fechaModal()"></div>
        <div id="janelaModal" style="display:none"><span class="tituloCard"></span><span class="conteudo"></span><span class="botoes"></span></div>
    
        <script type="text/javascript" src="<?=Yii::app()->baseUrl?>/js/jquery/jquery.maskedinput-1.2.2.js"></script>
    </body>
</html>
