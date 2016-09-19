<?php

/**
 * Controlador utilizado para permitir ao usuário bater ponto.
 * 
 * Aqui são implementados os métodos para registrar entrada e saída do servidor,
 * permitir a visualização da jornada de trabalho e visualizar informações 
 * básicas do servidor como nome, foto e cargo.
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @version v1.0
 * @since v1.0
 */
class RegistroController extends BaseController
{

    /**
     * Método do Yii Framework para permitir a execução de código antes da 
     * execução de uma action.
     * 
     * Aqui são carregados os arquivos necessários para exibição do layout da 
     * aplicação como os arquivos HTML, CSS e JavaScript.
     * 
     * Além das ações mencionadas esse método também verifica a sessão do usuário
     * para garantir que há uma sessão válida chamada PontoUFRGS no navegador.
     * 
     * @param CAction $action A action do controller que foi requisitada.
     */
    public function beforeAction($action)
    {
        Yii::app()->getClientScript()->registerCoreScript('jquery');
        Yii::app()->getClientScript()->registerCoreScript('jquery.ui');
        Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl."/css/materialize/css/materialize.min.css");
        Yii::app()->getClientScript()->registerScript('home', 'var HOME = "' . Yii::app()->baseUrl . '/";', CClientScript::POS_HEAD);
        
        if ((Yii::app()->session->isStarted) && (Yii::app()->session->getSessionName() != "PontoUFRGS")) {
            Yii::app()->session->close();
            Yii::app()->session->destroy();
        }
        Yii::app()->session->setSessionName("PontoUFRGS");
        Yii::app()->session->open();
        $this->layout = 'registroPonto';
        
        $ip = $_SERVER['REMOTE_ADDR'];
        $ipv4 = substr($ip, 0, 6); // 143.54 no caso da UFRGS
        $ipv6 = substr($ip, 0, 11); // 2801:80:40: no caso da UFRGS
        // teste de IP da rede
//        if (($ipv4 != '143.54') && ($ipv6 != '2801:80:40:')) {
//            // fora da rede UFRGS, cai fora
//            $this->render('mensagem', array(
//                'mensagem' => "O registro de Ponto só funciona na rede da UFRGS.",
//            ));
//            return false;
//        }

        return true;
    }

    /**
     * Método do Yii Framework para adição de filtros nas actions. É executado
     * automaticamente antes de cada chamada a um controller para validar a 
     * sessão do usuário.
     * 
     * Está definido neste controller para remover os filtros adicionados na
     * superclasse {@see BaseController}.
     *
     * @return array Array vazio indicando a ausência de filtros
     */
    public function filters()
    {
        return array(
        );
    }

    /**
     * Action utilizada para mostrar o painel de controle do ponto eletrônico.
     * 
     * Esse método monta a tela que contem os botões para que o servidor possa
     * registrar seus horários de entrada e saída, bem como visualizar sua 
     * jornada e informações pessoais básicas.
     */
    public function actionIndex()
    {
        if (isset(Yii::app()->session['id_pessoa_ponto'])) {
            $pessoa = Pessoa::model()->with(array(
                    'DadosFuncionais' => array(
                        'joinType' => 'inner join',
                        'on' => 'coalesce(DadosFuncionais.data_desligamento, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP() 
                            and coalesce(DadosFuncionais.data_aposentadoria, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP() ' .
                        (isset($_GET['v']) ? 'and DadosFuncionais.nr_vinculo = ' . intval($_GET['v']) : '')
                    ),
                    'DadosFuncionais.GrupoEmprego' => array(
                        'joinType' => 'inner join',
                        'on' => "GrupoEmprego.segmento_grupo = 'T'"
                    ),
                    'DadosFuncionais.CatFuncional',
                    'DadosFuncionais.OrgaoLotacao',
                    'DadosFuncionais.OrgaoExercicio',
                ))->findByPk(Yii::app()->session['id_pessoa_ponto']);

            if ($pessoa) {
                if (count($pessoa->DadosFuncionais) == 1) {
                    // Apenas um vínculo
                    $pessoa->DadosFuncionais = $pessoa->DadosFuncionais[0];

                    // Verifica afastamentos e permissão de IP via função do banco
                    $testeLiberacao = RestricaoRelogio::verificaLiberacaoPonto(
                        $pessoa->id_pessoa, $pessoa->DadosFuncionais->matricula, $pessoa->DadosFuncionais->nr_vinculo, $_SERVER['REMOTE_ADDR']
                    );
                    if ($testeLiberacao['libera'] || (AMBIENTE == 'dev')) {
                        // Restrições passaram, pode bater ponto
                        $registrosHoje = Ponto::model()->findAll(array(
                            'condition' => "id_pessoa = :id_pessoa and DATE_FORMAT(data_hora_ponto, '%d/%m/%Y') = :Hoje",
                            'params' => array(
                                ':id_pessoa' => Yii::app()->session['id_pessoa_ponto'],
                                ':Hoje' => date('d/m/Y')
                            ),
                            'order' => 't.data_hora_ponto'
                        ));

                        $hora = date("H");
                        if ($hora < 12)
                            $saudacao = "Bom dia, ";
                        elseif ($hora < 19)
                            $saudacao = "Boa tarde, ";
                        else
                            $saudacao = "Boa noite, ";

                        if ($pessoa->DadosFuncionais->regime_trabalho == 'DE')
                            $pessoa->DadosFuncionais->regime_trabalho = 40;

                        $diasUteisMes = Ponto::getNrDiasUteis(date("m"), date("Y"));

                        $cargaHorariaDiaria = ($pessoa->DadosFuncionais->regime_trabalho / 5); // regime de trabalho / 5 
                        $cargaHorariaSemanal = $pessoa->DadosFuncionais->regime_trabalho; // regime de trabalho
                        $cargaHorariaMensal = ($pessoa->DadosFuncionais->regime_trabalho / 5) * $diasUteisMes; // regime de trabalho / 5 * numero de dias uteis no mes

                        $this->render('index', array(
                            'pessoa' => $pessoa,
                            'saudacao' => $saudacao,
                            'registrosHoje' => $registrosHoje,
                            'jornadaDiaria' => Ponto::getJornada('D', $pessoa->DadosFuncionais->nr_vinculo, $pessoa->id_pessoa) / 60, // em minutos
                            'jornadaSemanal' => Ponto::getJornada('S', $pessoa->DadosFuncionais->nr_vinculo, $pessoa->id_pessoa) / 60, // em minutos
                            'jornadaMensal' => Ponto::getJornada('M', $pessoa->DadosFuncionais->nr_vinculo, $pessoa->id_pessoa) / 60, // em minutos
                            'cargaHorariaDiaria' => $cargaHorariaDiaria,
                            'cargaHorariaSemanal' => $cargaHorariaSemanal,
                            'cargaHorariaMensal' => $cargaHorariaMensal
                        ));
                    }
                    else {
                        // Existe uma restrição para o ponto
                        $this->render('mensagem', array(
                            'mensagem' => $testeLiberacao['mensagem'],
                        ));
                    }
                }
                else {
                    // Selecionar vínculo
                    $this->render('selecionaVinculo', array(
                        'pessoa' => $pessoa,
                    ));
                }
            }
            else {
                // Sem vínculo que bata ponto
                $this->render('mensagem', array(
                    'mensagem' => "Você não precisa bater ponto. =)",
                ));
            }
        }
        else {
            // Redireciona para a tela de login
            $this->actionSair();
        }
    }

    /**
     * Action utilizada para mostrar a foto do servidor na página do ponto.
     */
    public function actionFoto()
    {
        if (isset(Yii::app()->session['id_pessoa_ponto'])) {
            header("content-type: image/jpeg");

            $this->desabilitaYiiToolbar();

            $sql = "select Foto from pessoa where id_pessoa = " . Yii::app()->session['id_pessoa_ponto'];
            $foto = Yii::app()->db->createCommand($sql)->queryScalar();
            if (ctype_xdigit($foto))
                print hex2bin($foto);
            else
                print $foto;
        }

        Yii::app()->end();
    }

    /**
     * Action utilizada para atualizar o relógio da página do ponto.
     */
    public function actionAtualizaRelogio()
    {
        $this->desabilitaYiiToolbar();
        print self::getData("H:i");
    }

    /**
     * Action utilizada para mostrar a jornada do usuário.
     * 
     * Esse método busca os registros de horários e os devolve em formato JSON
     * contendo o ultimo registro feito, a jornada diária e o tempo percorrido 
     * no ponto atual.
     * 
     * Exemplo de resposta em formato JSON:
     * 
     * <code>
     * {
     *    'ultimoRegistro' => [['hora' => '1000', 'tipo' =>'entrada']],
     *    'jornadaDiaria' => 480
     *    'agora' => 1200
     * }
     * </code>
     * 
     * O método deve receber o parâmetro nrVinculo via método POST.
     */
    public function actionGetUltimoRegistroEJornada()
    {
        if (isset(Yii::app()->session['id_pessoa_ponto'], $_POST['nrVinculo']) && ($_POST['nrVinculo'] != 0)) {
            $ultimoRegistro = Ponto::model()->find(array(
                'condition' => "id_pessoa = :id_pessoa and nr_vinculo = :nr_vinculo and DATE_FORMAT(data_hora_ponto, '%d/%m/%Y') = :Data",
                'params' => array(
                    ':id_pessoa' => Yii::app()->session['id_pessoa_ponto'],
                    ':nr_vinculo' => $_POST['nrVinculo'],
                    ':Data' => date("d/m/Y"),
                ),
                'order' => 't.data_hora_ponto DESC',
                'limit' => 1
            ));

            if (!empty($ultimoRegistro)) {
                $ultimoRegistro = array(
                    'hora' => strtotime($ultimoRegistro->data_hora_ponto) / 60, // em minutos
                    'tipo' => $ultimoRegistro->entrada_saida
                );
            }
            else {
                $ultimoRegistro = array();
            }

            $jornada = Ponto::getJornada('D', $_POST['nrVinculo']);

            print CJSON::encode(array(
                    'ultimoRegistro' => $ultimoRegistro,
                    'jornadaDiaria' => $jornada / 60, // em minutos
                    'agora' => time() / 60 // em minutos
            ));
        }
    }

    /**
     * Action utilizada para salvar o registro feito pelo usuário.
     * 
     * Ao clicar nos botões de entrada e saída da tela principal esta action é
     * chamada e o tempo é salvo no banco de dados.
     * 
     * Para poder registrar corretamente o ponto os seguintes parâmetros devem
     * ser informados via método POST: tipo (E para entrada ou S para saída) e
     * nrVinculo (indicando o vínculo do servidor com a instituição).
     * 
     * Esse método retorna um objeto JSON contendo uma mensagem e um código de
     * erro.
     * 
     * Exemplo de resposta do método:
     * <code>
     * {
     *    'erro' => 0,
     *    'msg' => 'Bom trabalho!'
     * }
     * </code>
     */
    public function actionRegistraPonto()
    {
        if (isset(Yii::app()->session['id_pessoa_ponto'], $_POST['nrVinculo']) && ($_POST['nrVinculo'] != 0)) {
            if (in_array($_POST['tipo'], array('E', 'S'))) {
                $nrVinculo = intval($_POST['nrVinculo']);
                $pessoa = Pessoa::model()->with(array(
                        'DadoFuncional' => array(
                            'on' => 'DadoFuncional.nr_vinculo = ' . $nrVinculo,
                            'joinType' => 'inner join'
                        )
                    ))->findByPk(Yii::app()->session['id_pessoa_ponto']);

                $erro = false;
                $msg = ($_POST['tipo'] == 'E' ? 'Entrada' : 'Saída') . " registrada com sucesso! ";
                if ($_POST['tipo'] == 'E')
                    $msg .= "Bom trabalho!";
                else if (date("H") > 15)
                    $msg .= "Bom descanso!";
                else
                    $msg .= "Até breve!";

                if (!empty($pessoa)) {
                    // verifica afastamentos e permissão de IP via funcao do banco
                   // verifica afastamentos e permissao de IP via funcao do banco
                    $testeLiberacao = RestricaoRelogio::verificaLiberacaoPonto(
                        $pessoa->id_pessoa, $pessoa->DadoFuncional->matricula, $pessoa->DadoFuncional->nr_vinculo, $_SERVER['REMOTE_ADDR']
                    );
                    if ($testeLiberacao['libera'] || (AMBIENTE == 'dev')) {
                        $registroPonto = new Ponto();
                        $registroPonto->id_pessoa = $pessoa->id_pessoa;
                        $registroPonto->matricula = $pessoa->DadoFuncional->matricula;
                        $registroPonto->nr_vinculo = $pessoa->DadoFuncional->nr_vinculo;
                        $registroPonto->data_hora_ponto = new CDbExpression("DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL -30 SECOND)");
                        $registroPonto->entrada_saida = $_POST['tipo'];
                        $registroPonto->id_pessoa_registro = $pessoa->id_pessoa;
                        $registroPonto->data_hora_registro = new CDbExpression("CURRENT_TIMESTAMP()");
                        $registroPonto->ip_registro = $_SERVER['REMOTE_ADDR'];
                        $registroPonto->ambiente_registro = $_SERVER['HTTP_USER_AGENT'];
                        if (!$registroPonto->save()) {
                            $erro = true;
                            $msg = "Ocorreu um erro ao registrar o ponto. " . print_r($registroPonto->getErrors(), true);
                        }
                    }
                    else {
                        $erro = true;
                        $msg = str_replace(">", "", $query['mensagem']);
                    }
                }
                else {
                    $erro = true;
                    $msg = "Pessoa não encontrada.";
                }
            }
            else {
                $erro = true;
                $msg = "Que tipo de registro você está querendo fazer?";
            }

            print CJSON::encode(array(
                    'erro' => intval($erro),
                    'msg' => $msg
            ));
        }
    }

    /**
     * Action utilizada para autenticar o usuário.
     * 
     * Esse método verifica se a credencial (código e senha) inseridos pelo 
     * usuário é válida. Se for uma sessão é criada e o mesmo é redirecionado
     * para a página principal.
     * 
     * Para a verificação acontecer deve-se passar os parâmetros usuario e senha
     * via método POST.
     */
    public function actionLogin()
    {
        if (isset($_POST['usuario'])) {
            $usuario = intval($_POST['usuario']) % 1000000; // usuário e numerico com no maximo 6 digitos
            $senha = str_replace("'", "''", $_POST['senha']);

            if (AMBIENTE == 'producao') {
                // TODO teste de senha em ambiente de produção
                if ($erro) {
                    // TODO registro da falha em log
                    
                    $this->render('login', array(
                        'usuario' => $usuario,
                        'mensagem' => $result['mensagem'],
                    ));
                    Yii::app()->end();
                }
            }
            // TODO registro do acesso com sucesso
            Yii::app()->session['id_pessoa_ponto'] = $usuario;
            $this->actionIndex();
        }
        else {
            $this->render('login');
        }
    }

    /**
     * Action utilizada para realizar o logout do usuário.
     * 
     * Método que destrói a sessão do usuário e o redireciona para a página de 
     * login.
     */
    public function actionSair()
    {
        Yii::app()->session->setSessionName("PontoUFRGS");
        Yii::app()->session->destroy();
        Yii::app()->session->close();
        $this->actionLogin();
    }

    /**
     * Método auxiliar para exibição da hora dependendo da origem.
     * 
     * Esse método tem o código necessário para retornar uma data contida no
     * banco de dados ou gerada pelo servidor usando a função <code>date()</code>
     * nativa da linguagem PHP, bastando informar o formato desejada.
     * 
     * Exemplo de formato para a data: Y-m-d H:i:s
     * 
     * @param string $formato Formato desejada para a data
     * @return string Uma string contendo a data atual no formato requisitado
     */
    public static function getData($formato)
    {
        /** VIA BANCO
        $aux = explode(" ", Yii::app()->db->createCommand('select CURRENT_TIMESTAMP()')->queryScalar());
        $auxData = explode("-", $aux[0]);
        $auxHora = explode(":", $aux[1]);
        $auxSegundos = explode(".", $auxHora[2]);
        
        $data = str_replace(array('d', 'm', 'Y', 'H', 'i', 's'), array($auxData[2], $auxData[1], $auxData[0], $auxHora[0], $auxHora[1], $auxSegundos[0]), $formato);
        
        return $data;
         **/
        /** VIA SERVIDOR */
        return date($formato);
    }

}