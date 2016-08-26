<?php

/**
 * This is the model class for table "restricao_relogio".
 *
 * The followings are the available columns in table 'restricao_relogio':
 * @property string $nr_restricao
 * @property string $id_orgao
 * @property string $escopo
 * @property string $id_pessoa
 * @property string $mascara_ip_v4
 * @property string $mascara_ip_v6
 * @property string $data_atualizacao
 * @property string $id_pessoa_atualizacao
 * @property string $ip_atualizacao
 */
class RestricaoRelogio extends CActiveRecord
{
    public $inOrgaos = NULL, $inLotacao = NULL, $porOrgao = true;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'restricao_relogio';
    }

    public function primaryKey()
    {
        return 'nr_restricao';
    }
    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('data_atualizacao, id_pessoa_atualizacao, ip_atualizacao', 'required'),
            array('id_orgao', 'length', 'max' => 5),
            array('escopo', 'length', 'max' => 1),
            array('id_pessoa, id_pessoa_atualizacao', 'length', 'max' => 6),
            array('mascara_ip_v4', 'length', 'max' => 18),
            array('mascara_ip_v6', 'length', 'max' => 45),
            array('ip_atualizacao', 'length', 'max' => 39),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('nr_restricao, id_orgao, escopo, id_pessoa, mascara_ip_v4, mascara_ip_v6, data_atualizacao, id_pessoa_atualizacao, ip_atualizacao', 'safe', 'on' => 'search'),
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
            'Orgao' => array(self::BELONGS_TO, 'Orgao', 'id_orgao'),
            'Pessoa' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoa'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'nr_restricao' => 'Nr Seq Restricao',
            'id_orgao' => 'Cod Orgao',
            'escopo' => 'escopo',
            'id_pessoa' => 'Cod Pessoa',
            'mascara_ip_v4' => 'Mascara Ipv4',
            'mascara_ip_v6' => 'Mascara Ipv6',
            'data_atualizacao' => 'Data Hora Ultima Atualizacao',
            'id_pessoa_atualizacao' => 'Cod Pessoa Ultima Atualizacao',
            'ip_atualizacao' => 'Ipultima Atualizacao',
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        
        $criteria->with = array('Orgao', 'Pessoa');

        if ($this->inOrgaos != NULL) {
            $criteria->addInCondition('t.id_orgao', $this->inOrgaos);
        }
        if ($this->inLotacao != NULL) {
            $criteria->addCondition("t.id_pessoa in (
                select S.id_pessoa 
                from SERVIDOR S
                    join dado_funcional D on S.matricula = D.matricula
                where
                    D.coalesce(D.data_desligamento, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP() 
                    and coalesce(D.data_aposentadoria, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP()
                    and D.orgao_exercicio in ".implode(',', $this->inLotacao)."
            )");
        }

        if ($this->porOrgao) {
            $criteria->addCondition('t.id_orgao is not null');
        }
        else {
            $criteria->addCondition('t.id_pessoa is not null');
        }
        
        $criteria->compare('nr_restricao', $this->nr_restricao, false);
        $criteria->compare('id_orgao', $this->id_orgao, false);
        $criteria->compare('escopo', $this->escopo, true);
        $criteria->compare('id_pessoa', $this->id_pessoa, false);
        $criteria->compare('mascara_ip_v4', $this->mascara_ip_v4, true);
        $criteria->compare('mascara_ip_v6', $this->mascara_ip_v6, true);
        $criteria->compare('data_atualizacao', $this->data_atualizacao, true);
        $criteria->compare('id_pessoa_atualizacao', $this->id_pessoa_atualizacao, false);
        $criteria->compare('ip_atualizacao', $this->ip_atualizacao, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20
            ),
            'sort' => array(
                'attributes' => array(
                    'mascara_ip_v4',
                    'mascara_ip_v6',
                    'nome_orgao' => array(
                        'asc' => 'Orgao.sigla_orgao asc',
                        SORT_ASC => 'Orgao.sigla_orgao asc',
                        'desc' => 'Orgao.sigla_orgao desc',
                        SORT_DESC => 'Orgao.sigla_orgao desc',
                    )
                ),
                'defaultOrder' => array(
                    'nome_orgao' => SORT_ASC,
                )
            ),
        ));
    }
    
    public static function getOrgaosChefia($codPessoaServidor) {
        return Orgao::model()->with('DirigenteOrgao', 'DirigenteSubstituto')->findAll('DirigenteOrgao.id_pessoa = :id_pessoa or DirigenteSubstituto.id_pessoa = :id_pessoa2 ', array(
            ':id_pessoa' => $codPessoaServidor,
            ':id_pessoa2' => $codPessoaServidor,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RestricaoRelogio the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Verifica se uma determinada pessoa pode fazer registro de ponto no IP
     * Somente IP v4 por enquanto
     * @param int $id_pessoa
     * @param int $matricula
     * @param int $nr_vinculo
     * @param string $ip
     */
    public static function verificaLiberacaoPonto($id_pessoa, $matricula, $nr_vinculo, $ip)
    {
        // verifica se não tem afastamento no dia
        $afastamento = Frequencia::model()->findAll(
            'matricula = :matricula AND nr_vinculo = :nr_vinculo 
            AND CURRENT_TIMESTAMP() BETWEEN data_frequencia AND data_fim_frequencia', array(
                ':matricula' => $matricula,
                ':nr_vinculo' => $nr_vinculo,
            )
        );
        if (!empty($afastamento)) {
            return array(
                'libera' => false,
                'mensagem' => 'Existe um afastamento registrado para esse dia.'
            );
        }
        // verifica se a pessoa pode fazer registro no IP
        $dadoFuncional = DadoFuncional::model()->find('matricula = :matricula AND nr_vinculo = :nr_vinculo', array(
            ':matricula' => $matricula,
            ':nr_vinculo' => $nr_vinculo,
        ));
        $orgaosEmQueServidorPodeBaterPonto = Helper::getHierarquiaAscendenteOrgao($dadoFuncional->orgao_exercicio);
        $mascarasEmQueServidorPodeBaterPonto = RestricaoRelogio::model()->findAll(
            '(matricula = :matricula AND nr_vinculo = :nr_vinculo) OR id_orgao IN (:str_orgaos)', array(
                ':matricula' => $matricula,
                ':nr_vinculo' => $nr_vinculo,
                ':str_orgaos' => implode(',', $orgaosEmQueServidorPodeBaterPonto),
            )
        );
        foreach ($mascarasEmQueServidorPodeBaterPonto as $mascara) {
            if (Helper::ip_match($ip, $mascara->mascara_ip_v4)) {
                return array(
                    'libera' => true,
                    'mensagem' => ''
                );
            }
        }
        return array(
            'libera' => false,
            'mensagem' => 'Local de registro não habilitado.'
        );
    }
}