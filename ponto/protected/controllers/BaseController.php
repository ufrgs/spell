<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Derivação da classe CController do Yii Framework para reaproveitamento de 
 * código. Todos os controladores da aplicação são extensões desta classe.
 * 
 * Aqui são definidas as regras de acesso das páginas, configuração do menu 
 * lateral da aplicação e métodos utilitários para os controladores.
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage controllers
 * @version v1.0
 * @since v1.0
 */
class BaseController extends CController
{
    
    /**
     * Atributo que representa o menu da aplicacao. É instanciado no método 
     * privado setMenu().
     * 
     * @var array Instância do menu da aplicação
     */
    public $menu = array();

    /**
     * Método do Yii Framework para adição de filtros nas actions. É executado
     * automaticamente antes de cada chamada a um controller para validar a 
     * sessão do usuário.
     *
     * @return array Implementações da classe CFilter
     */
    public function filters()
    {
        return array(
            array('application.components.Sessao - Error, Login, Sair'),
            'accessControl - Error, Login, Sair',
        );
    }

    /**
     * Método do Yii Framework para definição das regras de acesso as páginas.
     * 
     * @link http://www.yiiframework.com/doc/guide/1.1/en/topics.auth#access-control-filter
     * @return array Regras de segurança para a acesso as páginas
     */
    public function accessRules()
    {
        return array(
            array(
                'deny',
                'users' => array('?'),
            ),
            array(
                'allow',
                'users' => array('*'),
                'controllers' => array('consolida'),
                'actions' => array('atualizaAfastamentosJuntaMedica'),
            ),
            array(
                'allow',
                'users' => array('@'),
                'controllers' => array('theme'),
            ),
            array(
                'allow',
                'users' => array('@'),
                'controllers' => array('registro', 'acompanhamento', 'calendario', 'ajuste', 'relatorio', 'horarios'),
            ),
            array(
                'allow',
                'users' => array('@'),
                'controllers' => array('compensacao'),
            ),
            array(
                'allow',
                'roles' => array(APLICACAO_GERENCIA),
                'controllers' => array('restricao', 'gerencia'),
            ),
            array(
                'allow',
                'users' => array('3'),
                'controllers' => array('consolida'),
            ),
            array(
                'deny',
            ),
        );
    }

    /**
     * Método do Yii Framework para permitir a execução de código antes da 
     * execução de uma action.
     * 
     * Aqui são carregados os arquivos necessários para exibição do layout da 
     * aplicação como os arquivos HTML, CSS e JavaScript.
     * 
     * @param CAction $action A action do controller que foi requisitada
     */
    public function beforeAction($action)
    {
        // Para depuração de theme em application
        //Yii::app()->themeManager->basePath = Yii::getPathOfAlias('application.cpd.themes');
        $this->setMenu();
        $this->layout = 'main';
        Yii::app()->getClientScript()->registerCoreScript('jquery');
        Yii::app()->getClientScript()->registerCoreScript('jquery.ui');
        Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl."/css/estilos.css");
        Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl."/css/font-awesome/css/font-awesome.min.css");
        Yii::app()->getClientScript()->registerScript('home', 'var HOME = "' . Yii::app()->baseUrl . '/";', CClientScript::POS_HEAD);

        return parent::beforeAction($action);
    }

    /**
     * Método utilitário para exibição de uma página com erros. É chamado quando
     * o controller registra algum erro de execução e precisa mostra-lo na tela.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            $error['admin'] = 'ADMIN';
            $error['time'] = time();
            $error['version'] = '';
            $this->render('system.views.pt_br.error', array('data' => $error));
        }
    }

    /**
     * Método para montagem do menu lateral exibido nas telas da aplicação.
     */
    private function setMenu()
    {
        // Variável que faz o controle de qual menu está ativo
        $actionId = Yii::app()->controller->id . '/' . Yii::app()->controller->action->id;

        if (isset(Yii::app()->session['id_pessoa'])) {
            $orgaosChefia = RestricaoRelogio::getOrgaosChefia(Yii::app()->session['id_pessoa']);
            
            // Monta a listagem de menus da aplicacao
            $this->menu = array(
                'label' => 'Opções disponíveis',
                'items' => array(
                    array('label' => 'Acompanhamento de Registros',
                        'url' => array("acompanhamento/index"),
                        'active' => $actionId == 'acompanhamento/index'),
                    array('label' => 'Calendário de Acompanhamento',
                        'url' => array("calendario/index"),
                        'active' => $actionId == 'calendario/index'),
                    array('label' => 'Ajuste de Registros',
                        'url' => array("ajuste/pedido"),
                        'active' => $actionId == 'ajuste/pedido'),
                    array('label' => 'Registro de Compensação',
                        'url' => array("compensacao/pedido"),
                        'active' => $actionId == 'compensacao/pedido',),
                    array('label' => 'Acompanhamento da Chefia',
                        'url' => array("acompanhamento/acompanhamentoChefia"),
                        'active' => (($actionId == 'acompanhamento/acompanhamentochefia') || ($actionId == 'calendario/acompanhamentochefia')),
                        'visible' => !empty($orgaosChefia)),
                    array('label' => 'Certificação de Ajustes',
                        'url' => array("ajuste/pedidosAvaliacao"),
                        'active' => $actionId == 'ajuste/pedidosavaliacao',
                        'visible' => !empty($orgaosChefia)),
                    array('label' => 'Pedidos Certificados',
                        'url' => array("ajuste/pedidosCertificados"),
                        'active' => $actionId == 'ajuste/pedidoscertificados',
                        'visible' => !empty($orgaosChefia)),
                    array('label' => 'Certificação de Compensações',
                        'url' => array("compensacao/pedidosAvaliacao"),
                        'active' => $actionId == 'compensacao/pedidosavaliacao',
                        'visible' => !empty($orgaosChefia)),
                    array('label' => 'Relatório Consolidado',
                        'url' => array("relatorio/index"),
                        'active' => Yii::app()->controller->id == 'relatorio',
                        'visible' => !empty($orgaosChefia)),
                    array('label' => 'Horário de Expediente',
                        'url' => array("horarios/horariosOrgaos"),
                        'active' => $actionId == 'horarios/horariosorgaos'),
                    array('label' => 'TUTORIAL DE USO',
                        'url' => URL_TUTORIAL,
                        'active' => false),
                    array('label' => 'Restrições de IPs',
                        'url' => array("restricao/index"),
                        'active' => $actionId == 'restricao/index',
                        //'visible' => isset(Yii::app()->session['id_pessoa']) && (Yii::app()->session['id_pessoa'] == 132034)),
                        'visible' => Yii::app()->user->checkAccess(APLICACAO_GERENCIA)),
                    array('label' => 'Gerência',
                        'url' => array("gerencia/index"),
                        'active' => $actionId == 'gerencia/index',
                        //'visible' => isset(Yii::app()->session['id_pessoa']) && (Yii::app()->session['id_pessoa'] == 132034)),
                        'visible' => Yii::app()->user->checkAccess(APLICACAO_GERENCIA)),
                    array('label' => 'Consolidação de dados',
                        'url' => array("consolida/index"),
                        'active' => Yii::app()->controller->id == 'consolida',
                        'visible' => isset(Yii::app()->session['id_pessoa']) && (Yii::app()->session['id_pessoa'] == 3)),
                ),
            );
        }
    }

    /**
     * Método utilitário para verificar se o usuário logado pode ou não bater 
     * ponto.
     * 
     * O método verifica se o identificador único do usuário passado por 
     * parâmetro corresponde a um objeto {@see Pessoa} e se o mesmo faz parte 
     * de um {@see Orgao} válido.
     * 
     * @param int $codPessoa Chave primária da classe Pessoa
     * @return boolean TRUE or FALSE
     */
    public function pessoaPodeBaterPonto($codPessoa)
    {
        $dadoFuncional = DadoFuncional::model()->find(array('id_pessoa' => $codPessoa));
        $orgaosSuperiores = implode(",", Helper::getHierarquiaAscendenteOrgao($dadoFuncional->orgao_exercicio));
        $sql = "select 
                    1
                from restricao_relogio RR 
                where 
                    (RR.matricula = :matricula
                    and RR.nr_vinculo = :nr_vinculo)
                    or RR.id_orgao in (
                        $orgaosSuperiores
                    )";
        $query = Yii::app()->db->createCommand($sql)->queryColumn(array(
            ':matricula' => $dadoFuncional->matricula,
            ':nr_vinculo' => $dadoFuncional->nr_vinculo,
        ));

        return !empty($query);
    }

    /**
     * Método para desabilitar a Yii Debug Toolbar
     */
    protected function desabilitaYiiToolbar()
    {
        foreach (Yii::app()->log->routes as $r) {
            if ($r instanceof YiiDebugToolbarRoute) {
                $r->enabled = false;
            }
        }
    }
}