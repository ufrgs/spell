
$(document).ready(function() {
    iniciaScripts();
    
    if ($("#pessoaAcompanhamento").val() != 0) {
        renderizaAcompanhamento({ p: $("#pessoaAcompanhamento").val()});
    }
    
    $("#acServidor").autocomplete({
        source: HOME + "gerencia/servidores",
        minLength: 3,
        delay: 500,
        select: function (event, ui) {
            $("#pessoaAcompanhamento").val(ui.item.id);
            renderizaAcompanhamento({ p: ui.item.id});
        }
    });
});

function corrigePendencia() {
    $.ajax({
        url: HOME + "gerencia/corrigePendencias",
        success: function(retorno) {
            alert(retorno);
        },
        error: function(retorno) {
            alert(retorno);
        }
    });
}

function renderizaAcompanhamento(dados) {
    $.ajax({
        url: HOME + "acompanhamento/pessoa",
        type: "POST",
        data: dados,
        success: function(retorno) {
            $("#acompanhamento").html(retorno);
            iniciaScripts();
        },
        error: function(retorno) {
            alert(retorno);
        }
    });
}

function iniciaScripts() {
    $('#progressoDiario').circleProgress({
        value: $('#progressoDiario').attr('value'),
        size: 120,
        thickness: 15,
        fill: { color: "#ada" }
    });
    $('#progressoSemanal').circleProgress({
        value: $('#progressoSemanal').attr('value'),
        size: 120,
        thickness: 15,
        fill: { color: "#6c6" }
    });
    $('#progressoMensalAteHoje').circleProgress({
        value: $('#progressoMensalAteHoje').attr('value'),
        size: 120,
        thickness: 15,
        fill: { color: "#3a3" }
    });
    $('#progressoMensal').circleProgress({
        value: $('#progressoMensal').attr('value'),
        size: 120,
        thickness: 15,
        fill: { color: "#181" }
    });
    $("#selVinculo").change(function() {
        if ($(this).val() != '') {
            renderizaAcompanhamento({p:$("#pessoaAcompanhamento").val(), v:$(this).val()});
        }
    });
    $("#ano").change(function() {
        if ($(this).val() != '') {
            renderizaAcompanhamento({p:$("#pessoaAcompanhamento").val(), v:$("#nrVinculo").val(), a:$(this).val()});
        }
    });
    $("#mes").change(function() {
        if ($(this).val() != '') {
            renderizaAcompanhamento({p:$("#pessoaAcompanhamento").val(), v:$("#nrVinculo").val(), a:$("#ano").val(), m:$(this).val()});
        }
    });
}