<?php

/* 
    Document   : RelatorioController
    Created on : 06/05/2016, 16:04:30
    Author     : thiago
*/

class RelatorioController extends BaseController
{
    public function actionIndex()
    {
        $orgaosChefia = Helper::getHierarquiaOrgaosChefia(Yii::app()->user->id_pessoa);
        $orgaosChefia = Helper::coalesce(implode(',', $orgaosChefia), 0);
        $orgaos = Orgao::model()->findAll(array(
            'select' => "id_orgao, sigla_orgao, nome_orgao",
            'condition' => "id_orgao in (
                $orgaosChefia
            )",
            'order' => 'nome_orgao'
        ));
        if (!empty($orgaos)) {
            $this->render('index', array(
                'orgaos' => $orgaos
            ));
        }
        else {
            // nao e chefe
            $this->render('/registro/mensagem', array('mensagem' => 'Você não possui cargo de chefia.', 'classe' => 'Info'));
        }
    }
    
    /**
     * 
     * Dado um orgao, busca os ultimos 12 meses disponiveis para relatorio
     * @param int $orgao
     * @return HTML do select
     */
    public function actionBuscaUltimos12Meses($orgao)
    {
        $orgaosConsultar = Helper::getHierarquiaDescendenteOrgao($orgao);
        $orgaosConsultar = Helper::coalesce(implode(',', $orgaosConsultar), 0);
        $sql = "select 
                    distinct CH.ano, CH.mes, CH.data_inicio_mes
                from orgao O
                    join dado_funcional DF on
                        O.id_orgao = DF.orgao_exercicio
                    join ch_mes_servidor CH on
                        DF.matricula = CH.matricula
                        and DF.nr_vinculo = CH.nr_vinculo
                where
                    O.id_orgao in (
                        $orgaosConsultar
                    )
                order by 
                    CH.data_inicio_mes desc
                limit 12 ";
        $periodos = Yii::app()->db->createCommand($sql)->queryAll(true);
        $this->renderPartial('_periodos', array(
            'periodos' => $periodos,
        ));
    }
    
    public function actionExibeCargaHorariaConsolidada()
    {
        if (isset($_GET['orgao'], $_GET['periodo'])) {
            $periodo = explode("/", $_GET['periodo']);
            $mes = $periodo[0];
            $ano = $periodo[1];
            $orgao = Orgao::model()->findByPk($_GET['orgao']);
            $orgaosConsultar = Helper::getHierarquiaDescendenteOrgao($orgao->id_orgao);
            $orgaosConsultar = Helper::coalesce(implode(',', $orgaosConsultar), 0);
            $orgaosChefia = Helper::getHierarquiaOrgaosChefia(Yii::app()->user->id_pessoa);
            $orgaosChefia = Helper::coalesce(implode(',', $orgaosChefia), 0);
            $dataProviderRegistros = new CActiveDataProvider(CargaHorariaMesServidor::model(), array(
                'criteria' => array(
                    'with' => array(
                        'Pessoa' => array('select' => 'nome_pessoa'), 
                        'DadoFuncional' => array('select' => 'regime_trabalho, orgao_exercicio'),
                        'DadoFuncional.OrgaoExercicio' => array('select' => 'nome_orgao'),
                        'DadoFuncional.CatFuncional' => array('select' => 'nome_categoria'),
                    ),
                    'select' => 't.*',
                    'condition' => "DadoFuncional.data_desligamento is null
                        and DadoFuncional.data_aposentadoria is null
                        and DadoFuncional.orgao_exercicio in (
                            $orgaosChefia
                        ) and DadoFuncional.orgao_exercicio in (
                            $orgaosConsultar
                        ) and t.mes = :mes and t.ano = :ano",
                    'params' => array(
                        ':mes' => $mes,
                        ':ano' => $ano,
                    ),
                ),
                'pagination' => false,
                'sort' => array(
                    'attributes' => array(
                        '*',
                        'Pessoa.nome_pessoa' => array(
                            'asc' => 'Pessoa.nome_pessoa asc',
                            SORT_ASC => 'Pessoa.nome_pessoa asc',
                            'desc' => 'Pessoa.nome_pessoa desc',
                            SORT_DESC => 'Pessoa.nome_pessoa desc',
                        ),
                        'DadoFuncional.CatFuncional.nome_categoria' => array(
                            'asc' => 'CatFuncional.nome_categoria asc',
                            SORT_ASC => 'CatFuncional.nome_categoria asc',
                            'desc' => 'CatFuncional.nome_categoria desc',
                            SORT_DESC => 'CatFuncional.nome_categoria desc',
                        ),
                        'DadoFuncional.regime_trabalho' => array(
                            'asc' => 'DadoFuncional.regime_trabalho asc',
                            SORT_ASC => 'DadoFuncional.regime_trabalho asc',
                            'desc' => 'DadoFuncional.regime_trabalho desc',
                            SORT_DESC => 'DadoFuncional.regime_trabalho desc',
                        )
                    ),
                    'defaultOrder' => 'OrgaoExercicio.nome_orgao asc, Pessoa.nome_pessoa asc'
                )
            ));
            
            $dataProviderSemRegistros = new CActiveDataProvider(DadoFuncional::model(), array(
                'criteria' => array(
                    'with' => array(
                        'Pessoa' => array('select' => 'nome_pessoa'), 
                        'OrgaoExercicio' => array('select' => 'nome_orgao'),
                        'CatFuncional' => array('select' => 'nome_categoria'),
                        'GrupoEmprego' => array(
                            'joinType' => 'inner join',
                            'on' => "GrupoEmprego.segmento_grupo = 'T'"
                        ),
                    ),
                    'select' => 'orgao_exercicio, regime_trabalho',
                    'condition' => "t.data_desligamento is null
                        and t.data_aposentadoria is null
                        and t.orgao_exercicio in (
                            $orgaosChefia
                        ) and t.orgao_exercicio in (
                            $orgaosConsultar
                        ) and not exists (
                            select 1 from ch_mes_servidor
                            where
                                matricula = t.matricula
                                and nr_vinculo = t.nr_vinculo
                                and mes = :mes and ano = :ano
                        )",
                    'params' => array(
                        ':mes' => $mes,
                        ':ano' => $ano,
                    ),
                ),
                'pagination' => false,
                'sort' => array(
                    'attributes' => array(
                        '*',
                        'Pessoa.nome_pessoa' => array(
                            'asc' => 'Pessoa.nome_pessoa asc',
                            SORT_ASC => 'Pessoa.nome_pessoa asc',
                            'desc' => 'Pessoa.nome_pessoa desc',
                            SORT_DESC => 'Pessoa.nome_pessoa desc',
                        ),
                        'CatFuncional.nome_categoria' => array(
                            'asc' => 'CatFuncional.nome_categoria asc',
                            SORT_ASC => 'CatFuncional.nome_categoria asc',
                            'desc' => 'CatFuncional.nome_categoria desc',
                            SORT_DESC => 'CatFuncional.nome_categoria desc',
                        ),
                        'DadoFuncional.regime_trabalho' => array(
                            'asc' => 'DadoFuncional.regime_trabalho asc',
                            SORT_ASC => 'DadoFuncional.regime_trabalho asc',
                            'desc' => 'DadoFuncional.regime_trabalho desc',
                            SORT_DESC => 'DadoFuncional.regime_trabalho desc',
                        )
                    ),
                    'defaultOrder' => 'OrgaoExercicio.nome_orgao asc, Pessoa.nome_pessoa asc'
                )
            ));
            
            $this->render('exibicao', array(
                'orgao' => $orgao,
                'ano' => $ano,
                'mes' => $mes,
                'periodo' => $mes.'/'.$ano,
                'dataProviderRegistros' => $dataProviderRegistros,
                'dataProviderSemRegistros' => $dataProviderSemRegistros,
            ));
        }
        else {
            $this->redirect("index");
        }
    }
}