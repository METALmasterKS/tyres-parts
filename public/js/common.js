jQuery(document).ready(function () {
    //spinner инпуты с увеличением и уменьшением
    jsInputSpinRefresh();
    //ajax удаление
    jQuery(this).on('click', ".js-ajax-remove", function () {
        var clicked = jQuery(this);
        var postparamname = jQuery(this).attr('postparamname');
        var names = postparamname.split(',');
        var data = {};
        jQuery.each(names, function (i, val) {
            data[val] = clicked.attr(val);
        });
        var url = jQuery(this).attr('url');
        var removeselector = jQuery(this).attr('removeselector');
        var quest = jQuery(this).attr('confirm');
        
        if (confirm(quest ? quest : 'Вы уверены?') == false) 
            return false;
        
        jQuery.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "json",
            error: function (XMLHttpRequest, textStatus, errorThrown) {},
            success: function (data) {
                jQuery(removeselector).remove();
            }
        });
        return false;
    });
    
    //ajax массовое по галкам удаление
    jQuery(this).on('click', ".js-ajax-bulk-remove", function () {
        var checkboxselector = jQuery(this).attr('checkboxselector');
        var postparamname = jQuery(this).attr('postparamname');
        var values = [];
        jQuery(checkboxselector+':checked').each(function(){
            values.push(jQuery(this).val());
        });
        var url = jQuery(this).attr('url');
        var removeselector = jQuery(this).attr('removeselector');
        var quest = jQuery(this).attr('confirm');
        
        if (confirm(quest ? quest : 'Вы уверены?') == false) 
            return false;
        
        var data = {};
        data[postparamname] = values;
        jQuery.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "json",
            error: function (XMLHttpRequest, textStatus, errorThrown) {},
            success: function (data) {
                $.each(values, function (index, value){
                    jQuery(removeselector+value).remove();
                });

            }
        });
    });
    
    //ajax подгрузка в контейнер
    jQuery(this).on('click', ".js-ajax-load-to-container", function () {
        var clicked = jQuery(this);
        var postparamname = jQuery(this).attr('postparamname');
        var value = jQuery(this).attr(postparamname);
        var url = jQuery(this).attr('url');
        var containerselector = jQuery(this).attr('container');
        var data = {};
        data[postparamname] = value;
        jQuery.ajax({
            type: "GET",
            url: url,
            data: data,
            dataType: "html",
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                //statusMessage('Во время AJAX запроса произошла ошибка: '+textStatus+'!','show_img');
            },
            success: function (data) {
                jQuery(containerselector).html(data);
            }
        });
        return false;
    });
    
    //dpd загрузка городов по региону
    jQuery(this).on('change', 'select[name=dpd_region_id]', function(){
        var dpd_region_id = jQuery(this).children('option:selected').val();
        //Гифка загрузки
        //jQuery('.js-ajax-load').html('<img src="/images/design/ajax-loader.gif"/>'); 
        jQuery.ajax({
            type: "POST",
            url: "/app/dpd/ajaxgetcities",
            dataType: "html",
            data: {
                dpd_region_id: dpd_region_id
            },
            success: function (html) {
                //удаление гифки загрузки
                //jQuery('.js-ajax-load').html('');
                jQuery('select[name=dpd_city_id]').html(html);
            }
        });
	});
    
    //датепикер диапазон дат
    jQuery('.js-datetimepicker-start-end').each(function () {
        var datepickerstart = jQuery(this).find('.datetimepicker-start').datetimepicker();
        var datepickerend = jQuery(this).find('.datetimepicker-end').datetimepicker({
            useCurrent: false //Important! See issue #1075
        });
        datepickerstart.on("dp.change", function (e) {
            datepickerend.data("DateTimePicker").minDate(e.date);
        });
        datepickerend.on("dp.change", function (e) {
            datepickerstart.data("DateTimePicker").maxDate(e.date);
        });
    });
    
});


function jsInputSpinRefresh(){
    jQuery(".js-input-spin").each(function(){
        var step = (jQuery.inArray( jQuery(this).attr('step'), [undefined, '']) < 0 ? parseFloat(jQuery(this).attr('step')) : 1);
        var unit = (jQuery.inArray( jQuery(this).attr('unit'), [undefined, '']) < 0 ? parseFloat(jQuery(this).attr('unit')) : '');
        var decimals = (step == 1 ? 0 : 2);
        jQuery(this).TouchSpin({
            min: 0,
            max: 1000,
            step: step,
            decimals: decimals,
            boostat: 5,
            maxboostedstep: 10,
            postfix: unit,
            verticalupclass: 'glyphicon glyphicon-plus',
            verticaldownclass: 'glyphicon glyphicon-minus'
        });
    });
}