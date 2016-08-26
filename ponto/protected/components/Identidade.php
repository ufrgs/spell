<?php

class Identidade extends CUserIdentity implements IdentidadeInterface
{
    const SESSAO_OK = 0;
    const ERRO_SESSAO_INVALIDA = 1;
    const ERRO_PESSOA_INVALIDA = 2;

    private $_pessoa;
    private $_modelos;
    private $_nomeChave;

    public function __construct()
    {
        parent::__construct("", "");
    }

    public function defineModelos(array $modelos)
    {
        $this->_modelos = $modelos;
    }

    public function authenticate()
    {
        $sessao = Yii::app()->getSession();
        $sessao->open();

        foreach ($this->_modelos as $nomeChave => $modelo) {
            if (isset($sessao[$nomeChave]) and trim($sessao[$nomeChave]) != "") {
                $ident = $modelo::model()->findByPk($sessao[$nomeChave]);
                if (is_null($ident)) {
                    $this->errorCode = self::ERRO_PESSOA_INVALIDA;
                    return false;
                }
                else {
                    $this->_pessoa = $ident;
                    $this->_nomeChave = $nomeChave;
                    $this->username = $sessao[$nomeChave];
                    $this->errorCode = self::SESSAO_OK;
                    return true;
                }
            }
        }
        $this->errorCode = self::ERRO_SESSAO_INVALIDA;
        return false;
    }

    public function buscaPessoa()
    {
        return $this->_pessoa;
    }

}