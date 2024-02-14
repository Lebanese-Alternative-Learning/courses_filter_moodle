// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 * @package    courses_search
 * @copyright  2023 Muhmammed Alaaaldin <mhd.alaaaldeen@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$(document).ready(function () {


    $('.courses-search-btn').on('click', function () {
        let parentid = $(this).attr('data-formid');
        $('#' + parentid + ' .courses-search-page').val(0);
        loadpage(parentid);
        return false;
    });

    $('#block-region-side-top .form-group').each(function () {
        let placeholder = $(this).find('label').text().replaceAll('  ', '');


        var cl3="col-md-3";
        var cl9="col-md-9";
        if ($(this).find('[data-fieldtype="select"]').length) {
            //$(this).find('.'+cl3).remove();
            $(this).find('.'+cl3).attr('style','width: 100%; max-width: 100%; flex: unset;');
            //$(this).find('.col-lg-3 ').attr('class','');
            $(this).find('.'+cl9).attr('style', ' width:100%');
            $(this).find('.'+cl9+' select').attr('style', ' width:100%');
            $(this).find('.'+cl9).attr('class', '');

            let select = $(this).find('[data-fieldtype="select"] select');


            let name = $(select).attr('name');

            if (name.includes('_any')) {
                $(select).find('option:first').remove();
                let options = $(select).html();
               // $(select).html('<option selected value="">Any ' + placeholder + '</option>' + options);
                $(select).html('<option selected value="">Any</option>' + options);
            }
            if (name.includes('_multiple')) {
                $(select).attr('name', $(select).attr('name') + '[]');
                $(select).attr('multiple', 'multiple');
                $(select).parent().append(' <i style="font-size: 12px"></i>');
            }

        }
        if ($(this).find('[data-fieldtype="text"]').length) {
           // $(this).find('.'+cl3).remove();
            $(this).find('.'+cl3).attr('style','width: 100%; max-width: 100%; flex: unset;');
            //$(this).find('.col-lg-3 ').attr('class','');
            $(this).find('.'+cl9).attr('class', '');
            $(this).find('input').attr('placeholder', placeholder);
        }


    })

});


function orderby(parentid, v) {
    $('#' + parentid + ' .courses-search-orderby').val(v);
    loadpage(parentid);
}

function change_perpage(parentid, v) {
    $('#' + parentid + ' .courses-search-perpage').val(v);
    $('#' + parentid + ' .courses-search-page').val(0);
    loadpage(parentid);
}

function loadpage(parentid) {


    url = $('#' + parentid + '  .ajaxurl').val();
    order = $('#' + parentid + ' .courses-search-orderby').val();
    page = $('#' + parentid + ' .courses-search-page').val();
    perpage = $('#' + parentid + ' .courses-search-perpage').val();


    var d = {};
    $('#' + parentid + ' .filter_element').each(function () {
        d[$(this).attr('name')] = $(this).val();
    });
    let container = parentid;
    if ($('#formid-custom-search-page').length) {
        container = 'formid-custom-search-page';
        $('#' + parentid + ' .mform input[name="display"]').val('body');
    }
    $('#' + container + ' .main-items-container  .items').empty();
    $('#' + container + '  .main-items-container ').attr('style', 'min-height: 200px');
    $('#' + container + '  .loading ').fadeIn();


    console.log($('#' + parentid + ' .mform').serialize());
    console.log(d);
    jQuery.ajax({
        type: "POST",
        data: $('#' + parentid + ' .mform').serializeArray(),
        url: url,
        success: function (data) {

            $('#' + container + '  .main-items-container ').attr('style', 'min-height: unset');
            $('#' + container + ' .loading').fadeOut(0);

            var dom_nodes = $($.parseHTML(data));

            $('#' + container + ' .main-items-container .items').html(dom_nodes);
            $('#' + container + ' .pagination  .page-item').on('click', function (e) {
                e.preventDefault();
                page = $(this).data('page-number') - 1;
                $('#' + parentid + ' .courses-search-page').val(page);
                loadpage(parentid);
                $('html, body').animate({
                    scrollTop: $('#' + parentid + '   .items').offset().top - 100
                }, 1000);
            });

            $('#' + parentid + ' .select_perpage').on('change', function (e) {
                $('#' + parentid + ' .courses-search-perpage').val(this.value);
                $('#' + parentid + ' .courses-search-page').val(0);
                loadpage(parentid);
            });
        }
    });
    return true;
};