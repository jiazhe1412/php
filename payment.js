/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */


$(document).ready(function () {
    $('#card').click(function () {
        $('#cardInfo').show();
        $('#tngInfo').hide();
    });

    $('#tng').click(function () {
        $('#cardInfo').hide();
        $('#tngInfo').show();
    });

    if ($('#card').is(':checked')) {
        $('#cardInfo').show();
        $('#tngInfo').hide();
    }

    if ($('#tng').is(':checked')) {
        $('#cardInfo').hide();
        $('#tngInfo').show();
    }

});