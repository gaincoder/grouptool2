require('../../scss/gemeinsam_bewegen/public_area.scss');

const $ = require('jquery');
require('bootstrap');

require('../cookie.js');

require('select2');

$(document).ready(function() {
    $('select[data-select="true"]').select2();
});