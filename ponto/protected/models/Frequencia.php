<?php

class Frequencia extends CActiveRecord {

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
		return 'frequencia';
	}

	public function primaryKey() {
		return 'nr_frequencia';
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		return array(
            'DadoFuncional' => array(self::BELONGS_TO, 'DadoFuncional', array('matricula' => 'id_pessoa', 'nr_vinculo' => 'nr_vinculo')),
		);
	}
}