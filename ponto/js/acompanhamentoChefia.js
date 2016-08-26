
$(document).ready(function() {
    iniciaScripts();
    
    $("#acServidor").autocomplete({
        source: HOME + "acompanhamento/subordinados",
        minLength: 3,
        delay: 500,
        select: function (event, ui) {
            carregaAcompanhamento(ui.item.id);
        }
    });
    
    // se foi enviada uma pessoa por parametro, mas ainda nao carregou
    if (($("#pessoaAcompanhamento").val() != '') && ($("#abaAtiva").val() == 'acompanhamento')) {
        carregaAcompanhamento($("#pessoaAcompanhamento").val())
    }
});

function carregaAcompanhamento(pessoa) {
    if (pessoa != '') {
        $.ajax({
            url: HOME + "acompanhamento/pessoa",
            type: "POST",
            data: { 
                p: pessoa, 
                a: $("#anoParametro").val(),
                m: $("#mesParametro").val(),
            },
            success: function(retorno) {
                $("#pessoaAcompanhamento").val(pessoa);
                $("#acompanhamento").html(retorno);
                $("#abasTipoAcompanhamento").show();
                iniciaScripts();
            },
            error: function(retorno) {
                alert(retorno);
            }
        });
    }
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
            window.location = HOME + $("#abaAtiva").val() + '/acompanhamentoChefia/?v='+$(this).val();
        }
    });
    $("#ano").change(function() {
        if ($(this).val() != '') {
            window.location = HOME + $("#abaAtiva").val() + '/acompanhamentoChefia/?v='+$("#nrVinculo").val()+'&a='+$(this).val();
        }
    });
    $("#mes").change(function() {
        if ($(this).val() != '') {
            window.location = HOME + $("#abaAtiva").val() + '/acompanhamentoChefia/?v='+$("#nrVinculo").val()+'&a='+$("#ano").val()+'&m='+$(this).val();
        }
    });
}

function mudaAba(aba) {
    if ($("#pessoaAcompanhamento").val() != '') {
        window.location = HOME + aba + '/acompanhamentoChefia/?v='+$("#nrVinculo").val()+'&a='+$("#ano").val()+'&m='+$("#mes").val();
    }
}