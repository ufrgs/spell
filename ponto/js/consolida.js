/**
 * **consolida.js**
 * 
 * Função utilizada para consolidar os registros do mês. Todos os servidores
 * com registro no mês selecionado terão seus horários consolidados.
 * 
 * @returns {void} Mostra na tela uma mensagem de sucesso ou erro na operação
 */
function servidores() {
    $("#mensagem").html('<img src="/ponto/css/imgs/bigLoader.gif" alt="Carregando..."/>');
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

/**
 * **consolida.js**
 * 
 * Função utilizada para consolidar horários de um lote de servidores de um 
 * determinado mês e ano.
 * 
 * @returns {void} Mostra na tela uma mensagem de sucesso ou erro na operação
 */
function lote() {
    $("#mensagem").html('<img src="/ponto/css/imgs/bigLoader.gif" alt="Carregando..."/>');
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
