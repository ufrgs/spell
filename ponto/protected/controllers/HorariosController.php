<?php

class HorariosController extends BaseController
{      
    
    public function beforeAction($action) {
        
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/horarioOrgao.js', CClientScript::POS_END);
       
        return parent::beforeAction($action);
    }
    
    public function actionHorariosOrgaos()
    {
        $id_pessoa = Yii::app()->session['id_pessoa'];
        $controle = $empty = false;
        $orgaos = $this->retornaOrgaosResponsabilidade($id_pessoa);
        if ($orgaos == NULL || empty ($orgaos)) //Pessoa não possui orgaos sob sua responsabilidade 
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
        else //possui orgaos sobre sua responsabilidade 
        {
            $definicao = $orgao = null;            
            if(isset($_REQUEST['Orgaos'])) { //selecionou um dos orgaos sob responsabilidade 
                $orgaos = $this->retornaOrgaosResponsabilidade($id_pessoa);
                $controle  = true;
                $orgao     = Orgao::model()->find('t.id_orgao = '.$_REQUEST["Orgaos"]);
                $definicao = DefinicoesOrgao::model()->find('t.id_orgao = '.$_REQUEST["Orgaos"]);
                $defOrgaoSuperior = DefinicoesOrgao::model()->find('t.id_orgao = '.$orgao->getAttribute("id_orgao_superior"));
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
            
            //Verifica se o horário informado está de acordo com o horário do Órgão superior
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
    
    private function retornaOrgaosResponsabilidade($id_pessoa)
    {
        $orgaosChefia = Helper::getHierarquiaOrgaosChefia(Yii::app()->user->id_pessoa);
        $orgaos = Orgao::model()->findAll(array(
            'select' => 'id_orgao, nome_orgao',
            'condition' => "id_orgao in (
                :orgaos_chefia
            )",
            'params' => array(
                ':orgaos_chefia' => implode(',', $orgaosChefia),
            ),
            'order' => 'nome_orgao'
        ));
        return $orgaos;
    }
    
    private function retornaOrgaoLotacao($id_pessoa)
    {
        $pessoa = DadoFuncional::model()->with('OrgaoExercicio')->find(array(
            'condition' => "coalesce(DadosFuncionais.data_desligamento, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP() 
                            and coalesce(DadosFuncionais.data_aposentadoria, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP()
                            and id_pessoa = :id_pessoa",
            'params' => array(
                ':id_pessoa' => $id_pessoa,
            )
        ));
        return $pessoa->OrgaoExercicio;
    }
}