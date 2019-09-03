// app.js

require('../../scss/gemeinsam_bewegen/closed_area.scss');
require('../../scss/summernote.scss');

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');
require('../../js/summernote.js');
require('select2');
require('bootstrap4-toggle');

$(document).ready(function() {
    $('.summernote').summernote();
    $('select[data-select="true"]').select2();
});