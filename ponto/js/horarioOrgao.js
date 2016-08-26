/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function checkSabado(){
    $('.sabado').attr('disabled', !$('#checkboxSabado').is(':checked'));
    $('.sabado').attr('value', '');    
}

function checkDomingo() {
    $('.domingo').attr('disabled', !$('#checkboxDomingo').is(':checked'));
    $('.domingo').attr('value', '');
}

function selecionaOrgao() {
    if ($('#Orgaos').val() != '') {
        $('#horarios').load(HOME + 'horarios/horariosOrgaos', 'Orgaos='+ $('#Orgaos').val(), function(){
            $(".hora").mask("99:99");
            $(".sabado").mask("99:99");
            $(".domingo").mask("99:99");
            $('.sabado').attr('disabled', !$('#checkboxSabado').is(':checked'));
            $('.domingo').attr('disabled', !$('#checkboxDomingo').is(':checked'));
        });
        
    }
}

function validaCampo(objeto) {
    if (!/^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])$/i.test($(objeto).val())) {
        alert("Escreva um horário válido.");
        $(objeto).attr('value', '');
    }
}

