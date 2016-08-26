
function selecionaOrgao() {
    if ($('#orgao').val() != '') {
        $('#periodos').load(HOME + 'relatorio/buscaUltimos12Meses', 'orgao='+ $('#orgao').val());
    }
}