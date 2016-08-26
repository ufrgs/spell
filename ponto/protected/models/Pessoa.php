<?php

class Pessoa extends CActiveRecord {

	/**
	 * Returns the static model of the specified AR class.
	 * @return Pessoa the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'pessoa';
	}

	public function primaryKey() {
		return 'id_pessoa';
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		return array(
            'DadoFuncional' => array(self::HAS_ONE, 'DadoFuncional', 'id_pessoa'),
			'DadosFuncionais' => array(self::HAS_MANY, 'DadoFuncional', 'id_pessoa'),
		);
	}
	
	public function scopes()
	{
		return array(
            'limite'=>array(
                'limit'=>10,
            ),
        );
	}	

	public function relationsEspecifico() {
		return array();
	}

	public function save($runValidation = true, $attributes = NULL) {
		throw new CException("Operação inválida.");
	}

	public function delete() {
		throw new CException("Operação inválida.");
	}
}