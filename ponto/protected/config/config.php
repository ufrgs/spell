<?php
return array(
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.controllers.BaseController',
    ),
    'preload'=>array('log'),
    'components' => array(
        'assetManager' => array('class' => 'CAssetManager', ),
        'errorHandler' => array(
            'errorAction' => 'base/Error',
        ),
        'user' => array(
            'class' => 'application.components.UsuarioWeb'
        ),
        'seguranca' => array(
            'class' => 'application.components.ComponenteSeguranca',
            'permissao' => array(),
        ),
        // TODO - configurar acesso ao banco de dados
        'db'=>array(
            'connectionString' => 'mysql:host=localhost:3307;dbname=ponto',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'usbw',
            'charset' => 'utf8',
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'caseSensitive'=>false,
            'rules' => array(
                '<controller:\w+>/<id:\d+>'=>'<controller>/ver',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>/<id:\d+>/<id2:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>'
            ),
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning, trace, profile',
                    'filter' => 'CLogFilter',
                ),
                // uncomment the following to show log messages on web pages
    //                array(
    //                    'class'=>'CWebLogRoute',
    //                ),
            ),
        ),
    ),
    'defaultController' => 'registro',
    'name' => 'Ponto Eletrônico',
    'charset' => 'utf-8',
    'sourceLanguage' => 'pt_br',
    'language' => 'pt_br',
);
