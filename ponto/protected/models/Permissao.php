<?php

class Permissao extends CActiveRecord {

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
		return 'permissao';
	}

	public function primaryKey() {
		return array('id_aplicacao', 'id_pessoa');
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		return array(
            'Pessoa' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoa'),
		);
	}
}