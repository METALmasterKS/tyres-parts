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
        var parentid = jQuery(this).attr('parentid');
        var url = jQuery(this).attr('url');
        jQuery.ajax({
            type: "POST",
            url: url,
            data: {
                sort : sort,
                parentId : parentid
            },
            dataType: "json",
            error: function (XMLHttpRequest, textStatus, errorThrown) {},
            success: function (data) {
                if (data.success == true) 
                    flashMessage('success', ['Порядок сохранен']);
                else if (data.success == false) 
                    flashMessage('danger', ['Внимание! Не удалось сохранить порядок']);
            }
        });
    });
    
    //ckeditor init
    if ( jQuery('[id^=ckeditor]').length ){
        jQuery('[id^=ckeditor]').each(function(){
            
            //CKFinder.start();
            
            var ckeditor = CKEDITOR.replace(jQuery(this).attr('id'), {
                extraPlugins: 'cut',
                toolbar : 'Сut,100',
                
                filebrowserBrowseUrl: '/admin/content/browsefiles?resourceType=Files',//'/ckfinder/ckfinder.html?resourceType=Files',
                filebrowserImageBrowseUrl: '/admin/content/browsefiles?resourceType=Images',//'/ckfinder/ckfinder.html?resourceType=Images',
                filebrowserUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                filebrowserImageUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                filebrowserWindowWidth: '1000',
                filebrowserWindowHeight: '700'
                /*
                filebrowserBrowseUrl: '/app/ckeditor/browse',
                filebrowserUploadUrl: '/app/ckeditor/upload',
                filebrowserImageBrowseUrl: '/app/ckeditor/browse?type=Images',
                filebrowserImageUploadUrl: '/app/ckeditor/upload?type=Images'
                */
            });
            CKFinder.setupCKEditor(ckeditor);
            
        });
    }
    
});

function flashMessage(type, messages) {
    var template = '<div class="alert alert-dismissible" role="alert"> \
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> \
    </div>';
    
    jQuery('.flash-message-block').html(template);
    var block = jQuery('.flash-message-block').children();
    block.addClass('alert-'+type);    
    jQuery.each(messages, function (index, message){
        block.append('<p>'+message+'</p>');
    });
}