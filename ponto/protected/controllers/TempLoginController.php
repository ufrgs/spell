<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Controlador utilizado para gerenciar o acesso ao sistema.
 * 
 * Aqui são definidas as actions para permitir o login e logout no sistema.
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage controllers
 * @version v1.0
 * @since v1.0
 */
class TempLoginController extends BaseController
{

    /**
     * Action utlizada para permitir o login do usuário.
     * 
     * Esse método permite o login nos ambientes de teste e produção e tem três
     * comportamentos: 
     * 
     * - Exibir a tela de autenticação para um usuário não logado
     * - Realizar o login de um usuário
     * - Redirecionar um usuário logado para a tela de acompanhamento de horários
     * 
     * @todo Testar o login em ambiente de produção
     * @todo Registrar erro de login em arquivo de LOG
     * @todo Registrar sucesso em fazer login
     */
    public function actionLogin()
    {
        if (isset($_POST['usuario'])) {
            $usuario = intval($_POST['usuario']) % 1000000; // Usuário é numérico com no máximo 6 digitos
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
            Yii::app()->session['id_pessoa'] = $usuario;
            $this->redirect('/ponto/acompanhamento/index');
        } else if (isset(Yii::app()->session['id_pessoa'])) {
            $this->redirect('/ponto/acompanhamento/index');
        } else {
            $this->render('login');
        }
    }

    /**
     * Action utlizada para realizar o logout do usuário.
     * 
     * A sessão do usuário é destruída e o mesmo é redirecionado para a página 
     * de login.
     */
    public function actionSair()
    {
        Yii::app()->session->destroy();
        Yii::app()->session->close();
        $this->redirect('login');
    }
}
