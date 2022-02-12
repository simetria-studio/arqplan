
$(document).ready(function() {
    $("#valor").maskMoney({allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
    $('[name="quantidade"]').mask("999999");

$('.dropdown-toggle').on('click', function(){
    console.log('clicado');
    $('.dropdown-menu').addClass('show');
});

$('.dropdown-menu').on('click', function(){
    $('.dropdown-menu').removeClass('show');
    console.log('oi to aqui');
});

});

