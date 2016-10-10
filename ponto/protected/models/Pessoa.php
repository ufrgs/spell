<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Modelo criado para representar a tabela pessoa
 * 
 * Aqui são implementados os métodos básicos do Yii Framework para realizar o 
 * mapeamento das entidades do banco de dados relacional no paradigma de objetos.
 * 
 * Além de tais operações, geralmente são implementados recursos a mais para
 * reduzir a quantidade de queries e operações repetititvas nos controladores.
 * 
 * @property int $id_pessoa Chave primária da classe Pessoa
 * @property string $nome_pessoa Nome da pessoa
 * @property email $email Endereço de e-mail da pessoa
 * @property char $tipo_foto Formato do arquivo da foto da pessoa
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage models
 * @version v1.0
 * @since v1.0
 */
class Pessoa extends CActiveRecord {

	/**
     * Método do Yii Framework para retornar a instância da classe
     * 
     * Esse método deve ser implementado em todas as classe {@see CActiveRecord}
     * para permitir que o framework encontre a classe.
     * 
     * @param string $className Nome da classe que é Active Record.
     * @return Pessoa A classe que é Active Record
     */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
     * Método do Yii Framework para definição da tabela associada ao objeto
     * 
     * A string retornada define para o Yii qual tabela contém os registros a
     * serem mapeados para essa classe.
     * 
     * @return string Nome da tabela no banco de dados associada ao objeto
     */
	public function tableName() {
		return 'pessoa';
	}

    /**
     * Método do Yii Framework para definição da chave primária do objeto
     * 
     * A string retornada indica a coluna contendo o identificador único do 
     * objetos.
     * 
     * @return string Nome da coluna referente à chave primária do objeto
     */
	public function primaryKey() {
		return 'id_pessoa';
	}

	/**
     * Método do Yii Framework para definição de relacionamentos entre tabelas
     * 
     * Aqui são definidos as tabelas, os tipos de relação e as colunas que as 
     * possuem.
     * 
     * @link http://www.yiiframework.com/doc/guide/1.1/en/database.arr#declaring-relationship Como declarar relacionamentos
     * @return array Relacionamentos que esta tabela possui
     */
	public function relations() {
		return array(
            'DadoFuncional' => array(self::HAS_ONE, 'DadoFuncional', 'id_pessoa'),
			'DadosFuncionais' => array(self::HAS_MANY, 'DadoFuncional', 'id_pessoa'),
		);
	}
	
    /**
     * Método do Yii Framework para definição de regras de validação
     * 
     * Aqui são definidos os atributos das colunas da tabela que presenta o 
     * objeto como os campos que aceitam valores nulos e tamanho máximo de 
     * caracteres suportados.
     * 
     * É recomendado apenas definir as regras para os atributos que forem ser 
     * utilizados com dados do usuário.
     * 
     * @link http://www.yiiframework.com/doc/guide/1.1/en/form.model#declaring-validation-rules Como declarar regras
     * @return array Regras de validação para este modelo
     */
    public function rules()
    {
        return array(
            array('id_pessoa, nome_pessoa, email', 'required'),
            array('id_categoria', 'length', 'max' => 6),
            array('nome_pessoa', 'length', 'max' => 150),
            array('tipo_foto', 'length', 'max' => 4)
        );
    }
    
    /**
     * Método do Yii Framework para definição de escopos
     * 
     * Esse método é utilizado para definir condições a serem aplicadas nas 
     * pesquisas. A implementação atual define a quantidade padrão de pessoas
     * a serem retornadas por pesquisa usando o comando SQL LIMIT.
     * 
     * @link http://www.yiiframework.com/doc/guide/1.1/en/database.ar#named-scopes Como declarar escopos
     * @return array Lista de escopos definidos
     */
	public function scopes()
	{
		return array(
            'limite' => array(
                'limit' => 10,
            ),
        );
    }

    /**
     * Método do Yii Framework para persistir Active Records
     * 
     * A sobrescrita do método nessa classe é utilizada para impedir que uma
     * instância dessa classe seja persistida pela aplicação.
     * 
     * @param boolean $runValidation Variável para indicar se o modelo precisa ser validado antes de ser persistido
     * @param array $attributes Atributos extras para serem persistidos
     * @throws CException
     */
	public function save($runValidation = true, $attributes = NULL) {
		throw new CException("Operação inválida.");
	}

    /**
     * Método do Yii Framework para persistir Active Records
     * 
     * A sobrescrita do método nessa classe é utilizada para impedir que uma
     * instância dessa classe seja removida do banco de dados pela aplicação.
     * 
     * @throws CException
     */
	public function delete() {
		throw new CException("Operação inválida.");
	}
}