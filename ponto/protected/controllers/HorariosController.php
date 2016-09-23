<?php
/**
 * Copyright 2016 Universidade Federal do Rio Grande do Sul
 */

/**
 * Controlador utilizado para controle de carga horária dos órgãos.
 * 
 * Aqui são implementados os métodos para listagem e alteração dos horários de
 * dias úteis e finais de semana dos órgãos controlados pelo sistema.
 * 
 * @author UFRGS <cpd-dss@ufrgs.br>
 * @package cpd\spell
 * @version v1.0
 * @since v1.0
 */
class HorariosController extends BaseController
{
    
    /**
     * Método do Yii Framework para permitir a execução de código antes da 
     * execução de uma action.
     * 
     * Aqui são carregados os arquivos necessários para exibição do layout da 
     * aplicação como os arquivos HTML, CSS e JavaScript.
     * 
     * @param CAction $action A action do controller que foi requisitada
     */
    public function beforeAction($action) {
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/horarioOrgao.js', CClientScript::POS_END);
       
        return parent::beforeAction($action);
    }
    
    /**
     * Action utilizada para listagem de horários de acordo com uma categoria.
     * 
     * A seleção da categoria a ser mostrada é feita através do parâmetro 
     * numérico "Orgãos" passado via métodos GET ou POST e tratado com a 
     * superglobal $_REQUEST[].
     * 
     * Caso não seja passado o parâmetro é mostrada apenas a lista de órgãos
     * para que o usuário selecione o que desejar visualizar os horários.
     * 
     * Exemplo de URL: <code>/ponto/horarios/horariosOrgaos?Orgaos=2</code>
     */
    public function actionHorariosOrgaos()
    {
        $id_pessoa = Yii::app()->session['id_pessoa'];
        $controle = $empty = false;
        $orgaos = $this->retornaOrgaosResponsabilidade($id_pessoa);
        if ($orgaos == NULL || empty ($orgaos)) // Pessoa não possui órgãos sob sua responsabilidade 
        {
            $orgao = $this->retornaOrgaoLotacao($id_pessoa);
            $definicao   =   new DefinicoesOrgao();            
            if (!is_null($orgao))
            {
                $definicao  =   DefinicoesOrgao::model()->find('id_orgao = '.$orgao->id_orgao);                
            }            
            $empty = is_null($definicao) || is_null($orgao);            
            $this->render('exibirHorariosOrgao', array('orgao' => $orgao, 'definicao' => $definicao, 'podeEditar' => false, 'empty' => $empty));
        }
        else // Possui órgãos sob sua responsabilidade
        {
            $definicao = $orgao = null;            
            if(isset($_REQUEST['Orgaos'])) { // Selecionou um dos órgãos sob responsabilidade 
                $orgaos = $this->retornaOrgaosResponsabilidade($id_pessoa);
                $controle  = true;
                $orgao     = Orgao::model()->findByPk($_REQUEST['Orgaos']); 
                $definicao = DefinicoesOrgao::model()->findByPk($orgao->id_orgao);
                $defOrgaoSuperior = ($orgao->getAttribute("id_orgao_superior") == NULL ? NULL : DefinicoesOrgao::model()->findByPk($orgao->getAttribute("id_orgao_superior")));
                $aLimitesHorario['hora_inicio_expediente'] = "00:00";
                $aLimitesHorario['hora_fim_expediente'] = "23:59";
                $aLimitesHorario['Sabado'] = true;
                $aLimitesHorario['Domingo'] = true;
                $aLimitesHorario['hora_inicio_expediente_sabado'] = "00:00";
                $aLimitesHorario['hora_fim_expediente_sabado'] = "23:59";
                $aLimitesHorario['hora_inicio_expediente_domingo'] = "00:00";
                $aLimitesHorario['hora_fim_expediente_domingo'] = "23:59";
                if (!is_null($defOrgaoSuperior) || !empty($defOrgaoSuperior)){
                    $aLimitesHorario['hora_inicio_expediente'] = $defOrgaoSuperior->getAttribute("hora_inicio_expediente_hora");
                    $aLimitesHorario['hora_fim_expediente'] = $defOrgaoSuperior->getAttribute("hora_fim_expediente_hora");
                    $aLimitesHorario['Sabado'] = $defOrgaoSuperior->getAttribute("sabado");                    
                    $aLimitesHorario['Domingo'] = $defOrgaoSuperior->getAttribute("domingo");
                    $aLimitesHorario['hora_inicio_expediente_sabado'] = $defOrgaoSuperior->getAttribute("hora_inicio_expediente_sabado_hora");
                    $aLimitesHorario['hora_fim_expediente_sabado'] = $defOrgaoSuperior->getAttribute("hora_fim_expediente_sabado_hora");
                    $aLimitesHorario['hora_inicio_expediente_domingo'] = $defOrgaoSuperior->getAttribute("hora_inicio_expediente_domingo_hora");
                    $aLimitesHorario['hora_fim_expediente_domingo'] = $defOrgaoSuperior->getAttribute("hora_fim_expediente_domingo_hora");
                }
                if(is_null($definicao)){
                    $definicao = new DefinicoesOrgao();
                }
                $this->renderPartial('exibirHorariosOrgao', array('orgaos' => $orgaos, 'orgao' => $orgao, 'definicao' => $definicao,'aLimitesHorario'=>$aLimitesHorario, 'podeEditar' => true, 'empty' => $empty));                
            }else{
                $this->render('horariosOrgaos', array('orgaos' => $orgaos, 'definicao'=> $definicao, 'orgao' => $orgao));
            }
        }
    }
    
    /**
     * Action utilizada para salvar os horários alterados na página.
     * 
     * Esse método deve receber os parâmetros: Orgao e DefinicoesOrgao via 
     * método POST.
     * 
     * @return string Mensagem de sucesso ou erro em formato JSON contendo o atributo mensagem
     * @throws CHttpException Excessão disparada caso o parâmetro "Orgao" não tenha sido passado
     */
    public function actionSalvarHorarios()
    {   
        if (isset($_POST['Orgao'])){
            $msg="";
            $postDefinicoes = $_POST['DefinicoesOrgao'];
            $orgao     = Orgao::model()->find('t.id_orgao = '.$_POST['Orgao']);            
            $definicao = DefinicoesOrgao::model()->find('t.id_orgao = '.$orgao->id_orgao);
            $defOrgaoSuperior = DefinicoesOrgao::model()->find('t.id_orgao = '.$orgao->getAttribute("id_orgao_superior"));
            
            if (is_null($definicao)){
                $definicao = new DefinicoesOrgao();
                $definicao->id_orgao = $orgao->id_orgao;          
            }
            
            $definicao->data_atualizacao = new CDbExpression("CURRENT_TIMESTAMP()");
            $definicao->id_pessoa_atualizacao = Yii::app()->session['id_pessoa'];
            
            // Verifica se o horário informado está de acordo com o horário do Órgão superior
            if (!is_null($defOrgaoSuperior)){
                if($postDefinicoes['hora_inicio_expediente_hora'] < $defOrgaoSuperior->hora_inicio_expediente_hora || $postDefinicoes['hora_fim_expediente_hora'] > $defOrgaoSuperior->hora_fim_expediente_hora){
                    $msg .= "O horário informado não está de acordo com o horário do órgão hierarquicamente superior.";
                }
            }
            
            if (!($postDefinicoes['hora_inicio_expediente_hora']=="") && !($postDefinicoes['hora_fim_expediente_hora']=="")){                
                $hora_inicio_expediente = $postDefinicoes['hora_inicio_expediente_hora'];
                $hora_fim_expediente = $postDefinicoes['hora_fim_expediente_hora'];                
                $definicao->hora_inicio_expediente_hora = $hora_inicio_expediente;
                $definicao->hora_fim_expediente_hora = $hora_fim_expediente;
            }
            else {
                $msg.="Hora inválida";
            }
            
            if (isset($postDefinicoes['hora_inicio_expediente_sabado_hora']) && isset($postDefinicoes['hora_fim_expediente_sabado_hora'])){
                if(!is_null($defOrgaoSuperior)){
                    if(!($defOrgaoSuperior->hora_inicio_expediente_sabado_hora == "") || !($defOrgaoSuperior->hora_fim_expediente_sabado_hora == ""))
                    {
                        if($postDefinicoes['hora_inicio_expediente_sabado_hora'] < $defOrgaoSuperior->hora_inicio_expediente_sabado_hora || $postDefinicoes['hora_fim_expediente_sabado_hora'] > $defOrgaoSuperior->hora_fim_expediente_sabado_hora){
                            $msg .= "O horário informado não está de acordo com o horário do órgão hierarquicamente superior para sábado.";
                        }
                    }else{
                        $msg .= "O órgão hierarquicamente superior não possui horário cadastrado no sábado.";
                    }
                }
                
                if ((!($postDefinicoes['hora_inicio_expediente_sabado_hora']=="") && !($postDefinicoes['hora_fim_expediente_sabado_hora']==""))){
                    $hora_inicio_expediente_sabado = $postDefinicoes['hora_inicio_expediente_sabado_hora'];
                    $hora_fim_expediente_sabado = $postDefinicoes['hora_fim_expediente_sabado_hora'];
                    $definicao->hora_inicio_expediente_sabado_hora = $hora_inicio_expediente_sabado;
                    $definicao->hora_fim_expediente_sabado_hora = $hora_fim_expediente_sabado;               
                }
                else {
                    $msg.="Hora de sábado inválida";
                }
            }
            
            else{
                $definicao->hora_inicio_expediente_sabado_hora =  null;
                $definicao->hora_fim_expediente_sabado_hora =  null;
            }
            
            if (isset($postDefinicoes['hora_inicio_expediente_domingo_hora']) && isset($postDefinicoes['hora_fim_expediente_domingo_hora'])){
                if(!is_null($defOrgaoSuperior)){
                    if(!($defOrgaoSuperior->hora_inicio_expediente_domingo_hora == "") || !($defOrgaoSuperior->hora_fim_expediente_domingo_hora == ""))
                    {
                        if($postDefinicoes['hora_inicio_expediente_domingo_hora'] < $defOrgaoSuperior->hora_inicio_expediente_domingo_hora || $postDefinicoes['hora_fim_expediente_domingo_hora'] > $defOrgaoSuperior->hora_fim_expediente_domingo_hora){
                            $msg .= "O horário informado não está de acordo com o horário do órgão hierarquicamente superior para Domingo.";
                        }
                    }else{
                        $msg .= "O órgão hierarquicamente superior não possui horário cadastrado no Domingo.";
                    }
                }
                
                if (!($postDefinicoes['hora_inicio_expediente_domingo_hora']=="") && !($postDefinicoes['hora_fim_expediente_domingo_hora']=="")){
                    $hora_inicio_expediente_domingo = $postDefinicoes['hora_inicio_expediente_domingo_hora'];
                    $hora_fim_expediente_domingo = $postDefinicoes['hora_fim_expediente_domingo_hora'];
                    $definicao->hora_inicio_expediente_domingo_hora = $hora_inicio_expediente_domingo;
                    $definicao->hora_fim_expediente_domingo_hora = $hora_fim_expediente_domingo;               
                }
                else {
                    $msg.="Hora de domingo inválida";
                }
            }
            
            else{
                $definicao->hora_inicio_expediente_domingo_hora = null;
                $definicao->hora_fim_expediente_domingo_hora = null;               
            }
            if ($msg==""){
                if ($definicao->save()) {
                    $orgaosDescendentes = Helper::getHierarquiaDescendenteOrgao($orgao->id_orgao);
                    $orgaos = Orgao::model()->findAll(array(
                        "condition" => "id_orgao <> :id_orgao
                            AND id_orgao IN (
                                :orgaos_descendentes
                            )",
                        'params' => array(
                            ':id_orgao' => $orgao->id_orgao,
                            ':orgaos_descendentes' => implode(',', $orgaosDescendentes),
                        )
                    ));

                    if(!empty($orgaos) || !is_null($orgaos)){
                        foreach ($orgaos as $org) {

                            $def = new DefinicoesOrgao();
                            $def->id_orgao = $org->id_orgao;         
                            $def->data_atualizacao = new CDbExpression("CURRENT_TIMESTAMP()");
                            $def->id_pessoa_atualizacao = Yii::app()->session['id_pessoa'];

                            if (isset($hora_inicio_expediente) && isset($hora_fim_expediente)){
                                if(!($hora_inicio_expediente=="") && !($hora_fim_expediente=="")){
                                    $def->hora_inicio_expediente_hora = $hora_inicio_expediente;
                                    $def->hora_fim_expediente_hora = $hora_fim_expediente;               
                                }
                            }

                            if (isset($hora_inicio_expediente_sabado) && isset($hora_fim_expediente_sabado)){
                                if(!($hora_inicio_expediente_sabado=="") && !($hora_fim_expediente_sabado=="")){
                                    $def->hora_inicio_expediente_sabado_hora = $hora_inicio_expediente_sabado;
                                    $def->hora_fim_expediente_sabado_hora = $hora_fim_expediente_sabado;               
                                }
                            }

                            if (isset($hora_inicio_expediente_domingo) && isset($hora_fim_expediente_domingo)){
                                if(!($hora_inicio_expediente_domingo=="") && !($hora_fim_expediente_domingo=="")){
                                    $def->hora_inicio_expediente_domingo_hora = $hora_inicio_expediente_domingo;
                                    $def->hora_fim_expediente_domingo_hora = $hora_fim_expediente_domingo;               
                                }
                            }                        

                            if (!$def->save()){
                                echo Yii::app()->user->setFlash('error', "Ocorreu um erro ao salvar o horário do órgão ".$org->id_orgao);
                            }
                        }
                    }
                    $mensagem = array('mensagem'=>utf8_encode("O hor&aacute;rio foi salvo com sucesso."), 'tipo'=>'flash-success');                    
                    echo json_encode($mensagem);                    
                    
                }
                else {                    
                    $mensagem = array('mensagem'=>utf8_encode("Ocorreu um erro ao salvar o hor&aacute;rio."), 'tipo'=>'flash-error');                    
                    echo json_encode($mensagem); 
                }
            }            
            else {
                $mensagem = array('mensagem'=>utf8_encode("Ocorreu um erro ao salvar o hor&aacute;rio. ".$msg), 'tipo'=>'flash-error');                    
                echo json_encode($mensagem); 
            }
        }
        else{
            throw new CHttpException(400, "Erro ao processar solicitação");
        }
         
    }
    
    /**
     * Método auxiliar para busca de órgãos sob responsabilidade de uma pessoa.
     * 
     * É utilizado no método actionHorariosOrgaos() para reaproveitamento de
     * código.
     * 
     * @param int Chave primária do usuário
     * @return array Array contendo instâncias da classe Orgao
     */
    private function retornaOrgaosResponsabilidade($id_pessoa)
    {
        $orgaosChefia = Helper::getHierarquiaOrgaosChefia(Yii::app()->user->id_pessoa);
        $orgaosChefia = Helper::coalesce(implode(',', $orgaosChefia), 0);
        $orgaos = Orgao::model()->findAll(array(
            'select' => 'id_orgao, nome_orgao',
            'condition' => "id_orgao in (
                $orgaosChefia
            )",
            'order' => 'nome_orgao'
        ));
        return $orgaos;
    }
    
    /**
     * Método auxiliar para busca horários de pessoas sem chefia.
     * 
     * É utilizado no método actionHorariosOrgaos() para reaproveitamento de
     * código.
     * 
     * @param int $id_pessoa Chave primária do usuário
     * @return Orgao Instância da classe Orgao associada ao usuário
     */
    private function retornaOrgaoLotacao($id_pessoa)
    {
        $pessoa = DadoFuncional::model()->with('OrgaoExercicio')->find(array(
            'condition' => "coalesce(data_desligamento, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP() 
                            and coalesce(data_aposentadoria, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP()
                            and id_pessoa = :id_pessoa",
            'params' => array(
                ':id_pessoa' => $id_pessoa,
            )
        ));
        return $pessoa->OrgaoExercicio;
    }
}