# Sistema de Ponto ELetrônico Livre (SPELL)
Esse sistema foi desenvolvido no [Centro de Processamento de Dados](http://www.ufrgs.br/cpd/)(CPD) da [Universidade Federal do Rio Grande do Sul](http://www.ufrgs.br/)(UFRGS) para ser implementado em pontos eletrônicos da [Universidade Federal Fluminense](http://www.uff.br/)(UFF) visando melhorar o controle de horários dos servidores da instituição.

## Funcionalidades
* Acompanhamento de horários (funcionário e chefia)
* Registro de horários
* Solicitação de ajustes e abonos de horas

## Guia de instalação

#### **Pré-requisitos**
Para testar o sistema o computador deve conter os seguintes softwares:
* [PHP 5.3](https://secure.php.net/downloads.php) ou superior
* [Yii Framework 1.1](https://github.com/yiisoft/yii/releases/download/1.1.17/yii-1.1.17.467ff50.zip)
* Servidor Web (Recomenda-se o [USBWebserver](http://www.usbwebserver.net))

#### **Como configurar**
1. Baixe e mova o código fonte para a pasta de páginas do servidor. No caso do USBWebserver, coloque na pasta `root`
2. Baixe e extraia o Yii Framework para a pasta raiz do servidor
3. Execute o arquivo [ponto.sql](ponto.sql) no banco de dados

Por pardrão, as credenciais de acesso do banco de dados são:
```
usuário: root
senha: usbw
```

#### **Como testar**
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

## Guia de configuração

#### **Conexão com o banco de dados**
Os dados de acesso ao banco de dados são especificados no arquivo `ponto/protected/config/config.php`.
Basta alterar os valores contidos na chave `db` do array `components`.

```
'db'=>array(
        'connectionString' => 'mysql:host=localhost:3307;dbname=ponto',
        'emulatePrepare' => true,
        'username' => 'root',
        'password' => 'usbw',
        'charset' => 'utf8',
    ),
```

#### **Segurança do end-point de registros**
Para garantir que somente os pontos oficiais possam realizar registros, 
recomenda-se adicionar o seguinte código no método `beforeAction` da classe
`RegistroController` realizando as substituições necessárias:

```
if (($ipv4 != 'Número IPv4 da rede') && ($ipv6 != 'Número IPv6 da rede')) {
    $this->render('mensagem', array(
        'mensagem' => "O registro de Ponto só funciona na rede da UFF.",
    ));
    return false;
}
```

## Mais informações
Para mais informações, guias e tutoriais sobre o projeto acesse a [Wiki](http://gitlab.dev.ufrgs.br/lvalente/cpd-spell/wikis/home) do repositório.