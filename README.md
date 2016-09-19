# Sistema de Ponto ELetrnico Livre (SPELL)

## Funcionalidades
* Acompanhamento de horrios (funcionrio e chefia)
* Registro de horrios
* Solicitaço de ajustes e abonos de horas

## Guia de instalaço

#### Pr-requisitos
Para testar o sistema o computador deve conter os seguintes softwares:
* [PHP 5.3](https://secure.php.net/downloads.php) ou superior
* [Yii Framework 1.1](https://github.com/yiisoft/yii/releases/download/1.1.17/yii-1.1.17.467ff50.zip)
* Servidor Web (Recomenda-se o [USBWebserver](http://www.usbwebserver.net))

#### Como configurar
1. Baixe e mova o cdigo fonte para a pasta de pginas do servidor. No caso do USBWebserver, coloque na pasta `root`
2. Baixe e extraia o Yii Framework para a pasta raiz do servidor
3. Exececute o arquivo [ponto.sql](ponto.sql) no banco de dados

Por pardro, as credenciais de acesso do banco de dados so:
```
usurio: root
senha: usbw
```

#### Como testar
* Para visualizar a tela do ponto acesse `localhost:8080/ponto`
* Para visualizar a tela de serviços acesse `localhost:8080/tempLogin/login`

Lista de usurios cadastrados para teste:

| Login| Senha| Tipo | Chefia | Cadastrado
|:---|:---|:---|:---|:---|
|1||Tcnico|4|Departamento|
|2||Tcnico|1|Unidade acadmica|
|3||Docente|2|rgo mximo|
|4||Tcnico||Departamento|

*Observaço: pode-se informar qualquer senha para realizar o login*

## Detalhes sobre o cdigo
* A indentaço do cdigo segue a especificaço [PSR-2](https://github.com/bobsta63/netbeans-psr-formatting)
