<?php
header('Content-Type: text/html; charset=utf8');

define('APLICACAO_GERENCIA', 1); // meia hora
define('URL_TUTORIAL', '#!'); // meia hora
define('AMBIENTE', 'dev');
define('YII_DEBUG', (AMBIENTE == 'dev')); // alterar para falso em produção

date_default_timezone_set('America/Sao_Paulo');

$config = 'protected/config/config.php';

require_once '../yii/framework/yii.php';

Yii::createWebApplication($config)->run();
