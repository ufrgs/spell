<?php

class UsuarioWeb extends CWebUser
{
    public $guestName = 'Visitante';
    private $pessoa = null;

    public function login($identidade, $duration = 0)
    {
        $this->pessoa = $identidade->buscaPessoa();
        $this->setState('__dadosPessoa', serialize($this->pessoa));
        parent::login($identidade, 0);
    }

    /*
      Reescreve funcao changeIdentity para nao regenerar sessao
      quando a sessao e regenerada, alguns usuarios sao desconectados
     */

    protected function changeIdentity($id, $name, $states)
    {
        //Yii::app()->getSession()->regenerateID(true);
        $this->setId($id);
        $this->setName($name);
        $this->loadIdentityStates($states);
    }

    public function getPessoa()
    {
        if (is_null($this->pessoa)) {
            if ($this->hasState('__dadosPessoa'))
                $this->pessoa = unserialize($this->getState('__dadosPessoa'));
            else
                return null;
        }
        return $this->pessoa;
    }

    public function __get($name)
    {
        $pessoa = $this->getPessoa();

        if (!is_null($pessoa) && $pessoa->hasAttribute($name))
            return $pessoa->$name;
        else
            return parent::__get($name);
    }

    public function checkAccess($operation, $params = array(), $allowCaching = true)
    {
        $regra = explode(" ", $operation);
        if (!isset($regra[1])) {
            return Yii::app()->seguranca->carregaAplicacao($operation)->direitoEntrada();
        }
        else {
            switch ($regra[1]) {
                case "entrada":
                    return Yii::app()->seguranca->carregaAplicacao($regra[0])->direitoEntrada();
                case "novo":
                    return Yii::app()->seguranca->carregaAplicacao($regra[0])->direitoNovo();
                case "salvar":
                    return Yii::app()->seguranca->carregaAplicacao($regra[0])->direitoSalvar();
                case "excluir":
                    return Yii::app()->seguranca->carregaAplicacao($regra[0])->direitoExcluir();
            }
            return false;
        }
    }

}