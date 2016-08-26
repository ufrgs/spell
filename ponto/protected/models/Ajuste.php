<?php

/**
 * This is the model class for table "ajuste".
 *
 * The followings are the available columns in table 'ajuste':
 * @property string $nr_ajuste
 * @property string $id_pessoa
 * @property string $matricula
 * @property string $nr_vinculo
 * @property string $data_hora_ponto
 * @property string $entrada_saida
 * @property string $id_pessoa_registro
 * @property string $data_hora_registro
 * @property string $ip_registro
 * @property string $justificativa
 * @property string $id_pessoa_certificacao
 * @property string $data_hora_certificacao
 * @property string $indicador_certificado
 * @property string $nr_ponto
 * @property string $nr_justificativa
 * @property string $justificativa_certificacao
 */
class Ajuste extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'ajuste';
    }

    public function primaryKey()
    {
        return 'nr_ajuste';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_pessoa, data_hora_ponto, entrada_saida, id_pessoa_registro, data_hora_registro, ip_registro', 'required'),
            array('id_pessoa, id_pessoa_registro, id_pessoa_certificacao', 'length', 'max' => 6),
            array('matricula', 'length', 'max' => 8),
            array('nr_vinculo, entrada_saida, indicador_certificado, indicador_excluido', 'length', 'max' => 1),
            array('ip_registro', 'length', 'max' => 39),
            array('justificativa', 'length', 'max' => 2048),
            array('justificativa_certificacao', 'length', 'max' => 512),
            array('data_hora_certificacao', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('nr_ajuste, id_pessoa, matricula, nr_vinculo, data_hora_ponto, entrada_saida, id_pessoa_registro, data_hora_registro, ip_registro, justificativa, id_pessoa_certificacao, data_hora_certificacao, indicador_certificado', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Ponto' => array(self::BELONGS_TO, 'Ponto', 'nr_ponto'),
            'Pessoa' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoa'),
            'DadoFuncional' => array(self::BELONGS_TO, 'DadoFuncional', array('matricula' => 'matricula', 'nr_vinculo' => 'nr_vinculo')),
            'JustificativaAjuste' => array(self::BELONGS_TO, 'JustificativaAjuste', 'nr_justificativa'),
            'Certificador' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoa_certificacao'),
            'Arquivos' => array(self::HAS_MANY, 'ArquivoAjuste', 'nr_ajuste'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'nr_ajuste' => 'Nr Seq Ajuste',
            'id_pessoa' => 'Cod Pessoa',
            'matricula' => 'Matricula Servidor',
            'nr_vinculo' => 'Nr Vinculo',
            'data_hora_ponto' => 'Data Hora Ponto',
            'entrada_saida' => 'Indicador Entrada Saida',
            'id_pessoa_registro' => 'Cod Pessoa Registro',
            'data_hora_registro' => 'Data Hora Registro',
            'ip_registro' => 'Ipregistro',
            'justificativa' => 'justificativa',
            'id_pessoa_certificacao' => 'Cod Pessoa Certificacao',
            'data_hora_certificacao' => 'Data Hora Certificacao',
            'indicador_certificado' => 'Indicador Certificacao',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search($dataProviderOptions = array())
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->with = array(
                'Pessoa' => array(
                    'select' => 'nome_pessoa'
                ),
                'Certificador' => array(
                    'select' => 'nome_pessoa'
                ),
                'DadoFuncional' => array(
                    'select' => 'regime_trabalho',
                ),
                'DadoFuncional.CatFuncional' => array(
                    'select' => 'nome_categoria'
                ),
                'DadoFuncional.OrgaoExercicio' => array(
                    'select' => 'sigla_orgao, nome_orgao'
                ), 'JustificativaAjuste', 'Arquivos', 'Certificador');
        $criteria->compare('t.nr_ajuste', $this->nr_ajuste, false);
        $criteria->compare('t.id_pessoa', $this->id_pessoa, false);
        $criteria->compare('t.matricula', $this->matricula, false);
        $criteria->compare('t.nr_vinculo', $this->nr_vinculo, false);
        $criteria->compare('t.indicador_certificado', $this->indicador_certificado, false);
        $criteria->compare('t.id_pessoa_certificacao', $this->id_pessoa_certificacao, false);
        $criteria->addCondition("coalesce(t.indicador_excluido, 'N') = 'N'");

        return new CActiveDataProvider($this, array_merge(array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 15,
            ),
            'sort' => array(
                'attributes' => array(
                    'nr_ajuste',
                    'id_pessoa',
                    'matricula',
                    'data_hora_ponto',
                    'entrada_saida',
                    'id_pessoa_registro',
                    'data_hora_registro',
                    'ip_registro',
                    'id_pessoa_certificacao',
                    'justificativa',
                    'data_hora_certificacao',
                    'indicador_certificado',
                    'justificativa_certificacao',
                    'nr_ponto',
                    'Pessoa.nome_pessoa' => array(
                        'asc' => 'Pessoa.nome_pessoa asc',
                        SORT_ASC => 'Pessoa.nome_pessoa asc',
                        'desc' => 'Pessoa.nome_pessoa desc',
                        SORT_DESC => 'Pessoa.nome_pessoa desc',
                    ),
                    'Certificador.nome_pessoa' => array(
                        'asc' => 'Certificador.nome_pessoa asc',
                        SORT_ASC => 'Certificador.nome_pessoa asc',
                        'desc' => 'Certificador.nome_pessoa desc',
                        SORT_DESC => 'Certificador.nome_pessoa desc',
                    ),
                ),
                'defaultOrder' => array(
                    'data_hora_registro' => CSort::SORT_DESC,
                ),
            ),
        ), $dataProviderOptions));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Ajuste the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function listaAnexos()
    {
        $listagem = "";
        $repositorio = new Repositorio;
        if (!empty($this->Arquivos)) {
            foreach ($this->Arquivos as $arquivo) {
                //$listagem .= CHtml::link($arquivo->descricao_arquivo, Yii::app()->createUrl("ajuste/anexo", array('arquivo' => $arquivo->nr_arquivo_ajuste)), array('target' => '_blank')).'<br/>';
                $link = $repositorio->devolveLinkExibicao(92, $arquivo->cod_repositorio);
                $listagem .= CHtml::link($arquivo->descricao_arquivo, $link, array('target' => '_blank')).'<br/>';
            }
        }
        else {
            $listagem = 'sem anexos';
        }
        return $listagem;
    }

}