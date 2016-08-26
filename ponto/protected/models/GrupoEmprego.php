<?php
class GrupoEmprego extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'grupo_emprego';
    }

    public function primaryKey()
    {
        return array('id_grupo');
    }
}
