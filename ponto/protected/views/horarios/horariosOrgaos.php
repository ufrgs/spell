
<form method="POST">
    
    <?php
        echo CHtml::label('Selecione o órgão', 'Orgaos', array('class' => 'esquerdaAlinhado maior'));
        echo CHtml::dropDownList(
            'Orgaos', 
            isset($_POST['Orgaos']) ? $_POST['Orgaos'] : '',
            CHtml::listData(
                $orgaos,
                'id_orgao', 
                'nome_orgao'
            ), 
            array(
                'onchange' => 'selecionaOrgao()',
                'empty' => ' ------------------ '
            )
        );       
    ?>
    
</form>

<div id="horarios">
    <? if(!is_null($definicao)) {
        echo $this->renderPartial('exibirHorariosOrgao', array('orgao' => $orgao, 'definicao' => $definicao, 'podeEditar' => true, 'empty' => false, 'teste' => 1), false, false);
    } ?>
</div>