<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Controlador utilizado para permitir a consolidação de horários dos servidores.
 * 
 * Aqui são implementados os métodos para consolidar registros de servidores 
 * individuais, em lote ou mesmo todos os servidores.
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @subpackage controllers
 * @version v1.0
 * @since v1.0
 */
class ConsolidaController extends BaseController
{

    /**
     * Método do Yii Framework para adição de filtros nas actions.
     * 
     * Esse método é executado automaticamente antes de cada chamada ao método
     * {@see ConsolidaController::actionAtualizaAfastamentosJuntaMedica()}.
     *
     * @return array Filtros utilizados neste controlador
     */
    public function filters()
    {
        return array(
            array('application.components.Sessao - atualizaAfastamentosJuntaMedica'),
            'accessControl - atualizaAfastamentosJuntaMedica',
        );
    }

    /**
     * Action utilizada para mostrar a principal do painel de consolidação.
     * 
     * Esse método busca todos os pedidos feitos pelo usuário e os exibe na tela.
     */
    public function actionIndex()
    {
        $this->render("index");
    }

    /**
     * Calcula e salva a carga horaria dos servidores que registraram ponto no 
     * mês selecionado.
     * 
     * Esse método verifica se os servidores estão com os dados de horários
     * corretos e salva os novos registros no banco de dados.
     * 
     * Se algum horário estiver com erro o método renderiza uma mensagem de erro.
     * Caso contrário uma mensagem de sucesso é mostrada na tela.
     * 
     * Ambas as mensagens são exibidas usando o método <code>renderPartial()</code>.
     * 
     * @param int $mes Valor correspondente ao mês corrente
     * @param int $ano Valor correspondente ao ano corrente
     */
    public function actionCargaHorariaServidores($mes, $ano)
    {
        $this->desabilitaYiiToolbar();

        $mes = intval($mes);
        $ano = intval($ano);

        // Busca todos os servidores que tiveram registro no mês especificado
        $servidores = PontoEAjuste::model()->findAll(array(
            'select' => 'id_pessoa, matricula, nr_vinculo',
            'distinct' => true,
            'condition' => 'month(data_hora_ponto) = :mes and year(data_hora_ponto) = :ano',
            'params' => array(
                ':mes' => $mes,
                ':ano' => $ano,
            ),
            'order' => 'id_pessoa'
        ));

        $diasUteisMes = Ponto::getNrDiasUteis($mes, $ano);

        foreach ($servidores as $servidor) {
            try {
                if (!CargaHorariaMesServidor::buscaDadosESalva($servidor->matricula, $servidor->nr_vinculo, $mes, $ano, $diasUteisMes)) {
                    $this->renderPartial("/registro/mensagem", array(
                        'mensagem' => "$matriculaServidor;$nrVinculo - erro",
                        'classe' => 'Erro'
                    ));
                }
            } catch (Exception $e) {
                $this->renderPartial('/registro/mensagem', array(
                    'mensagem' => $e->getMessage(),
                    'classe' => 'Erro'
                ));
                return;
            }
        }

        $this->renderPartial('/registro/mensagem', array(
            'mensagem' => 'Carga horária dos servidores atualizada (' . $mes . '/' . $ano . ').',
            'classe' => 'Sucesso'
        ));
    }

    /**
     * Calcula e salva a carga horária de um lote de servidores que registraram 
     * ponto no mes selecionado.
     * 
     * Esse método tem o mesmo comportamento do método 
     * {@see ConsolidaController::actionCargaHorariaServidores($mes, $ano)} com
     * a diferênça de permitir o processamento dos horários de mais de um 
     * servidor.
     * 
     * @param int $mes Valor correspondente ao mês corrente
     * @param int $ano Valor correspondente ao ano corrente
     * @param array $lote Array com os códigos dos servidores
     */
    public function actionCargaHorariaLote($mes, $ano, $lote)
    {
        $diasUteisMes = Ponto::getNrDiasUteis($mes, $ano);
        $pessoas = explode("\n", $lote);
        $processados = '';

        foreach ($pessoas as $pessoa) {
            $dadosServidor = explode(";", $pessoa);
            if (isset($dadosServidor[0], $dadosServidor[1])) {
                try {
                    if (!CargaHorariaMesServidor::buscaDadosESalva($dadosServidor[0], $dadosServidor[1], $mes, $ano, $diasUteisMes)) {
                        $this->renderPartial("/registro/mensagem", array(
                            'mensagem' => "$matriculaServidor;$nrVinculo - erro",
                            'classe' => 'Erro'
                        ));
                    }
                    $processados .= $pessoa . '<br/>';
                } catch (Exception $e) {
                    $this->renderPartial('/registro/mensagem', array(
                        'mensagem' => $e->getMessage() . '<br/>' . $processados,
                        'classe' => 'Erro'
                    ));
                    return;
                }
            } else {
                $this->renderPartial('/registro/mensagem', array(
                    'mensagem' => 'Formato do lote incorreto.<br/>' . $processados,
                    'classe' => 'Erro'
                ));
                return;
            }
        }
        $this->renderPartial('/registro/mensagem', array(
            'mensagem' => 'Carga horária dos servidores atualizada (' . $mes . '/' . $ano . ').<br/>' . $processados,
            'classe' => 'Sucesso'
        ));
    }

    /**
     * Action utilizada para atualizar a carga horaria de servidores que tiveram 
     * lançamentos de frequência da junta medica após o fechamento do mês.
     * 
     * Se algum horário estiver com erro o método renderiza uma mensagem de erro.
     * Caso contrário uma mensagem de sucesso é mostrada na tela.
     * 
     * Ambas as mensagens são exibidas usando o método <code>renderPartial()</code>.
     */
    public function actionAtualizaAfastamentosJuntaMedica()
    {
        $sql = "select 
                    distinct F.matricula, F.nr_vinculo, C.mes, C.ano
                from frequencia F 
                    join dado_funcional DF on
                        F.matricula = DF.matricula
                        and F.nr_vinculo = DF.nr_vinculo
                    join grupoemprego GE on
                        DF.id_grupo = DF.id_grupo
                        and GE.segmento_grupo = 'T'
                    join ch_mes_servidor C on
                        F.matricula = C.matricula
                        and F.nr_vinculo = C.nr_vinculo
                        and month(F.data_frequencia) = C.mes
                        and year(F.data_frequencia) = C.ano
                where 
                    coalesce(F.data_atualizacao, F.data_inclusao) > C.data_atualizacao ";
        $query = Yii::app()->db->createCommand($sql)->queryAll();
        $processados = '';
        foreach ($query as $registro) {
            try {
                if (!CargaHorariaMesServidor::buscaDadosESalva($registro['matricula'], $registro['nr_vinculo'], $registro['mes'], $registro['ano'])) {
                    $this->renderPartial("registro/mensagem", array(
                        'mensagem' => $registro['matricula'] . ";" . $registro['nr_vinculo'] . ": ",
                        'classe' => 'Erro'
                    ));
                }
                $processados .= $registro['mes'] . "/" . $registro['ano'] . " - " . $registro['matricula'] . ";" . $registro['nr_vinculo'] . '<br/>';
            } catch (Exception $e) {
                // adiciona log de erro
                $this->renderPartial('registro/mensagem', array(
                    'mensagem' => $e->getMessage() . '<br/>' . $processados,
                    'classe' => 'Erro'
                ));
                return;
            }
        }
        $this->renderPartial('/registro/mensagem', array(
            'mensagem' => 'Carga horária dos servidores atualizada.<br/>' . $processados,
            'classe' => 'Sucesso'
        ));
    }
}
