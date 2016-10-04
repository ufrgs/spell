<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Modelo criado para representar a tabela restricao_relogio
 * 
 * Aqui são implementados os métodos básicos do Yii Framework para realizar o 
 * mapeamento das entidades do banco de dados relacional no paradigma de objetos.
 * 
 * Além de tais operações, geralmente são implementados recursos a mais para
 * reduzir a quantidade de queries e operações repetititvas nos controladores.
 * 
 * @property int $nr_restricao Chave primária da classe Restricao
 * @property int $id_orgao Chave primária da classe Orgao
 * @property char $escopo Descrição referente ao escopo da restrição
 * @property int $id_pessoa Chave primária da classe Pessoa
 * @property string $mascara_ip_v4 Endereço de IPv4 da máquina que contém a restrição
 * @property string $mascara_ip_v6 Endereço de IPv6 da máquina que contém a restrição
 * @property string $data_atualizacao Data da última atualização na restrição
 * @property string $id_pessoa_atualizacao Identificador do usuário que fez a atualização
 * @property string $ip_atualizacao Endereço de IP do usuário que fez a atualização
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage models
 * @version v1.0
 * @since v1.0
 */
class RestricaoRelogio extends CActiveRecord
{
    /**
     * Valor opcional a ser usado nas consultas utilizando o comando SQL IN.
     * 
     * @var int
     */
    public $inOrgaos = NULL;

    /**
     * Valor opcional a ser usado nas consultas utilizando o comando SQL IN.
     * 
     * @var int
     */
    public $inLotacao = NULL;

    /**
     * Indicador utilizado para habilitar a pesquisa por órgãos.
     * 
     * @var boolean
     */
    public $porOrgao = true;

    /**
     * Atributo fictício para ser utilizado nos filtros da tabela.
     * 
     * @var boolean
     */
    public $sigla_orgao = NULL;
    
    /**
     * Atributo fictício para ser utilizado nos filtros da tabela.
     * 
     * @var boolean
     */
    public $nome_pessoa = NULL;
    
    /**
     * Método do Yii Framework para definição da tabela associada ao objeto
     * 
     * A string retornada define para o Yii qual tabela contém os registros a
     * serem mapeados para essa classe.
     * 
     * @return string Nome da tabela no banco de dados associada ao objeto
     */
    public function tableName()
    {
        return 'restricao_relogio';
    }

    /**
     * Método do Yii Framework para definição da chave primária do objeto
     * 
     * A string retornada indica a coluna contendo o identificador único do 
     * objetos.
     * 
     * @return string Nome da coluna referente à chave primária do objeto
     */
    public function primaryKey()
    {
        return 'nr_restricao';
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
            array('data_atualizacao, id_pessoa_atualizacao, ip_atualizacao', 'required'),
            array('id_orgao', 'length', 'max' => 5),
            array('escopo', 'length', 'max' => 1),
            array('id_pessoa, id_pessoa_atualizacao', 'length', 'max' => 6),
            array('mascara_ip_v4', 'length', 'max' => 18),
            array('mascara_ip_v6', 'length', 'max' => 45),
            array('ip_atualizacao', 'length', 'max' => 39),
            array('nr_restricao, id_orgao, escopo, id_pessoa, mascara_ip_v4, mascara_ip_v6, data_atualizacao, id_pessoa_atualizacao, ip_atualizacao', 'safe', 'on' => 'search'),
        );
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
    public function relations()
    {
        return array(
            'Orgao' => array(self::BELONGS_TO, 'Orgao', 'id_orgao'),
            'Pessoa' => array(self::BELONGS_TO, 'Pessoa', 'id_pessoa'),
        );
    }

    /**
     * Método do Yii Framework para definir descrições às colunas da tabela
     * 
     * Aqui são definidos nomes mais amigáveis aos atributos do objeto. É 
     * utilizado para gerar mensagens de erros mais claras e mostrar dados nas
     * telas da aplicação.
     * 
     * @return array Lista de descrições no formato 'coluna'=>'descrição'
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
     * Método do Yii Framework para buscar modelos
     *
     * Aqui é feita a pesquisa de um modelo de acordo com determinadas condições
     * passadas por parâmetro.
     * 
     * @return CActiveDataProvider Conjunto de dados retornados da consulta
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        
        $criteria->with = array('Orgao', 'Pessoa');

        if ($this->inOrgaos != NULL) {
            $criteria->addInCondition('t.id_orgao', $this->inOrgaos);
        }
        if ($this->inLotacao != NULL) {
            $criteria->addCondition("t.id_pessoa in (
                select D.id_pessoa 
                from dado_funcional D
                where
                    coalesce(D.data_desligamento, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP() 
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
        $criteria->compare('Orgao.sigla_orgao', $this->sigla_orgao, true);
        $criteria->compare('Orgao.nome_orgao', $this->sigla_orgao, true, 'OR');
        $criteria->compare('Pessoa.nome_pessoa', $this->nome_pessoa, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 30
            ),
            'sort' => array(
                'attributes' => array(
                    '*',
                    'nome_orgao' => array(
                        'asc' => 'Orgao.sigla_orgao asc',
                        SORT_ASC => 'Orgao.sigla_orgao asc',
                        'desc' => 'Orgao.sigla_orgao desc',
                        SORT_DESC => 'Orgao.sigla_orgao desc',
                    ),
                    'nome_pessoa' => array(
                        'asc' => 'Pessoa.nome_pessoa desc',
                        SORT_ASC => 'Pessoa.nome_pessoa desc',
                        'desc' => 'Pessoa.nome_pessoa asc',
                        SORT_DESC => 'Pessoa.nome_pessoa asc',
                    )
                ),
                'defaultOrder' => array(
                    'sigla_orgao' => SORT_ASC,
                    'nome_pessoa' => SORT_ASC,
                )
            ),
        ));
    }
    
    /**
     * Método para gerenciamento de órgãos
     * 
     * Esse método retorna todos os órgãos que estejam sob direção de um servidor.
     * 
     * @param int $codPessoaServidor Chave primária da classe Pessoa
     * @return array Instâncias da classe Orgao no qual o servidor é dirigente
     */
    public static function getOrgaosChefia($codPessoaServidor) {
        return Orgao::model()->with('DirigenteOrgao', 'DirigenteSubstituto')->findAll('DirigenteOrgao.id_pessoa = :id_pessoa or DirigenteSubstituto.id_pessoa = :id_pessoa2 ', array(
            ':id_pessoa' => $codPessoaServidor,
            ':id_pessoa2' => $codPessoaServidor,
        ));
    }

    /**
     * Método do Yii Framework para retornar a instância da classe
     * 
     * Esse método deve ser implementado em todas as classe {@see CActiveRecord}
     * para permitir que o framework encontre a classe.
     * 
     * @param string $className Nome da classe que é Active Record.
     * @return RestricaoRelogio A classe que é Active Record
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Método para controle acesso no ponto eletrônico
     * 
     * Esse método verifica se o usuário pode ou não fazer registo de ponto a
     * partir do IP da máquina.
     * 
     * O método retorna um array contendo as chaves <code>libera</code> contendo
     * TRUE ou FALSE e <code>mensagem</code> contendo um texto sobre o resultado.
     * Por padrão, só é exibida uma mensagem quando o ponto não estiver liberado.
     * 
     * Atualmente o método só suporta endereços IPv4.
     * 
     * @param int $id_pessoa Chave primária da classe Pessoa
     * @param int $matricula Chave primária da classe DadoFuncional
     * @param int $nr_vinculo Chave primária da classe DadoFuncional
     * @param string $ip Endereço de IP da máquina na qual o servidor está registrando
     * @return array Array permitindo ou não o usuário de usar um determinado ponto
     */
    public static function verificaLiberacaoPonto($id_pessoa, $matricula, $nr_vinculo, $ip)
    {
        // Verifica se não tem afastamento no dia
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
        
        // Verifica se a pessoa pode fazer registro no IP
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