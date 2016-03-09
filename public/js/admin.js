jQuery(document).ready(function () {
    //сортировка в таблице
    $( ".table-sortable tbody" ).sortable({
        handle: 'span',
        update: function( event, ui ) {
            var sort = $(this).sortable('toArray');
            
        }
    });
    $( ".table-sortable tbody" ).disableSelection();
    //сохранение порядка по кнопке
    jQuery(this).on('click', ".js-ajax-save-sort", function () {
        var sort = $(".table-sortable tbody").sortable('toArray');
        var parentgroupid = jQuery(this).attr('parentgroupid');
        var url = jQuery(this).attr('url');
        jQuery.ajax({
            type: "POST",
            url: url,
            data: {
                sort : sort,
                parentGroupId : parentgroupid
            },
            dataType: "json",
            error: function (XMLHttpRequest, textStatus, errorThrown) {},
            success: function (data) {
                console.log(data);
            }
        });
    });
});
