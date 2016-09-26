/**
 * **relatorio.js**
 * 
 * Função utilizada para verificar os pedidos em um órgão realizados nos últimos
 * 12 mêses.
 * 
 * @returns {void} Mostra os pedidos na tela.
 */
function selecionaOrgao() {
    if ($('#orgao').val() != '') {
        $('#periodos').load(HOME + 'relatorio/buscaUltimos12Meses', 'orgao='+ $('#orgao').val());
    }
}