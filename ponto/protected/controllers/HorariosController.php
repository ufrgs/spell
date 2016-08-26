<?php

class HorariosController extends BaseController
{      
    
    public function beforeAction($action) {
        
        Yii::app()->getClientScript()->registerScriptFile("/Funcoes/jquery/jquery.maskedinput-1.2.2.js", CClientScript::POS_END);
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/horarioOrgao.js', CClientScript::POS_END);
       
        return parent::beforeAction($action);
    }
    
    public function actionHorariosOrgaos()
    {
        $id_pessoa = Yii::app()->session['id_pessoa'];
        $controle = $empty = false;
        $orgaos = Orgao::model()->with('OrgaoUfrgs')->findAll(array(
            'select' => 't.id_orgao, t.nome_orgao',
            'condition' => "t.id_orgao in (
                select id_orgao from fn_hierarquia_orgao_funcoes_pessoa (:CodPessoa1)
                union
                select id_orgao from fn_permissoes (:id_pessoa2, 'RH', 'rh003', null) 
                    union
                select id_orgao 
                from TABELAS_AUXILIARES..ADOrgaoDirigenteExercicio TAUX
                    inner join SERVIDOR S on S.matricula = TAUX.matricula  
                where S.id_pessoa = :id_pessoa3
            ) and OrgaoUfrgs.DataExtincaoOrgao is null",
            'params' => array(
                ':CodPessoa1' => $id_pessoa,
                ':id_pessoa2' => $id_pessoa,
                ':id_pessoa3' => $id_pessoa,
            ),
            'order' => 'nome_orgao'
        ));

        if ($orgaos == NULL || empty ($orgaos))
        {
            $orgao      =   Orgao::model()->find(array(
                'condition'     =>  "
                    id_orgao in (
                    select O.id_orgao
                    from dado_funcional D
                    join orgao O on
                        D.orgao_exercicio = O.id_orgao
                    where D.id_pessoa = :id_pessoa
                        and coalesce(D.data_aposentadoria, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP()
                        and coalesce(D.data_desligamento, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY)) > CURRENT_TIMESTAMP())"
            ,
                'params' => array(
                    ':id_pessoa' => $id_pessoa,
                    )
            ));
            $definicao   =   new DefinicoesOrgao();
            
            if (is_null($orgao))
            {
                $empty      =   true;
            }
            
            else
            {
                $definicao  =   DefinicoesOrgao::model()->find('id_orgao = '.$orgao->id_orgao);
                if(is_null($definicao)){
                    $empty  =   true;                    
                }
            }
            
            $this->render('exibirHorariosOrgao', array('orgao' => $orgao, 'definicao' => $definicao, 'podeEditar' => false, 'empty' => $empty));
        }        
        else
        {
            $definicao = $orgao = null;
            
            if(isset($_REQUEST['Orgaos'])) {
                $orgaos = Orgao::model()->with('OrgaoUfrgs')->findAll(array(
                    'select' => 't.id_orgao, t.nome_orgao',
                    'condition' => "t.id_orgao in (
                        select id_orgao from fn_hierarquia_orgao_funcoes_pessoa (:CodPessoa1)
                        union
                        select id_orgao from fn_permissoes (:id_pessoa2, 'RH', 'rh003', null) 
                            union
                        select id_orgao 
                        from TABELAS_AUXILIARES..ADOrgaoDirigenteExercicio TAUX
                            inner join SERVIDOR S on S.matricula = TAUX.matricula  
                        where S.id_pessoa = :id_pessoa3
                    ) and OrgaoUfrgs.DataExtincaoOrgao is null",
                    'params' => array(
                        ':CodPessoa1' => $id_pessoa,
                        ':id_pessoa2' => $id_pessoa,
                        ':id_pessoa3' => $id_pessoa,
                    ),
                    'order' => 'nome_orgao'
                ));
                if (is_null($orgaos) || empty($orgaos)){
                    $empty      =   true;
                    $this->render('exibirHorariosOrgao', array('orgao' => $orgao, 'definicao' => $definicao, 'podeEditar' => false, 'empty' => $empty));
                    
                }
                else{
                    $controle  = true;
                    $orgao     = Orgao::model()->find('t.id_orgao = '.$_REQUEST["Orgaos"]);
                    $definicao = DefinicoesOrgao::model()->find('t.id_orgao = '.$_REQUEST["Orgaos"]);
                    if(is_null($definicao)){
                        $definicao = new DefinicoesOrgao();
                    }
                             
                    $this->renderPartial('exibirHorariosOrgao', array('orgaos' => $orgaos, 'orgao' => $orgao, 'definicao' => $definicao, 'podeEditar' => true, 'empty' => $empty));
                }
            }
            if ($controle==false){
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
            $defOrgaoSuperior = DefinicoesOrgao::model()->find('t.id_orgao = '.$orgao->getAttribute("CodOrgaoHierarquicamSuperior"));            
            
            if (is_null($definicao)){
                $definicao = new DefinicoesOrgao();
                $definicao->id_orgao = $orgao->id_orgao;          
            }
            
            $definicao->data_atualizacao = new CDbExpression("CURRENT_TIMESTAMP()");
            $definicao->id_pessoa_atualizacao = Yii::app()->session['id_pessoa'];
            
            //Verifica se o horario informado esta de acordo com o horario do orgao superior
            if (!is_null($defOrgaoSuperior)){
                if($postDefinicoes['HoraInicioExpediente_hora'] < $defOrgaoSuperior->HoraInicioExpediente_hora || $postDefinicoes['HoraFimExpediente_hora'] > $defOrgaoSuperior->HoraFimExpediente_hora){
                    $msg .= "O horário informado não está de acordo com o horário do órgão hierarquicamente superior.";
                }
            }
            
            if (!($postDefinicoes['HoraInicioExpediente_hora']=="") && !($postDefinicoes['HoraFimExpediente_hora']=="")){                
                $hora_inicio_expediente = $postDefinicoes['HoraInicioExpediente_hora'];
                $hora_fim_expediente = $postDefinicoes['HoraFimExpediente_hora'];                
                $definicao->HoraInicioExpediente_hora = $hora_inicio_expediente;
                $definicao->HoraFimExpediente_hora = $hora_fim_expediente;
            }
            else {
                $msg.="Hora inválida";
            }
            
            if (isset($postDefinicoes['HoraInicioExpedienteSabado_hora']) && isset($postDefinicoes['HoraFimExpedienteSabado_hora'])){
                if(!is_null($defOrgaoSuperior)){
                    if(!($defOrgaoSuperior->HoraInicioExpedienteSabado_hora == "") || !($defOrgaoSuperior->HoraFimExpedienteSabado_hora == ""))
                    {
                        if($postDefinicoes['HoraInicioExpedienteSabado_hora'] < $defOrgaoSuperior->HoraInicioExpedienteSabado_hora || $postDefinicoes['HoraFimExpedienteSabado_hora'] > $defOrgaoSuperior->HoraFimExpedienteSabado_hora){
                            $msg .= "O horário informado não está de acordo com o horário do órgão hierarquicamente superior para sábado.";
                        }
                    }else{
                        $msg .= "O órgão hierarquicamente superior não possui horário cadastrado no sábado.";
                    }
                }
                
                if ((!($postDefinicoes['HoraInicioExpedienteSabado_hora']=="") && !($postDefinicoes['HoraFimExpedienteSabado_hora']==""))){
                    $hora_inicio_expediente_sab = $postDefinicoes['HoraInicioExpedienteSabado_hora'];
                    $hora_fim_expediente_sab = $postDefinicoes['HoraFimExpedienteSabado_hora'];
                    $definicao->HoraInicioExpedienteSabado_hora = $hora_inicio_expediente_sab;
                    $definicao->HoraFimExpedienteSabado_hora = $hora_fim_expediente_sab;               
                }
                else {
                    $msg.="Hora de sábado inválida";
                }
            }
            
            else{
                $definicao->HoraInicioExpedienteSabado_hora =  null;
                $definicao->HoraFimExpedienteSabado_hora =  null;
            }
            
            if (isset($postDefinicoes['HoraInicioExpedienteDomingo_hora']) && isset($postDefinicoes['HoraFimExpedienteDomingo_hora'])){
                if(!is_null($defOrgaoSuperior)){
                    if(!($defOrgaoSuperior->HoraInicioExpedienteDomingo_hora == "") || !($defOrgaoSuperior->HoraFimExpedienteDomingo_hora == ""))
                    {
                        if($postDefinicoes['HoraInicioExpedienteDomingo_hora'] < $defOrgaoSuperior->HoraInicioExpedienteDomingo_hora || $postDefinicoes['HoraFimExpedienteDomingo_hora'] > $defOrgaoSuperior->HoraFimExpedienteDomingo_hora){
                            $msg .= "O horário informado não está de acordo com o horário do órgão hierarquicamente superior para Domingo.";
                        }
                    }else{
                        $msg .= "O órgão hierarquicamente superior não possui horário cadastrado no Domingo.";
                    }
                }
                
                if (!($postDefinicoes['HoraInicioExpedienteDomingo_hora']=="") && !($postDefinicoes['HoraFimExpedienteDomingo_hora']=="")){
                    $hora_inicio_expediente_dom = $postDefinicoes['HoraInicioExpedienteDomingo_hora'];
                    $hora_fim_expediente_dom = $postDefinicoes['HoraFimExpedienteDomingo_hora'];
                    $definicao->HoraInicioExpedienteDomingo_hora = $hora_inicio_expediente_dom;
                    $definicao->HoraFimExpedienteDomingo_hora = $hora_fim_expediente_dom;               
                }
                else {
                    $msg.="Hora de domingo inválida";
                }
            }
            
            else{
                $definicao->HoraInicioExpedienteDomingo_hora = null;
                $definicao->HoraFimExpedienteDomingo_hora = null;               
            }
            if ($msg==""){
                if ($definicao->save()) {
                    $orgaos     = Orgao::model()->findAll(array(
                        "condition" => "id_orgao in(
                            select id_orgao 
                            from fn_orgao_descendente(:id_orgao)
                            where id_orgao not in (
                                select id_orgao from definicoes_orgao
                            ))",
                        'params' => array(
                            ':id_orgao' => $orgao->id_orgao,
                            )));

                    if(!empty($orgaos) || !is_null($orgaos)){
                        foreach ($orgaos as $org) {

                            $def = new DefinicoesOrgao();
                            $def->id_orgao = $org->id_orgao;         
                            $def->data_atualizacao = new CDbExpression("CURRENT_TIMESTAMP()");
                            $def->id_pessoa_atualizacao = Yii::app()->session['id_pessoa'];

                            if (isset($hora_inicio_expediente) && isset($hora_fim_expediente)){
                                if(!($hora_inicio_expediente=="") && !($hora_fim_expediente=="")){
                                    $def->HoraInicioExpediente_hora = $hora_inicio_expediente;
                                    $def->HoraFimExpediente_hora = $hora_fim_expediente;               
                                }
                            }

                            if (isset($hora_inicio_expediente_sab) && isset($hora_fim_expediente_sab)){
                                if(!($hora_inicio_expediente_sab=="") && !($hora_fim_expediente_sab=="")){
                                    $def->HoraInicioExpedienteSabado_hora = $hora_inicio_expediente_sab;
                                    $def->HoraFimExpedienteSabado_hora = $hora_fim_expediente_sab;               
                                }
                            }

                            if (isset($hora_inicio_expediente_dom) && isset($hora_fim_expediente_dom)){
                                if(!($hora_inicio_expediente_dom=="") && !($hora_fim_expediente_dom=="")){
                                    $def->HoraInicioExpedienteDomingo_hora = $hora_inicio_expediente_dom;
                                    $def->HoraFimExpedienteDomingo_hora = $hora_fim_expediente_dom;               
                                }
                            }                        

                            if (!$def->save())
                                Yii::app()->user->setFlash('error', "Ocorreu um erro ao salvar o horário do órgão ".$org->id_orgao);
                        }
                    }
                    Yii::app()->user->setFlash('success', "Horário salvo com sucesso!");
                }
                else {
                    Yii::app()->user->setFlash('error', "Ocorreu um erro ao salvar o horário.");
                }
            }            
            else {
                Yii::app()->user->setFlash('error', "Ocorreu um erro ao salvar o horário. ".$msg);
            }
            $this->redirect(array('horariosOrgaos', array('Orgaos'=>$orgao->id_orgao)));
        }
        else
            throw new CHttpException(400, "Erro ao processar solicitação");
    }    
}