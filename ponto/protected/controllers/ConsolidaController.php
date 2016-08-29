<?php

/* 
    Document   : ConsolidaController
    Created on : 05/05/2016, 14:47:38
    Author     : thiago
*/

class ConsolidaController extends BaseController
{
    public function filters()
    {
        return array(
            array('application.components.Sessao - atualizaAfastamentosJuntaMedica'),
            'accessControl - atualizaAfastamentosJuntaMedica',
        );
    }
    
    /**
     * Acao inicial, com interface para execucao das outras acoes
     */
    public function actionIndex()
    {
        $this->render("index");
    }

    /**
     * Calcula e salva a carga horaria dos servidores que registraram ponto no mes selecionado
     * @param type $mes
     * @param type $ano
     */
    public function actionCargaHorariaServidores($mes, $ano) 
    {
        $this->desabilitaYiiToolbar();
        
        $mes = intval($mes);
        $ano = intval($ano);
        
        // busca todos os servidores que tiveram registro no mes especificado
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
            // para cada servidor, calcula o total de horas trabalhadas (registros, ajustes, abonos) e o total de horas previstas e salva na tabela
            try {
                if (!CargaHorariaMesServidor::buscaDadosESalva($servidor->matricula, $servidor->nr_vinculo, $mes, $ano, $diasUteisMes)) {
                    $this->renderPartial("/registro/mensagem", array(
                        'mensagem' => "$matriculaServidor;$nrVinculo - erro",
                        'classe' => 'Erro'
                    ));
                }
            }
            catch (Exception $e) {
                $this->renderPartial('/registro/mensagem', array(
                    'mensagem' => $e->getMessage(),
                    'classe' => 'Erro'
                ));
                return;
            }
        }
        
        $this->renderPartial('/registro/mensagem', array(
            'mensagem' => 'Carga horária dos servidores atualizada ('.$mes.'/'.$ano.').',
            'classe' => 'Sucesso'
        ));
    }
    
    /**
     * Calcula e salva a carga horária de um lote de servidores que registraram ponto no mes selecionado
     * @param type $mes
     * @param type $ano
     * @param type $matriculaServidor
     * @param type $nrVinculo
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
                    $processados .= $pessoa.'<br/>';
                }
                catch (Exception $e) {
                    $this->renderPartial('/registro/mensagem', array(
                        'mensagem' => $e->getMessage().'<br/>'.$processados,
                        'classe' => 'Erro'
                    ));
                    return;
                }
            }
            else {
                $this->renderPartial('/registro/mensagem', array(
                    'mensagem' => 'Formato do lote incorreto.<br/>'.$processados,
                    'classe' => 'Erro'
                ));
                return;
            }
        }
        $this->renderPartial('/registro/mensagem', array(
            'mensagem' => 'Carga horária dos servidores atualizada ('.$mes.'/'.$ano.').<br/>'.$processados,
            'classe' => 'Sucesso'
        ));
    }
    
    /**
     * atualiza a carga horaria de servidores que tiveram lancamentos de frequencia da junta medica apos o fechamento do mes
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
                    coalesce(F.data_atualizacao, F.DataInclusao) > C.data_atualizacao ";
        $query = Yii::app()->db->createCommand($sql)->queryAll();
        $processados = '';
        foreach ($query as $registro) {
            try {
                if (!CargaHorariaMesServidor::buscaDadosESalva($registro['matricula'], $registro['nr_vinculo'], $registro['mes'], $registro['ano'])) {
                    $this->renderPartial("registro/mensagem", array(
                        'mensagem' => $registro['matricula'].";".$registro['nr_vinculo'].": ",
                        'classe' => 'Erro'
                    ));
                } 
                $processados .= $registro['mes']."/".$registro['ano']." - ".$registro['matricula'].";".$registro['nr_vinculo'].'<br/>';
            }
            catch (Exception $e) {
                // adiciona log de erro
                $this->renderPartial('registro/mensagem', array(
                    'mensagem' => $e->getMessage().'<br/>'.$processados,
                    'classe' => 'Erro'
                ));
                return;
            }
        }
        $this->renderPartial('/registro/mensagem', array(
            'mensagem' => 'Carga horária dos servidores atualizada.<br/>'.$processados,
            'classe' => 'Sucesso'
        ));
    }
}