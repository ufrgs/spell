
/**
 * **horarioOrgao.js**
 * 
 * Função para habilitar e desabilitar a alteração de horários dos finais de 
 * semana.
 * 
 * @returns {void} Desabilita a alteração de horários do sábado
 */
function checkSabado(){
    $('.sabado').attr('disabled', !$('#checkboxSabado').is(':checked'));
    $('.sabado').attr('value', '');  
    $( "#sliderHorarioSabado" ).slider("option", {disabled:  !$('#checkboxSabado').is(':checked')});
}

/**
 * **horarioOrgao.js**
 * 
 * Função para habilitar e desabilitar a alteração de horários dos finais de 
 * semana.
 * 
 * @returns {void} Desabilita a alteração de horários do domingo
 */
function checkDomingo() {
    console.log("habilita domingo");
    $('.domingo').attr('disabled', !$('#checkboxDomingo').is(':checked'));
    $('.domingo').attr('value', '');
    $( "#sliderHorarioDomingo" ).slider("option", {disabled: !$('#checkboxDomingo').is(':checked')});
}

/**
 * **horarioOrgao.js**
 * 
 * Função utlizada para mostrar a carga horária de um órgão.
 * 
 * @returns {void} Mostra os horários do órgão selecionado
 */
function selecionaOrgao() {
    if ($('#Orgaos').val() != '') {
        $('#horarios').load(HOME + 'horarios/horariosOrgaos', 'Orgaos='+ $('#Orgaos').val(), function(){
            $(".hora").mask("99:99");
            $(".sabado").mask("99:99");
            $(".domingo").mask("99:99");
            $('.sabado').attr('disabled', !$('#checkboxSabado').is(':checked'));
            $('.domingo').attr('disabled', !$('#checkboxDomingo').is(':checked'));
            acionaSliders();
            ativaInputs();
            acionaForm();
            validaHorarios();
        });
        
    }
}

/**
 * **horarioOrgao.js**
 * 
 * Função utilizada para validar o campo do horário. Aqui é verificado se tem 
 * algum valor, se o mesmo é numérico e se segue o formato HH:MM.
 * 
 * @param {JSON} objeto Valor existen no campo que foi alterado
 * @returns {void} Mostra mensagem de erro na tela caso o horário seja inválido
 */
function validaCampo(objeto) {
    console.log("objeto: " + JSON.stringify(objeto));
    if (!/^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])$/i.test($(objeto).val())) {
        alert("Escreva um horário válido.");
        $(objeto).attr('value', '');
    }
    validaHorarios();
}

/**
 * **horarioOrgao.js**
 * 
 * Função utilizada para salvar os horários definidos.
 * 
 * @returns {void} Mostra uma mensagem na tela informando sucesso ou falha na operação
 */
function acionaForm(){
    $("#formHorarios").on("submit", function(e){
        e.preventDefault();
        var dados = ($( this ).serializeArray());
        validaHorarios();              
        
        $.ajax({
            url: HOME + 'horarios/salvarHorarios',
            type : "POST",
            dataType: "json",
            data: dados,
            beforeSend: function(){
                $("#divMensagemRetorno").removeClass();
                $("#divMensagemRetorno").html("");
                $("#formHorarios").hide();
            },
            success: function(data){                    
                $("#divMensagemRetorno").html(data.mensagem);
                $("#divMensagemRetorno").addClass(data.tipo);
                $("#formHorarios").show();
                $("#divMensagemRetorno").show("slow");
                setTimeout(function(){ $("#divMensagemRetorno").hide("slow"); }, 5000);
            }
        });
        
    });
}

/**
 * **horarioOrgao.js**
 * 
 * Função utilizada para verificar a coerência dos horários inseridos. Aqui é
 * feita a validação que garante que o horário de início de um período de 
 * trabalho é menor doque o horário final, por exemplo.
 * 
 * @returns {object} Referência para o campo com conteúdo inválido
 */
function validaHorarios(){
    var erro = null;
    
    /* Horarios de entrada e saida */
    if ($("#inLimitehora_inicio_expediente").val() > $("#inhora_inicio_expediente").val()){        
         $("#inhora_inicio_expediente").addClass("error");
        erro =  $("#inhora_inicio_expediente");
    }else{
        $("#inhora_inicio_expediente").removeClass("error");
    }
    if ($("#inLimitehora_fim_expediente").val() < $("#inhora_fim_expediente").val()){
        $("#inhora_fim_expediente").addClass("error");
        erro =  $("#inhora_inicio_expediente");
    }
    else{
        $("#inhora_fim_expediente").removeClass("error");
    }
    
    /* Horarios de entrada e saida no sabado */   
    if (($("#inLimitehora_inicio_expediente_sabado").val() > $("#inhora_inicio_expediente_sabado").val()) && $('#checkboxSabado').is(':checked')){        
         $("#inhora_inicio_expediente_sabado").addClass("error");
        erro =  $("#inhora_inicio_expediente");
    }else{
        $("#inhora_inicio_expediente_sabado").removeClass("error");
    }
    if (($("#inLimitehora_fim_expediente_sabado").val() < $("#inhora_fim_expediente_sabado").val()) && $('#checkboxSabado').is(':checked')){
        $("#inhora_fim_expediente_sabado").addClass("error");
        erro =  $("#inhora_fim_expediente_sabado");
    }
    else{
        $("#inhora_fim_expediente_sabado").removeClass("error");
    }

    /* Horarios de entrada e saida no domingo */
    if ($("#inLimitehora_inicio_expediente_domingo").val() > $("#inhora_inicio_expediente_domingo").val() && $('#checkboxDomingo').is(':checked')){        
         $("#inhora_inicio_expediente_domingo").addClass("error");
        erro =  $("#inhora_inicio_expediente");
    }else{
        $("#inhora_inicio_expediente_domingo").removeClass("error");
    }
    if ($("#inLimitehora_fim_expediente_domingo").val() < $("#inhora_fim_expediente_domingo").val() && $('#checkboxDomingo').is(':checked')){
        $("#inhora_fim_expediente_domingo").addClass("error");
        erro =  $("#inhora_fim_expediente_domingo");
    }
    else{
        $("#inhora_fim_expediente_domingo").removeClass("error");
    }
    
    return erro;
}

/**
 * **horarioOrgao.js**
 * 
 * Função utilizada para habilitar a alteração de horários utilizando um slider.
 * 
 * @returns {void} Mostra o slider para definição do horário
 */
function acionaSliders(){     
    $( "#sliderHorarioExpediente" ).slider({
        range: true,
        min: converteHorasEmMinutos($("#inLimitehora_inicio_expediente").val()),
        max: converteHorasEmMinutos($("#inLimitehora_fim_expediente").val()),
        step: 10,
        values: [ converteHorasEmMinutos($("#inhora_inicio_expediente").val()), converteHorasEmMinutos($("#inhora_fim_expediente").val()) ],
        slide: function( event, ui ) {
          $( "#inhora_inicio_expediente" ).val(converteMinutosEmHoras(ui.values[ 0 ]));
          $( "#inhora_fim_expediente" ).val(converteMinutosEmHoras(ui.values[ 1 ]));
          validaHorarios();
        }
    }); 
    $( "#sliderHorarioSabado" ).slider({
        range: true,
        min: converteHorasEmMinutos($("#inLimitehora_inicio_expediente_sabado").val()),
        max: converteHorasEmMinutos($("#inLimitehora_fim_expediente_sabado").val()),
        disabled: !$('#checkboxSabado').is(':checked'),
        step: 10,
        values: [ converteHorasEmMinutos($("#inhora_inicio_expediente_sabado").val()), converteHorasEmMinutos($("#inhora_fim_expediente_sabado").val()) ],
        slide: function( event, ui ) {
          $( "#inhora_inicio_expediente_sabado" ).val(converteMinutosEmHoras(ui.values[ 0 ]));
          $( "#inhora_fim_expediente_sabado" ).val(converteMinutosEmHoras(ui.values[ 1 ]));
        }
    });    
    $( "#sliderHorarioDomingo" ).slider({
        range: true,
        disabled: !$('#checkboxDomingo').is(':checked'),
        min: converteHorasEmMinutos($("#inLimitehora_inicio_expediente_domingo").val()),
        max: converteHorasEmMinutos($("#inLimitehora_fim_expediente_domingo").val()),
        step: 10,
        values: [ converteHorasEmMinutos($("#inhora_inicio_expediente_domingo").val()), converteHorasEmMinutos($("#inhora_fim_expediente_domingo").val()) ],
        slide: function( event, ui ) {
          $( "#inhora_inicio_expediente_domingo" ).val(converteMinutosEmHoras(ui.values[ 0 ]));
          $( "#inhora_fim_expediente_domingo" ).val(converteMinutosEmHoras(ui.values[ 1 ]));
        }
    }); 
}

/**
 * **horarioOrgao.js**
 * 
 * Função utilizada para habilitar os campos para preenchimento de horários
 * caso o período a ser editado esteja habilitado.
 * 
 * @returns {void} Habilita edição nos campos de horário do período
 */
function ativaInputs(){
    $("#inhora_inicio_expediente, #inhora_fim_expediente").on("blur",function(){
        $( "#sliderHorarioExpediente" ).slider("option", "values", [converteHorasEmMinutos($("#inhora_inicio_expediente").val()), converteHorasEmMinutos($("#inhora_fim_expediente").val())]);
        validaHorarios();
    });
}

/**
 * **horarioOrgao.js**
 * 
 * Função auxiliar utlizada na função <code>acionaSliders()</code> para conversão
 * de horários em um determinado formato.
 * 
 * @param {string} horas Quantidade de horas no formato HH:MM
 * @returns {int} A quantidade de horas informada convertida para minutos
 */
function converteHorasEmMinutos(horas){
    if(!horas){
        horas = "00:00";
    }
    var h = horas.split(":");
    var hora = parseInt(h[0])*60;
    var minuto = parseInt(h[1]);
    
    return (hora+minuto);
}

/**
 * **horarioOrgao.js**
 * 
 * Função auxiliar utlizada na função <code>acionaSliders()</code> para conversão
 * de horários em um determinado formato.
 * 
 * @param {int} min Quantidade em minutos a ser convertido para formato de horas
 * @returns {String} Horário formatado no padrão HH:MM
 */
function converteMinutosEmHoras(min){    
    var horas = Math.floor(min/60);
    var minutos = min%60;
    horas = (horas < 10 ? "0"+horas : horas );
    minutos = (minutos < 10 ? "0"+minutos : minutos);
    
    return horas+":"+minutos;
}
