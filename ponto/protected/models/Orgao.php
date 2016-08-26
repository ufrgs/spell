<?php
class Orgao extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return ORGAO the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'Orgao';
    }

    public function primaryKey()
    {
        return 'id_orgao';
    }

    public function relations()
    {
        return array(
            'DirigenteOrgao'  => array(self::BELONGS_TO, 'DadoFuncional', 'matricula_dirigente'),
			'DirigenteSubstituto'  =>array(self::BELONGS_TO, 'DadoFuncional', 'matricula_substituto'),
        );
    }
}
