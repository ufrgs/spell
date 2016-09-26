$(document).ready(function () {
    
    /**
     * **calendario.js**
     * 
     * Evento que permitir ao servidor acompanhar o horário de outro vínculo caso
     * o mesmo esteja relacionado a mais de um vínculo.
     * 
     * @ignore
     * @event Evento onChange. Quando o valor do campo é alterado essa ação é executada
     */
    $("#selVinculo").change(function() {
        if ($(this).val() != '') {
            window.location = HOME + 'calendario/index/?v='+$(this).val();
        }
    });

    /**
     * **calendario.js**
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
            window.location = HOME + 'calendario/index/?v='+$("#nrVinculo").val()+'&a='+$(this).val();
        }
    });

    /**
     * **calendario.js**
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
            window.location = HOME + 'calendario/index/?v='+$("#nrVinculo").val()+'&a='+$("#ano").val()+'&m='+$(this).val();
        }
    });
});