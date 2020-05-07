$(document).ready(function() {
    
/*** Pages ***/
    
    if($('body').hasClass('user-media')){
    // media
        function show_media(currjson){
            var fold_tmpl = _.template($('#media-render-folder').html());
            var img_tmpl = _.template($('#media-render-image').html());
            _.each(currjson, function(value, key){
                if(key == 'folder_path'){
                    $('#media-footer').val(value);
                    return;
                }
                if(_.isString(value)){
                    var im_html = img_tmpl({
                        'imageurl' : value,
                        'imagename' : key
                    });
                    $('#media-list ul').append(im_html);
                }
                if(_.isObject(value)){
                    var fl_html = fold_tmpl({
                        'folderpath' : value.folder_path,
                        'foldername' : key
                    });
                    $('#media-list ul').append(fl_html);
                }
            });
        }
        show_media(mdjson);

        function currentMedia(iter)
        {
            $('#media-list ul').empty();
            var result = '';
            if(iter.length){
                var back_tmpl = _.template($('#media-render-back').html());
                $('#media-list ul').append(back_tmpl({}));
                result = iter.map(function(key){
                        return "['" + key + "']";
                    }).join("");
            }
            show_media(eval('mdjson' + result));
        }

        var curr_obj = [];
        $('#media-list').on('click','a.album', function(){
            var key = $(this).find('span').html();
            curr_obj.push(key);
            currentMedia(curr_obj);
        });
        $('#media-list').on('click','a.back', function(){
            curr_obj.pop();
            currentMedia(curr_obj);
        });

        $('input[type=file]#imageUploadImage').change(function(e){
            let name = $(this).val();
            let newname = name.replace("C:\\fakepath\\", "");
            let labelid = $(this).attr('id');
            $('label[for='+ labelid +']').html(newname);
        });

        // picker
        if (window.parent != null) {
            $('#media-list.media-picker').on('click','a.pick-image', function(){
                $(this).addClass('active');
                $('#media-list.media-picker').find('a.pick-image').not(this).removeClass('active');
                var attr = $(this).attr('data-href');
                window.parent.$("#opener_media_select").val(attr);
            });
        }
    }
});
