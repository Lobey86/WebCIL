$(document).ready(function () {
    $("#filtrage").click(function () {
        $("#divFiltrage").slideToggle(400);
    });

    $('#checkArch').change(function () {
        $('#checkNonArch').prop('checked', false);
    });
    $('#checkNonArch').change(function () {
        $('#checkArch').prop('checked', false);
    });
});