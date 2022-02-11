
$(document).ready(function() {
    $("#valor").maskMoney({allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
    $('[name="quantidade"]').mask("999999");
});
