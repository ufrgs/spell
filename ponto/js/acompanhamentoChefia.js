$(document).ready(function() {
    iniciaScripts();

    /**
     * **acompanhamentoChefia.js**
     * 
     * Função que implementa o recurso de autocompletar no campo de pesquisa
     * de servidores.
     * 
     * @ignore
     * @event Evento de autocompletar do jQuery.
     * @link https://jqueryui.com/autocomplete/
     */
    $("#acServidor").autocomplete({
        source: HOME + "acompanhamento/subordinados",
        minLength: 3,
        delay: 500,
        select: function (event, ui) {
            carregaAcompanhamento(ui.item.id);
        }
    });
    
    // Se foi enviada uma pessoa por parametro, mas ainda não carregou
    if (($("#pessoaAcompanhamento").val() != '') && ($("#abaAtiva").val() == 'acompanhamento')) {
        carregaAcompanhamento($("#pessoaAcompanhamento").val())
    }
});

/**
 * **acompanhamentoChefia.js**
 * 
 * Função para mostrar os horários do servidor com a chave primária passada por
 * parâmetro.
 * 
 * @param {int} pessoa Chave primária da classe Pessoa referente ao servidor
 * @returns {void} Exibe na tela o horário do servidor ou uma mensagem de erro
 */
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

/**
 * **acompanhamentoChefia.js**
 * 
 * Script para definir os eventos da página de acompanhamento, como os circulos
 * que exibem a jornada de trabalho do servidor e os elementos de seleção de 
 * datas para filtragem de carga horária.
 * 
 * @returns {void} Inicializa os eventos da página
 */
function iniciaScripts() {
    
    /**
     * **acompanhamentoChefia.js**
     * 
     * Exibe o gráfico circular com a jornada diária.
     * 
     * @ignore
     * @event Evento da biblioteca Circle Progress para exibição e animação do circulo da jornada.
     * @link https://github.com/kottenator/jquery-circle-progress
     */
    $('#progressoDiario').circleProgress({
        value: $('#progressoDiario').attr('value'),
        size: 120,
        thickness: 15,
        fill: { color: "#ada" }
    });

    /**
     * **acompanhamentoChefia.js**
     * 
     * Exibe o gráfico circular com a jornada semanal.
     * 
     * @ignore
     * @event Evento da biblioteca Circle Progress para exibição e animação do circulo da jornada.
     * @link https://github.com/kottenator/jquery-circle-progress
     */
    $('#progressoSemanal').circleProgress({
        value: $('#progressoSemanal').attr('value'),
        size: 120,
        thickness: 15,
        fill: { color: "#6c6" }
    });

    /**
     * **acompanhamentoChefia.js**
     * 
     * Exibe o gráfico circular com a jornada mensal até o dia atual.
     * 
     * @ignore
     * @event Evento da biblioteca Circle Progress para exibição e animação do circulo da jornada.
     * @link https://github.com/kottenator/jquery-circle-progress
     */
    $('#progressoMensalAteHoje').circleProgress({
        value: $('#progressoMensalAteHoje').attr('value'),
        size: 120,
        thickness: 15,
        fill: { color: "#3a3" }
    });

    /**
     * **acompanhamentoChefia.js**
     * 
     * Exibe o gráfico circular com a jornada mensal.
     * 
     * @ignore
     * @event Evento da biblioteca Circle Progress para exibição e animação do circulo da jornada.
     * @link https://github.com/kottenator/jquery-circle-progress
     */
    $('#progressoMensal').circleProgress({
        value: $('#progressoMensal').attr('value'),
        size: 120,
        thickness: 15,
        fill: { color: "#181" }
    });

    /**
     * **acompanhamentoChefia.js**
     * 
     * Evento que permitir ao servidor acompanhar o horário de outro vínculo caso
     * o mesmo esteja relacionado a mais de um vínculo.
     * 
     * @ignore
     * @event Evento da biblioteca Circle Progress para exibição e animação do circulo da jornada.
     * @link https://github.com/kottenator/jquery-circle-progress
     */
    $("#selVinculo").change(function() {
        if ($(this).val() != '') {
            window.location = HOME + $("#abaAtiva").val() + '/acompanhamentoChefia/?v='+$(this).val();
        }
    });
    
    /**
     * **acompanhamentoChefia.js**
     * 
     * Evento para filtragem de datas de acompanhamento.
     * 
     * Esse filtro permite que o usuário selecione o tempo registrado de acordo
     * com um ano.
     * 
     * @ignore
     * @event Evento onChange. Quando o valor do campo é alterado essa ação é executada
     */
    $("#ano").change(function() {
        if ($(this).val() != '') {
            window.location = HOME + $("#abaAtiva").val() + '/acompanhamentoChefia/?v='+$("#nrVinculo").val()+'&a='+$(this).val();
        }
    });
    
    /**
     * **acompanhamentoChefia.js**
     * 
     * Evento para filtragem de datas de acompanhamento.
     * 
     * Esse filtro permite que o usuário selecione o tempo registrado de acordo
     * com um mês.
     * 
     * @ignore
     * @event Evento onChange. Quando o valor do campo é alterado essa ação é executada
     */
    $("#mes").change(function() {
        if ($(this).val() != '') {
            window.location = HOME + $("#abaAtiva").val() + '/acompanhamentoChefia/?v='+$("#nrVinculo").val()+'&a='+$("#ano").val()+'&m='+$(this).val();
        }
    });
}

/**
 * **acompanhamentoChefia.js**
 * 
 * Método para alteração entre as abas de visualização de horários do servidor
 * em formato de tabela e calendário.
 * 
 * Para visualizar a tabela de horários deve-se passar o parâmetro aba com o valor
 * <code>acompanhamento</code> e para visualizar em calendário passar o valor
 * <code>calendario</code>.
 * 
 * @alias {acompanhamentoChefia.js} - mudaAba
 * @param {string} aba Descrição da a aba a ser visualizada.
 * @returns {void} Muda o conteúdo da tela de acordo com a aba escolhida
 */
function mudaAba(aba) {
    if ($("#pessoaAcompanhamento").val() != '') {
        window.location = HOME + aba + '/acompanhamentoChefia/?v='+$("#nrVinculo").val()+'&a='+$("#ano").val()+'&m='+$("#mes").val();
    }
}