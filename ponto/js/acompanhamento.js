$(document).ready(function () {
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
            window.location = HOME + 'acompanhamento/index/?v='+$(this).val();
        }
    });
    $("#ano").change(function() {
        if ($(this).val() != '') {
            window.location = HOME + 'acompanhamento/index/?v='+$("#nrVinculo").val()+'&a='+$(this).val();
        }
    });
    $("#mes").change(function() {
        if ($(this).val() != '') {
            window.location = HOME + 'acompanhamento/index/?v='+$("#nrVinculo").val()+'&a='+$("#ano").val()+'&m='+$(this).val();
        }
    });
});

function ajusta(nrPonto, novoRegistro) {
    if (novoRegistro == undefined)
        novoRegistro = '';
    window.location = HOME + 'ajuste/pedido/?v='+$("#nrVinculo").val()+'&n='+nrPonto+'&td='+novoRegistro;
}