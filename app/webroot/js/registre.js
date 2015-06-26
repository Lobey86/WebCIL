$(document).ready(function () {

    $('#filtrage').click(function () {
        $('#divFiltrage').show();
        $('#filtrage').hide();
    });

    $('#checkArch').change(function () {
        $('#checkNonArch').prop('checked', false);
    });
    $('#checkNonArch').change(function () {
        $('#checkArch').prop('checked', false);
    });

});