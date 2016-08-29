<?php

class BaseController extends CController
{
    /**
     * Menu da aplicacao. Definido em setMenu().
     * @var array
     */
    public $menu = array();

    public function filters()
    {
        return array(
            array('application.components.Sessao - Error, Login, Sair'),
            'accessControl - Error, Login, Sair',
        );
    }

    //Mais informacoes em http://www.yiiframework.com/doc/guide/1.1/en/topics.auth#access-control-filter
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
                'users' => array(132034),
                'controllers' => array('consolida'),
            ),
            array(
                'deny',
            ),
        );
    }

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

    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            $error['admin'] = 'ADMIN';
            $error['time'] = time();
            $error['version'] = '';
            $this->render('system.views.pt_br.error', array('data' => $error));
        }
    }

    private function setMenu()
    {
        #Variavel que faz o controle de qual menu esta ativo
        $actionId = Yii::app()->controller->id . '/' . Yii::app()->controller->action->id;

        if (isset(Yii::app()->session['id_pessoa'])) {
            #Monta a listagem de menus da aplicacao
            $orgaosChefia = RestricaoRelogio::getOrgaosChefia(Yii::app()->session['id_pessoa']);
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
                        'visible' => isset(Yii::app()->session['id_pessoa']) && (Yii::app()->session['id_pessoa'] == 132034)),
                ),
            );
        }
    }

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

    // Desativa o Yii Debug Toolbar
    protected function desabilitaYiiToolbar()
    {
        foreach (Yii::app()->log->routes as $r) {
            if ($r instanceof YiiDebugToolbarRoute) {
                $r->enabled = false;
            }
        }
    }

}