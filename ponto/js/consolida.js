function servidores() {
    $("#mensagem").html('<img src="/Design/visual_ufrgs/bigLoader.gif" alt="Carregando..."/>');
    $.ajax({
        url: HOME + "consolida/cargaHorariaServidores",
        type: "GET",
        data: {
            ano: $('#ano').val(),
            mes: $('#mes').val(),
        },
        success: function(retorno) {
            $("#mensagem").html(retorno);
        },
        error: function(retorno) {
            alert(retorno);
            $("#mensagem").html('');
        }
    });
}

function lote() {
    $("#mensagem").html('<img src="/Design/visual_ufrgs/bigLoader.gif" alt="Carregando..."/>');
    $.ajax({
        url: HOME + "consolida/cargaHorariaLote",
        type: "GET",
        data: {
            ano: $('#ano').val(),
            mes: $('#mes').val(),
            lote: $('#servidores').val(),
        },
        success: function(retorno) {
            $("#mensagem").html(retorno);
            $('#servidores').val('');
        },
        error: function(retorno) {
            alert(retorno);
            $("#mensagem").html('');
        }
    });
}
