<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Componente para configuração da segurança da aplicação
 * 
 * Aqui são definidas as permissões de acesso dos usuários em realaçao a algumas
 * operações que podem ser feitas no sistema, como salvar ou apagar. Além do
 * tratamento dos comportamentos do usuário, também é definido as operações que
 * a aplicação pode ou não executar.
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage components
 * @version v1.0
 * @since v1.0
 */
class ComponenteSeguranca extends CApplicationComponent
{

    /**
     * Chave primária da classe Pessoa
     * 
     * @var int 
     */
    private $id_pessoa = null;

    /**
     * Chave primária da aplicação.
     * 
     * @var int 
     */
    private $id_aplicacao = null;

    /**
     * Comportamentos do usuário no sistema
     * 
     * @var boolean 
     */
    private $entrada, $novo, $salvar, $excluir;

    /**
     * Lista de órgãos associados ao usuário. Atualmente é um atributo não utilizado
     * 
     * @var array 
     */
    public $orgaos = array();

    /**
     * Lista de permissões disponíveis na aplicação
     * 
     * @var array 
     */
    public $permissao = array();

    /**
     * Método do Yii Framework para inicialização do componente
     * 
     * Aqui o componente é inicializado e o mesmo passa a executar sua ação no
     * sistema, que é de definir as permissões do usuário.
     */
    public function init()
    {
        parent::init();

        $sessao = Yii::app()->getSession();
        $sessao->open();

        if (!Yii::app()->user->getIsGuest()) {
            if (isset($sessao['id_pessoa'])) {
                $this->carregaPessoa(Yii::app()->user->id_pessoa);
            }
        } else if (isset($sessao['id_pessoa'])) {
            $this->carregaPessoa($sessao['id_pessoa']);
        }
    }

    /**
     * Método para carregamento do usuário
     * 
     * Esse método define as permissões do usuário.
     * 
     * @param int $id_pessoa Chave primária da classe Pessoa
     */
    public function carregaPessoa($id_pessoa)
    {
        $this->id_pessoa = (int) $id_pessoa;
        $this->id_aplicacao = $this->orgaos = null;
        $this->entrada = $this->novo = $this->salvar = $this->excluir = false;
    }

    /**
     * Método para carregamento das regras da aplicação.
     * 
     * Aqui é feita uma verificação para garantir que o usuário está acessando
     * uma aplicação que foi lhe dado acesso.
     * 
     * @param int $id_aplicacao Chave primária da aplicação
     * @return ComponenteSeguranca
     */
    public function carregaAplicacao($id_aplicacao)
    {
        if ($this->id_aplicacao != $id_aplicacao) {
            $this->id_aplicacao = trim($id_aplicacao);

            $permissao = Permissao::model()->find('id_pessoa = :id_pessoa AND id_aplicacao = :id_aplicacao AND data_expiracao IS NULL', array(
                ':id_pessoa' => $this->id_pessoa,
                ':id_aplicacao' => $this->id_aplicacao,
            ));

            // Permissão simplificada para somente um nível
            if (!empty($permissao)) {
                $this->entrada = $this->novo = $this->salvar = $this->excluir = true;
                $this->orgaos = Helper::getHierarquiaDescendenteOrgao($permissao->id_orgao);
            }
        }

        return $this;
    }

    /**
     * Método para carregamento da aplicação
     * 
     * Aqui é feita a verificação de permissão dos usuários e é iniciada a 
     * aplicação já com suas régras configuradas.
     * 
     * A permissão "*" (asterísco) indica que o usuário logado tem acesso a todas
     * as ações. Caso não tenha esse valor o método verifica todos os itens do 
     * atributo permissão e define as regras especificamente para o usuário logado.
     * 
     * Se o usuário tem permissão * o método retornará TRUE. Caso não tenha 
     * nenhuma permissão válida será retornado FALSE.
     * 
     * Caso seja necessária regras mais específicas, o método retornará a 
     * permissão definida pelo método 
     * <code>ComponenteSeguranca::direitoEntrada()</code>.
     * 
     * @param int $actionId Chave primária da action que chamou o método
     * @return boolean|ComponenteSeguranca Retorna TRUE em caso todas
     */
    public function actionAplicacao($actionId)
    {
        foreach ($this->permissao as $codAplicacao => $actions) {
            if (in_array($actionId, $actions) || $actions[0] == '*') {
                if ($codAplicacao == '*') {
                    return true;
                }

                return $this->carregaAplicacao($codAplicacao)->direitoEntrada();
            }
        }

        return false;
    }

    /**
     * Método para definição de permissão
     * 
     * Permite ao usuário ter acesso a entrar no sistema. Caso o atributo 
     * id_aplicação seja null, uma excessão é mostrada na tela indicando que a
     * aplicação não foi inicializada corretamente.
     * 
     * @return boolean Retorna TRUE para indicar permissão de entrada
     * @throws Exception
     */
    public function direitoEntrada()
    {
        if (is_null($this->id_aplicacao)) {
            throw new Exception("Aplicação não carregada");
        }

        return $this->entrada;
    }

    /**
     * Método para definição de permissão
     * 
     * Permite ao usuário ter acesso a criar novos itens no sistema. Caso o 
     * atributo id_aplicação seja null, uma excessão é mostrada na tela 
     * indicando que a aplicação não foi inicializada corretamente.
     * 
     * @return boolean Retorna TRUE para indicar permissão de criação
     * @throws Exception
     */
    public function direitoNovo()
    {
        if (is_null($this->id_aplicacao)) {
            throw new Exception("Aplicação não carregada");
        }

        return $this->novo;
    }

    /**
     * Método para definição de permissão
     * 
     * Permite ao usuário ter acesso a salvar alterações nos itens no sistema. 
     * Caso o atributo id_aplicação seja null, uma excessão é mostrada na tela 
     * indicando que a aplicação não foi inicializada corretamente.
     * 
     * @return boolean Retorna TRUE para indicar permissão de alteração
     * @throws Exception
     */
    public function direitoSalvar()
    {
        if (is_null($this->id_aplicacao)) {
            throw new Exception("Aplicação não carregada");
        }

        return $this->salvar;
    }

    /**
     * Método para definição de permissão
     * 
     * Permite ao usuário ter acesso a exclusão de itens no sistema. Caso o 
     * atributo id_aplicação seja null, uma excessão é mostrada na tela indicando 
     * que a aplicação não foi inicializada corretamente.
     * 
     * @return boolean Retorna TRUE para indicar permissão de exclusão
     * @throws Exception
     */
    public function direitoExcluir()
    {
        if (is_null($this->id_aplicacao)) {
            throw new Exception("Aplicação não carregada");
        }

        return $this->excluir;
    }

    /**
     * Método para definição de permissão
     * 
     * @return boolean Retorna TRUE para indicar que há órgãos válidos
     * @throws Exception
     */
    public function orgaosEscopo()
    {
        if (is_null($this->id_aplicacao)) {
            throw new Exception("Aplicação não carregada");
        }

        return $this->orgaos;
    }
}
