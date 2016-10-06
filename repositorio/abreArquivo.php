<?php

session_start();

if (!($temp = each($_GET)))
    die("Parâmetros insuficientes!");
$ChaveDoc = $temp[0]; // O primeiro argumento deve a Chave do Documento

if (!($temp = each($_GET)))
    die("Parâmetros insuficientes!");

$TipoDoc = $temp[0]; // O segundo argumento deve o Tipo de Documento

$Download = ( ($temp = each($_GET)) && ($temp[0] == "D") );

$sEndereco = $_SERVER['DOCUMENT_ROOT'] . '/repositorio/docs/' . $ChaveDoc . '.txt';
$sArquivo = $_SERVER['DOCUMENT_ROOT'] . '/repositorio/docs/' . $ChaveDoc;
$DadosDocumento = unserialize(file_get_contents($sEndereco));

$Pedacos = explode(".", $DadosDocumento['nome_arquivo']);
$Extensao = $Pedacos[count($Pedacos) - 1];

if ($DadosDocumento['data_expiracao'] != '') {
    $aData = explode('/', $DadosDocumento['data_expiracao']);
    $iData = intval($aData[2] . $aData[1] . $aData[0]);
    if (date('Ymd') > $iData)
        die('Documento inválido!');
}

switch ($Extensao) {
    case "pdf":
        $Mime = "application/pdf";
        break;
    case "jpg": case "jpeg":
        $Mime = "image/jpeg";
        break;
    case "gif":
        $Mime = "image/gif";
        break;
    case "txt":
        $Mime = "text/plain";
        break;
    case "doc": case "docx":
        $Mime = "application/msword";
        break;
    case "png":
        $Mime = "image/png";
        break;
    case "ppt": case "pps": case "pptx": case "ppsx":
        $Mime = "application/mspowerpoint";
        break;
    case "xls": case "xlsx":
        $Mime = "application/x-msexcel";
        break;
    case "ods":
        $Mime = "application/vnd.oasis.opendocument.spreadsheet";
        break;
    case "odt":
        $Mime = "application/vnd.oasis.opendocument.text";
        break;
    case "odp":
        $Mime = "application/vnd.oasis.opendocument.presentation";
        break;
    case "odg":
        $Mime = "application/vnd.oasis.opendocument.graphics";
        break;
    default:
        $Mime = "application/force-download";
        break;
}

header('cache-control: ');
header('pragma: ');
header('Content-type: ' . $Mime);

if ($Download)
    header('Content-Disposition: attachment; filename="' . $DadosDocumento['nome_arquivo'] . '"');
else
    header('Content-Disposition: inline; filename="' . $DadosDocumento['nome_arquivo'] . '"');

$temp = file_get_contents($sArquivo, FILE_BINARY);
echo $temp;
?>