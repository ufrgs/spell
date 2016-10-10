<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Modelo criado para representar a tabela frequencia
 * 
 * Aqui são implementados os métodos básicos do Yii Framework para realizar o 
 * mapeamento das entidades do banco de dados relacional no paradigma de objetos.
 * 
 * Além de tais operações, geralmente são implementados recursos a mais para
 * reduzir a quantidade de queries e operações repetititvas nos controladores.
 * 
 * @property int $nr_frequencia Chave primária da classe Frequencia
 * @property int $matricula Chave primária da classe DadoFuncional
 * @property int $nr_vinculo Chave primária da classe DadoFuncional
 * @property int $nr_dias Quantidade de dias trabalhados
 * @property DateTime $data_frequencia Data da frequência registrada no padrão AAAA-MM-DD HH:MM:SS
 * @property DateTime $data_fim_frequencia Data da frequência registrada no padrão AAAA-MM-DD HH:MM:SS
 * @property int $cod_frequencia Cópia da chave primária nr_frequencia
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage models
 * @version v1.0
 * @since v1.0
 */
class Frequencia extends CActiveRecord {

	/**
     * Método do Yii Framework para retornar a instância da classe
     * 
     * Esse método deve ser implementado em todas as classe {@see CActiveRecord}
     * para permitir que o framework encontre a classe.
     * 
     * @param string $className Nome da classe que é Active Record.
     * @return Frequencia A classe que é Active Record
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
		return 'frequencia';
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
		return 'nr_frequencia';
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
            'DadoFuncional' => array(self::BELONGS_TO, 'DadoFuncional', array('matricula' => 'id_pessoa', 'nr_vinculo' => 'nr_vinculo')),
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
            array('nr_frequencia, matricula, nr_vinculo, nr_dias, data_frequencia, cod_frequencia', 'required'),
            array('nr_frequencia, matricula', 'length', 'max' => 8),
            array('nr_vinculo', 'length', 'max' => 1),
            array('nr_dias', 'length', 'max' => 11),
            array('cod_frequencia', 'length', 'max' => 3)
        );
    }
}