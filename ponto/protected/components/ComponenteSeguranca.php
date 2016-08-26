<?php

class ComponenteSeguranca extends CApplicationComponent
{
    private $id_pessoa = null;
    private $id_aplicacao = null;
    private $entrada, $novo, $salvar, $excluir;
    public $orgaos = array();
    public $permissao = array();

    public function init()
    {
        parent::init();

        $sessao = Yii::app()->getSession();
        $sessao->open();

        if (!Yii::app()->user->getIsGuest()) {
            if (isset($sessao['id_pessoa'])) {
                $this->carregaPessoa(Yii::app()->user->id_pessoa);
            }
        }
        else if (isset($sessao['id_pessoa'])) {
                $this->carregaPessoa($sessao['id_pessoa']);
        }
    }

    public function carregaPessoa($id_pessoa)
    {
        $this->id_pessoa = (int) $id_pessoa;
        $this->id_aplicacao = $this->orgaos = null;
        $this->entrada = $this->novo = $this->salvar = $this->excluir = false;
    }

    public function carregaAplicacao($id_aplicacao)
    {
        if ($this->id_aplicacao != $id_aplicacao) {
            $this->id_aplicacao = trim($id_aplicacao);

            $permissao = Permissao::model()->find('id_pessoa = :id_pessoa AND id_aplicacao = :id_aplicacao AND data_expiracao IS NULL', array(
                ':id_pessoa' => $this->id_pessoa,
                ':id_aplicacao' => $this->id_aplicacao,
            ));
            // permissao simplificada para somente um nivel
            if (!empty($permissao)) {
                $this->entrada = $this->novo = $this->salvar = $this->excluir = true;
                $this->orgaos = Helper::getHierarquiaDescendenteOrgao($permissao->id_orgao);
            }
        }

        return $this;
    }

    public function actionAplicacao($actionId)
    {
        foreach ($this->permissao as $codAplicacao => $actions) {
            if (in_array($actionId, $actions) || $actions[0] == '*') {
                if ($codAplicacao == '*')
                    return true;
                return $this->carregaAplicacao($codAplicacao)->direitoEntrada();
            }
        }
        return false;
    }

    public function direitoEntrada()
    {
        if (is_null($this->id_aplicacao))
            throw new Exception("Aplicação não carregada");
        return $this->entrada;
    }

    public function direitoNovo()
    {
        if (is_null($this->id_aplicacao))
            throw new Exception("Aplicação não carregada");
        return $this->novo;
    }

    public function direitoSalvar()
    {
        if (is_null($this->id_aplicacao))
            throw new Exception("Aplicação não carregada");
        return $this->salvar;
    }

    public function direitoExcluir()
    {
        if (is_null($this->id_aplicacao))
            throw new Exception("Aplicação não carregada");
        return $this->excluir;
    }

    public function orgaosEscopo()
    {
        if (is_null($this->id_aplicacao))
            throw new Exception("Aplicação não carregada");

        return $this->orgaos;
    }
}
