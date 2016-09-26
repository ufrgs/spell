<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Classe utilizada para autenticação dos usuários.
 * 
 * Essa classe é uma derivação da classe {@see CUserIdentity} do Yii Framework, 
 * classe responsável por definir os comportamentos de usuários via autenticação
 * básica (usuário e senha). Aqui esse mecanismo padrão provido pelo framework é 
 * adaptado para as necessidades do sistema.
 * 
 * Esse participante também implementa a inerface {@see IdentidadeInterface} para
 * ser capaz de prover uma instância da classe {@see Pessoa} referente aos dados
 * do usuário autenticado.
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage components
 * @version v1.0
 * @since v1.0
 */
class Identidade extends CUserIdentity implements IdentidadeInterface
{

    const SESSAO_OK = 0;
    const ERRO_SESSAO_INVALIDA = 1;
    const ERRO_PESSOA_INVALIDA = 2;

    /**
     * Objeto contendo os dados da pessoa autenticada
     * 
     * @var Pessoa
     */
    private $_pessoa;

    /**
     * Lista indicando as classes que representam os usuários
     * 
     * @var array
     */
    private $_modelos;

    /**
     * Nome da chave primária correspondente ao id da pessoa
     * 
     * @var string
     */
    private $_nomeChave;

    /**
     * Contrutor da classe Identidade.
     * 
     * Método sobrecarregado herdado da classe {@see CUserIdentity} para passar
     * vazios os parâmetros de usuário e senha.
     * 
     * @ignore
     */
    public function __construct()
    {
        parent::__construct("", "");
    }

    /**
     * Método autilizar para definição do atributo $_modelos.
     * 
     * Esse método é utilizado pela classe {@see Sessao} para definir os modelos
     * que representam o usuário no sistema.
     * 
     * @param array $modelos
     */
    public function defineModelos(array $modelos)
    {
        $this->_modelos = $modelos;
    }

    /**
     * Método para controlar o acesso do usuário.
     * 
     * O método verifica se o mesmo tem uma sessão válida. Em caso negativo uma
     * sessão tentará ser criada a partir da chave primária correspondente ao 
     * id da pessoa.
     * 
     * @return boolean TRUE para usuário com sessão válida e false para inválida
     */
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
                } else {
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

    /**
     * Método sobrescrito da interface {@see IdentidadeInterface}.
     * 
     * É utilizado para retornar uma instância da classe Pessoa contendo os 
     * dados do usuário dono da sessão definido no método authenticate().
     * 
     * @return Pessoa Instância da classe Pessoa correspondente ao usuário da sessão
     */
    public function buscaPessoa()
    {
        return $this->_pessoa;
    }
}
