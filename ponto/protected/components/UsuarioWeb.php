<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Derivação da classe CWebUser para controle de autenticação.
 * 
 * Aqui são definidos os métodos para verificação de permissões, login e controle
 * de sessão.
 * 
 * Essa classe sobrescreve alguns métodos da classe para permitir a autenticação
 * utilizando dados diferentes para autenticação básica.
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage components
 * @version v1.0
 * @since v1.0
 */
class UsuarioWeb extends CWebUser
{

    /**
     * Atributo contendo o nome correspondente ao usuário da sessão
     * 
     * @var string 
     */
    public $guestName = 'Visitante';

    /**
     * Instância da classe Pessoa correspondente ao usuário da sessão
     * 
     * @var Pessoa 
     */
    private $pessoa = null;

    /**
     * Implementação da funcionalidade de login no sistema.
     * 
     * Esse método sobrescreve o comportamênto do método da superclasse para
     * que os dados de identidade do usuário sejam armazenados na sessão.
     * 
     * @param IdentidadeInterface $identidade Implementação da interface Identidade
     * @param int $duration Tempo de duração da sessão
     */
    public function login($identidade, $duration = 0)
    {
        $this->pessoa = $identidade->buscaPessoa();
        $this->setState('__dadosPessoa', serialize($this->pessoa));
        parent::login($identidade, 0);
    }

    /**
     * Método utilizado para controlar a sessão do usuário
     * 
     * Esse método é definido pelo Yii Framework na superclasse para regenerar 
     * a sessão do usuário. Porém esse comportamento acaba por desconectar 
     * alguns usuários logados. A implementação atual corrige esse problema.
     * 
     * @param int|null $id Chave primária da classe Pessoa. É usado NULL em caso de visitante
     * @param string $name Nome do usuário
     * @param array $states Dados do usuário armazenados na sessão
     */
    protected function changeIdentity($id, $name, $states)
    {
        $this->setId($id);
        $this->setName($name);
        $this->loadIdentityStates($states);
    }

    /**
     * Método para retornar os dados de uma pessoa autenticada.
     * 
     * É feita a descerialização do objeto contido na sessão __dadosPessoa e
     * retornada em forma de objeto.
     * 
     * @return Pessoa|null Instância contendo os dados da pessoa autenticada
     */
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

    /**
     * Método mágico utilizado para buscar o valor de atributo da pessoa.
     * 
     * O método recebe o nome de um atributo da classe {@see Pessoa} e busca o 
     * valor correspondente a esse atributo nos dados da pessoa autenticada.
     * 
     * @param string $name Nome da coluna corresponde a informação desejada
     * @return string Valor contido no campo informado.
     */
    public function __get($name)
    {
        $pessoa = $this->getPessoa();

        if (!is_null($pessoa) && $pessoa->hasAttribute($name)) {
            return $pessoa->$name;
        }

        return parent::__get($name);
    }

    /**
     * Método utilizado para controle de acesso à operações.
     * 
     * Esse método faz a filtragem de ações do usuário no sistema. Antes de 
     * executar a ação solicitada o método verifica se o usuário possui ou não
     * uma regra de acesso impedindo a operação.
     * 
     * As operações podem ser: entrada, novo, salvar e excluir.
     * 
     * @param string $operation Palavra contendo a ação que o usuário solicitou
     * @param array $params Lista de valores necessário para a ação.
     * @param boolean $allowCaching Indicador do uso de cache.
     * @return boolean Indicador de permissão para a execução. Retorna FALSE em caso de erro.
     */
    public function checkAccess($operation, $params = array(), $allowCaching = true)
    {
        $regra = explode(" ", $operation);
        if (!isset($regra[1])) {
            return Yii::app()->seguranca->carregaAplicacao($operation)->direitoEntrada();
        } else {
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
