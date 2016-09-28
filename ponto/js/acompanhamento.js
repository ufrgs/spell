$(document).ready(function () {
    
    /**
     * **acompanhamento.js**
     * 
     * Exibe o gráfico circular com a jornada semanal.
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
     * **acompanhamento.js**
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
     * **acompanhamento.js**
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
     * **acompanhamento.js**
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
     * **acompanhamento.js**
     * 
     * Evento que permitir ao servidor acompanhar o horário de outro vínculo caso
     * o mesmo esteja relacionado a mais de um vínculo.
     * 
     * @ignore
     * @event Evento onChange. Quando o valor do campo é alterado essa ação é executada
     */
    $("#selVinculo").change(function() {
        if ($(this).val() != '') {
            window.location = HOME + 'acompanhamento/index/?v='+$(this).val();
        }
    });

    /**
     * **acompanhamento.js**
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
            window.location = HOME + 'acompanhamento/index/?v='+$("#nrVinculo").val()+'&a='+$(this).val();
        }
    });

    /**
     * **acompanhamento.js**
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
            window.location = HOME + 'acompanhamento/index/?v='+$("#nrVinculo").val()+'&a='+$("#ano").val()+'&m='+$(this).val();
        }
    });
});

/**
 * **acompanhamento.js**
 * 
 * Função para realizar um pedido de ajuste no horário do servidor.
 * 
 * @param {int} nrPonto Identificador do ponto eletrônico. Chave primária da classe Ponto
 * @param {int} novoRegistro Indicador de novo registro
 * @returns {void} Redireciona o usuário para a tela com o pedido de ajuste
 */
function ajusta(nrPonto, novoRegistro) {
    if (novoRegistro == undefined) {
        novoRegistro = '';
    }
    window.location = HOME + 'ajuste/pedido/?v='+$("#nrVinculo").val()+'&n='+nrPonto+'&td='+novoRegistro;
}