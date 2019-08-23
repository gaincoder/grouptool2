// app.js

require('../../scss/global.scss');
require('../../scss/default/closed_area.scss');

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');
// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');
$(document).ready(function () {
    $('#carprev').click(function(){
        $("#myCarousel").carousel('prev');
    });
    $('#carnext').click(function () {
        $("#myCarousel").carousel('next');
    });
    $("#myCarousel").swiperight(function () {
        $(this).carousel('prev');
    });
    $("#myCarousel").swipeleft(function () {
        $(this).carousel('next');
    });
});