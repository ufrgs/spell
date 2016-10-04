<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Controlador utilizado para permitir aos cargos de chefia ter uma visualização 
 * geral das atividades no sistema.
 * 
 * Aqui são implementadas a listagem de registros, pedidos e abonos, bem como
 * a possibilidade de pesquisar e alterar tais dados.
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage controllers
 * @version v1.0
 * @since v1.0
 */
class GerenciaController extends BaseController
{
    
    /**
     * Action utilizada para mostrar o menu de tabs da tela de gerência.
     * 
     * Aqui são buscados todos os registros de horários, solicitações de ajustes
     * e abonos. Estes dados são exibidos na tela juntamente com campos para
     * pesquisa de tais dados.
     */
    public function actionIndex()
    {
        $registros = new Ponto('search');
        if (isset($_GET['Ponto'])) {
            $registros->attributes = $_GET['Ponto'];  //Campos do modelo
        }
        
        $ajustes = new Ajuste('search');
        if (isset($_GET['Ajuste'])) {
            $ajustes->attributes = $_GET['Ajuste'];  //Campos do modelo
        }
        
        $abonos = new Abono('search');
        if (isset($_GET['Abono'])) {
            $abonos->attributes = $_GET['Abono'];  //Campos do modelo
        }

        $this->render('index', array(
            'registros' => $registros,
            'ajustes' => $ajustes,
            'abonos' => $abonos,
            'acompanhamento' => '',
        ));
    }
    
    /**
     * Action para exclução de um pedido de compensação.
     * 
     * O método deve receber o parâmetro "nr" via método POST para realizar uma
     * busca por um objeto da classe {@see Compensacao} com uma chave primária
     * correspondente. Além do parâmetro nr também necessário passar o parâmetro
     * tipo inidicando o tipo de pedido.
     * 
     * O sucesso ou falha da operação é indicado pela string retornada pelo 
     * método usando o comando <code>print</code>.
     */
    public function actionExcluirPedido() 
    {
        if (isset($_POST['nr'], $_POST['tipo']) && in_array($_POST['tipo'], array('ajuste', 'abono'))) { 
            if ($_POST['tipo'] == 'ajuste') {
                $pedido = Ajuste::model()->findByPk($_POST['nr']);
                $chave = 'nr_ajuste';
                $dataPedido = $pedido->data_hora_ponto;
            }
            else {
                $pedido = Abono::model()->findByPk($_POST['nr']);
                $chave = 'nr_abono';
                $dataPedido = $pedido->data_abono;
            }
        
            $matriculaServidor = $pedido->matricula;
            $nrVinculo = $pedido->nr_vinculo;
            ArquivoAjuste::model()->deleteAllByAttributes(array($chave => $_POST['nr']));
            
            if ($pedido->delete()) {
                
                // Se a exclusão está acontecendo após o fechamento do mês do pedido, recalcula o total de horas
                if ((date('m') > date('m', strtotime($dataPedido))) || (date('Y') > date('Y', strtotime($dataPedido)))) {
                    $mesAnterior = (date('m') != 1 ? date('m')-1 : 12);
                    $anoAnterior = (date('m') != 1 ? date('Y') : date('Y')-1);
                    CargaHorariaMesServidor::buscaDadosESalva($matriculaServidor, $nrVinculo, $mesAnterior, $anoAnterior);
                }
                print 'Pedido excluído com sucesso!';
            }
            else {
                print 'Ocorreu um erro ao excluir o pedido.'.print_r($pedido->getErrors(), true);
            }
        }
    }
    
    /**
     * Action utilizada para pesquisa de servidores da Universidade.
     * 
     * O método recebe uma parâmetro com o termo a ser comparado com o nome e 
     * com o código da pessoa usando o comando LIKE da linguaguem SQL.
     * 
     * Os resultadores encontrados serão devolvidos em formato JSON seguindo o
     * exemplo:
     * 
     * <code>
     * {
     *  "id": 0,
     *  "label": "0 - Nome",
     *  "text": "0 - Nome"
     * }
     * </code>
     * 
     * @param string $term Texto a ser usado na comparação com o nome e o id
     */
    public function actionServidores($term)
    {
        $term = strtoupper(str_replace("'", "''", Helper::tiraAcento(trim($term))));
        $pessoas = Pessoa::model()->with(array(
            'DadoFuncional' => array(
                'select' => '',
                'on' => 'coalesce(DadoFuncional.data_desligamento, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP() 
                        and coalesce(DadoFuncional.data_aposentadoria, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP()',
                'joinType' => 'inner join'
            )
        ))->findAll(array(
            'select' => 't.id_pessoa, t.nome_pessoa, t.nome_pessoa',
            'condition' => "
                ( t.nome_pessoa like '%$term%' COLLATE utf8_general_ci or LTRIM(CAST(t.id_pessoa AS char(12))) = '$term' )
                and t.id_pessoa <> :id_pessoa",
            'params' => array(
                ':id_pessoa' => Yii::app()->user->id_pessoa,
            ),
            'order' => 't.nome_pessoa'
        ));

        $opcoes = array();
        if (!empty($pessoas)) {
            foreach ($pessoas as $pessoa) {
                $opcoes[] = array(
                    'id' => $pessoa->id_pessoa,
                    'label' => $pessoa->id_pessoa." - ".$pessoa->nome_pessoa,
                    'text' => $pessoa->id_pessoa." - ".$pessoa->nome_pessoa
                );
            }
        }
        else {
            $opcoes[] = array(
                'id' => '',
                'label' => 'Nenhum servidor encontrado',
                'text' => 'Nenhum servidor encontrado'
            );
        }

        print CJSON::encode($opcoes);
        //Yii::app()->end();
    }  
}
