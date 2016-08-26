<?php

class DadoFuncional extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'dado_funcional';
    }

    public function primaryKey()
    {
        return array('matricula', 'nr_vinculo');
    }
  
    public function relations()
    {
        return array(
            'OrgaoExercicio' => array(self::BELONGS_TO, 'Orgao', 'orgao_exercicio'),
            'OrgaoLotacao' => array(self::BELONGS_TO, 'Orgao', 'orgao_lotacao'),
            'CatFuncional' => array(self::BELONGS_TO, 'CategoriaFuncional', 'id_categoria'),
            'GrupoEmprego' => array(self::BELONGS_TO, 'GrupoEmprego', 'id_grupo'),
            'Pessoa' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoa'),
        );
    }
}