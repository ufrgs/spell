<?php
class CategoriaFuncional extends CActiveRecord
{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'categoria';
	}
	
	public function primaryKey()
    {
        return array('id_categoria');
    }
    
    public function relations()
    {
        return array(
            'grupoEmprego' => array(self::BELONGS_TO, 'GrupoEmprego', 'id_grupo'),
        );
    }
				
}