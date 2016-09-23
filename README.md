# Sistema de Ponto ELetrônico Livre (SPELL)

## Funcionalidades
* Acompanhamento de horários (funcionário e chefia)
* Registro de horários
* Solicitação de ajustes e abonos de horas

## Guia de instalação

#### Pré-requisitos
Para testar o sistema o computador deve conter os seguintes softwares:
* [PHP 5.3](https://secure.php.net/downloads.php) ou superior
* [Yii Framework 1.1](https://github.com/yiisoft/yii/releases/download/1.1.17/yii-1.1.17.467ff50.zip)
* Servidor Web (Recomenda-se o [USBWebserver](http://www.usbwebserver.net))

#### Como configurar
1. Baixe e mova o código fonte para a pasta de páginas do servidor. No caso do USBWebserver, coloque na pasta `root`
2. Baixe e extraia o Yii Framework para a pasta raiz do servidor
3. Execute o arquivo [ponto.sql](ponto.sql) no banco de dados

Por pardrão, as credenciais de acesso do banco de dados são:
```
usuário: root
senha: usbw
```

#### Como testar
* Para visualizar a tela do ponto acesse `localhost:8080/ponto`
* Para visualizar a tela de serviços acesse `localhost:8080/tempLogin/login`

Lista de usuários cadastrados para teste:

| Login	| Senha	| Tipo | Chefia | Cadastrado
|:---|:---|:---|:---|:---|
|1||Técnico|4|Departamento|
|2||Técnico|1|Unidade acadêmica|
|3||Docente|2|Órgão máximo|
|4||Técnico|0|Departamento|

*Observação: pode-se informar qualquer senha para realizar o login*

## Detalhes sobre o código
* A indentação do código segue a especificação [PSR-2](https://github.com/bobsta63/netbeans-psr-formatting)

## Como gerar a documentação

#### Método 1
* Instale o [PHPDocumentor](https://www.phpdoc.org/)
* Dentro da pasta raiz do projeto, execute o comando ```phpdoc -d ponto/protected/components/ -d ponto/protected/models/ -d ponto/protected/controllers/ -t ponto/docs/phpdoc```
* Acesse o endereço [localhost:8080/ponto/docs/phpdoc](localhost:8080/ponto/docs/phpdoc)

| Argumento	| Argumento extenso | Descrição	|
|:---|:---|:---|
|-d|--directory|Pasta onde está o código fonte|
|-t|--target|Pasta a ser armazenada a documentação|

#### Método 2
* Instale o [ApiGen](http://www.apigen.org/)
* Dentro da pasta raiz do projeto, execute o comando ```apigen generate -s ponto/protected/controllers -s ponto/protected/models -s ponto/protected/components -d ponto/docs/apigen```
* Acesse o endereço [localhost:8080/ponto/docs/apigen](localhost:8080/ponto/docs/apigen)

| Argumento	| Argumento extenso | Descrição	|
|:---|:---|:---|
|-s|--source|Pasta onde está o código fonte|
|-d|--destination|Pasta a ser armazenada a documentação|
