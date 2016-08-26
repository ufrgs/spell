<?php

/* 
    Document   : TempLoginController
    Created on : 16/08/2016, 17:37:24
    Author     : thiago
*/

class TempLoginController extends BaseController
{
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
            Yii::app()->session['id_pessoa'] = $usuario;
            $this->redirect('/ponto/acompanhamento/index');
        }
        else if (isset(Yii::app()->session['id_pessoa'])) {
            $this->redirect('/ponto/acompanhamento/index');
        }
        else {
            $this->render('login');
        }
    }

    public function actionSair()
    {
        Yii::app()->session->destroy();
        Yii::app()->session->close();
        $this->redirect('login');
    }
}