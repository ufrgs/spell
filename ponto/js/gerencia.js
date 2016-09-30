
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

/**
 * **gerencia.js**
 * 
 * Função para mostrar a jornada de trabalho de um servidor pesquisado na tela
 * de gerência.
 * 
 * O parâmetro "dados" é passado no seguinte formato: <code>{p: 0}</code>
 * 
 * Para realizar a filtragem por data a função utiliza a seguinte representação: 
 * ```
 * {
 *  p: 3, // Chave primária da pessoa
 *  v: 1, // Número do vínculo da pessoa
 *  a: 2016, // Ano a ser visualizado
 *  m: 4 // Número do mês
 * }
 * ```
 * 
 * @param {int} JSON contendo a chave p com a chave primária da pessoa como valor
 * @returns {void} Mostra os registros de horário da pessoa pesquisada
 */
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

/**
 * **gerencia.js**
 * 
 * Funçao criada para excluir registros de ajustes e abonos no menu da gerência.
 * 
 * @param {int} nr Chave primária do pedido
 * @param {string} tipo Tipo de pedido (ajuste ou abono)
 * @returns {void} Atualiza a tela após a remoção do pedido ou mostra mensagem de erro
 */
function excluir(nr, tipo) {
    if (confirm("Tem certeza que deseja excluir esse pedido de ajuste?")) {
        $.ajax({
            type: 'POST',
            url: HOME + 'gerencia/excluirPedido',
            data: { nr: nr, tipo: tipo },
            success: function (result) {
                alert(result);
                if (result.indexOf("sucesso") != -1)
                    document.location.reload(true);
            },
            error: function (result) {
                alert(result);
            }
        });
    }
}

/**
 * **gerencia.js**
 * 
 * Script para definir os eventos da página de acompanhamento, como os circulos
 * que exibem a jornada de trabalho do servidor pesquisado e os elementos de 
 * seleção de datas para filtragem de carga horária.
 * 
 * @returns {void} Inicializa os eventos da página
 */
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