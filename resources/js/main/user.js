var user_ob = function () {
    this.tiny_gl_configs = {
        'browser_spellcheck': true,
        'init_instance_callback': "this.tiny_gl_configs.shortcutsTinyMCEFunctions",
        'toolbar1': 'formatselect styleselect | bold italic underline | forecolor backcolor link | alignleft aligncenter alignright alignjustify superscript subscript | numlist bullist | removeformat code',
        'image_advtab': true,
        'paste_as_text': true,
        'plugins': [
            'advlist autolink lists link image charmap preview hr anchor pagebreak',
            'searchreplace wordcount code paste',
            'media nonbreaking save table directionality',
            'textpattern imagetools hr'
        ],
        'style_formats' : [
            { title: 'Highlight', inline: 'span', classes: 'tincy-style-2' },
            { title: 'Very Important', inline: 'span', classes: 'tincy-style-3' },
            { title: 'Keywords', inline: 'span', classes: 'tincy-style-1' },
            { title: 'Expected', inline: 'span', classes: 'tincy-style-4' },
            { title: 'Focus', inline: 'span', classes: 'tincy-style-5' }
        ],
        'formats' : {
            fmt_hl : {inline: 'span', classes: 'tincy-style-2'},
            fmt_vi : {inline: 'span', classes: 'tincy-style-3'},
            fmt_kw : {inline: 'span', classes: 'tincy-style-1'},
            fmt_ex : {inline: 'span', classes: 'tincy-style-4'},
            fmt_fc : {inline: 'span', classes: 'tincy-style-5'}
        },
        shortcutsTinyMCEFunctions : function(editor) {
            editor.shortcuts.add("alt+shift+q", "Clear Formatting", "removeformat");
            editor.shortcuts.add("alt+shift+a", "Highlight", function(){
                editor.execCommand('removeformat');
                editor.formatter.apply('fmt_hl');
            });
            editor.shortcuts.add("alt+shift+s", "Very Important", function(){
                editor.execCommand('removeformat');
                editor.formatter.apply('fmt_vi');
            });
            editor.shortcuts.add("alt+shift+d", "Keywords", function(){
                editor.execCommand('removeformat');
                editor.formatter.apply('fmt_kw');
            });
            editor.shortcuts.add("alt+shift+f", "Expected", function(){
                editor.execCommand('removeformat');
                editor.formatter.apply('fmt_ex');
            });
            editor.shortcuts.add("alt+shift+g", "Focus", function(){
                editor.execCommand('removeformat');
                editor.formatter.apply('fmt_fc');
            });
        }
    };

    this.tiny_gl_small_configs = {
        'browser_spellcheck': true,
        'init_instance_callback': "this.tiny_gl_configs.shortcutsTinyMCEFunctions",
        'plugins': [
            'lists link hr anchor table',
        ],
        'toolbar1': 'formatselect styleselect table | bold italic underline | forecolor backcolor link | alignleft aligncenter alignright alignjustify superscript subscript | numlist bullist | removeformat code',
        'image_advtab': true,
        'menubar':false,
        'statusbar':false,
        'paste_as_text': true,
        'style_formats' : [
            { title: 'Highlight', inline: 'span', classes: 'tincy-style-2' },
            { title: 'Very Important', inline: 'span', classes: 'tincy-style-3' },
            { title: 'Keywords', inline: 'span', classes: 'tincy-style-1' },
            { title: 'Expected', inline: 'span', classes: 'tincy-style-4' },
            { title: 'Focus', inline: 'span', classes: 'tincy-style-5' }
        ],
        'formats' : {
            fmt_hl : {inline: 'span', classes: 'tincy-style-2'},
            fmt_vi : {inline: 'span', classes: 'tincy-style-3'},
            fmt_kw : {inline: 'span', classes: 'tincy-style-1'},
            fmt_ex : {inline: 'span', classes: 'tincy-style-4'},
            fmt_fc : {inline: 'span', classes: 'tincy-style-5'}
        }
    };

    // countdown
    this.startTimer = function(ptr, timer_dom, time_left){
        if (typeof pub_globes.timer === 'undefined') {
            pub_globes.timer = {};
        }
        if(time_left !== "00:00:00"){
            window.clearInterval(pub_globes.timer[ptr]);
            var secleft = moment.duration(time_left).asSeconds();
            var end_date = moment().add(secleft, 's');

            pub_globes.timer[ptr] = countdown(function(ts) {
                if(ts.value > 0){
                    let cd = moment.utc(ts.value).format('HH:mm:ss');
                    $(timer_dom).html(cd);
                } else{
                    window.clearInterval(pub_globes.timer[ptr]);
                }
            }, end_date, countdown.SECONDS);
        } 
    };

    // essay
    var esrfire = 1;
    this.motionButtonClick = function(event){
        if(esrfire){
            esrfire = 0;
            let response = $(event.currentTarget).closest('.reaction-section').attr('data-response');
            let motion = $(event.currentTarget).val();
            let motion_dom = $(event.currentTarget).closest('.reaction-motion');
            $.ajax({
                url:"/essay-response/summon",
                type:"POST",
                dataType: "json",
                data:{
                    type: 'user_emotion',
                    response: response,
                    motion: motion
                },
                success: function(data){
                    $(motion_dom).find('button.btn-outline-success').removeClass('btn-outline-success').addClass('btn-outline-secondary');
                    $(motion_dom).find('button[value="'+ motion +'"]').addClass('btn-outline-success');
                    _.each(data.reaction, function(value, key){
                        $(motion_dom).find('button[value="'+ key +'"] .reaction-counter').html(value);
                    });
                },
                error: function(error) {
                    if(error.status == 404){
                        bootbox.alert(error.responseJSON.message);
                    }
                },
                complete: function() {
                    esrfire = 1;
                }
            });
        }
    };

    this.yourScoreClick = function(event){
        if(esrfire){
            esrfire = 0;
            let response = $(event.currentTarget).closest('.reaction-section').attr('data-response');
            let user_score = $(event.currentTarget).find('option:selected').val();
            let motion_dom = $(event.currentTarget).closest('.reaction-section').find('.user-comments');

            $.ajax({
                url:"/essay-response/summon",
                type:"POST",
                dataType: "json",
                data:{
                    type: 'user_score',
                    response: response,
                    user_score: user_score
                },
                success: function(data){
                    bootbox.alert(data.message);
                },
                error: function(error) {
                    if(error.status == 404){
                        bootbox.alert(error.responseJSON.message);
                    }
                },
                complete: function() {
                    esrfire = 1;
                }
            });
        }
    };

    this.yourCommentClick = function(event){
        if(esrfire){
            esrfire = 0;
            let response = $(event.currentTarget).closest('.reaction-section').attr('data-response');
            let comment = $(event.currentTarget).closest('.your-comment').find('textarea').val();
            let user_comment_dom = $(event.currentTarget).closest('.reaction-section').find('.user-comments');

            $.ajax({
                url:"/essay-response/summon",
                type:"POST",
                dataType: "json",
                data:{
                    type: 'user_comment',
                    response: response,
                    user_comment: comment
                },
                success: function(data){
                    if($(user_comment_dom).find('.active').length){
                        $(user_comment_dom).find('.active').html(comment);
                    } else{
                        let commentml = '<li class="list-group-item bg-transparent active">'+ comment +'</li>';
                        $(user_comment_dom).append(commentml);
                    }
                },
                error: function(error) {
                    if(error.status == 404){
                        bootbox.alert(error.responseJSON.message);
                    }
                },
                complete: function() {
                    esrfire = 1;
                }
            });
        }
    };
};

var userob = new user_ob();

$(document).ready(function() {

/*** Global ***/
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    if($('form.sub-picker').length){
        var prev_cat = 0;
        $('form.sub-picker select[name="sub"]').on('change', function(){
            $('form.sub-picker #subject-heading input[name="topic_id"]').val(0);
            $('form.sub-picker #subject-heading input.sublabel').val('');
            $('#frame_modal .modal-dialog').empty();
            prev_cat = 0;
        });

        $('#select_heading').click(function(e){
            var sel_cat = $('form.sub-picker select[name="sub"] option:selected').val();
            if(prev_cat != sel_cat){
                prev_cat = sel_cat;
                $.ajax({
                    url:"/syllabus/picker?sub=" + sel_cat,
                    type:"GET",
                    dataType: "html",
                    data:{
                    },
                    success: function(data){
                        $('#frame_modal .modal-dialog').html(data);
                        $('#frame_modal').modal('show');
                    },
                    complete: function(){
                
                    }
                });
            } else{
                $('#frame_modal').modal('show');
            } 
        });

        $('#frame_modal').on("click", "#select-picker-container .modal-footer .pick", function(event){
            var sel_rad = $('#frame_modal #select-picker-container .modal-body li input[name="syllabus-pick"]:checked');
            if($(sel_rad).length){
                var label = $(sel_rad).closest('li').find('> .label').html();
                var subid = $(sel_rad).val();
                $('#subject-heading input[name="topic_id"]').val(subid);
                $('#subject-heading input.sublabel').val(label);
                $('#frame_modal').modal('hide');
            }
            event.stopPropagation();
        });
    }

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

        $('#media-list').on('click','.delete-current', function(){
            let ttl = $(this).attr('title');
            let del_type = $(this).attr('del-attr');
            let del_item = $(this).attr('data-path');
            bootbox.confirm("Do you want to " + ttl, function(result){
                if(result){
                    $.ajax({
                        url:"/user/media/summon",
                        type:"POST",
                        dataType: "json",
                        data:{
                            type: del_type,
                            item: del_item
                        },
                        success: function(data){
                            if(data.message == "1"){
                                bootbox.alert(ttl + " done");
                                window.location.reload();
                            }
                        },
                        error: function(error) {
                            if(error.status == 404){
                                bootbox.alert(error.responseJSON.message);
                            }
                        }
                    });
                }
            })
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
    } else if($('body').hasClass('news')){
    // news

        if($('.wyswyg-form textarea.wyswyg-bg').length){
            let tiny_lc_configs = $.extend({
                selector: '.wyswyg-form textarea.wyswyg-bg',
                height: 500,
            }, userob.tiny_gl_configs);
            tinymce.init(tiny_lc_configs);
        }
        if($('.wyswyg-form textarea.wyswyg-sm').length){
            let tiny_lc_configs = $.extend({
                selector: '.wyswyg-form textarea.wyswyg-sm',
                height: 300,
            }, userob.tiny_gl_small_configs);
            tinymce.init(tiny_lc_configs);
        }

        $('#create-note-form').on('submit', function(e){
            if($(this)[0].checkValidity()) {
                let topic = $('#create-note-form input[name="topic_id"]').val();
                if(topic.length === 0){
                    $('#create-note-form input.sublabel').addClass('is-invalid');
                    e.preventDefault();
                    return false;
                } else{
                    $('#create-note-form input.sublabel').removeClass('is-invalid');
                }
                $('#create-note-form').submit();
            }

            e.preventDefault();
            return false;
        });
    } else if($('body').hasClass('essays')){
    // essays page

        function checkTimer(essay_pick, essay_id)
        {
            $.ajax({
                url:"/essays/summon",
                type:"POST",
                dataType: "json",
                data:{
                    type: 'pick_essay',
                    eid: essay_id
                },
                success: function(data){
                    userob.startTimer(essay_id, $(essay_pick).closest('.essay-box').find('.essay-timer'), data.time_left);
                    if((data.status == "progress") && !$(essay_pick).closest('.essay-box').find('.essay-pickers .active').length){
                        let pick_box = $(essay_pick).closest('.essay-box').find('.essay-pickers');
                        if($.trim($(pick_box).html())){
                            $(pick_box).append(', ');
                        }
                        $(pick_box).append('<span class="active">' + pub_globes.user +'</span>');
                    }
                    bootbox.alert(data.message);
                },
                error: function(error) {
                    if(error.status == 404){
                        bootbox.alert(error.responseJSON.message);
                    }
                }
            });
        }
        $('.essay-action .btn-pick').click(function(){
            var essay_pick = $(this);
            let eid = $(essay_pick).closest('.essay-box').attr('data-id');
            checkTimer(essay_pick, eid);
        });

        $('.essay-box').each(function(){
            let essay_id = $(this).attr('data-id');
            let iscd = $(this).attr('data-countdown');
            if(iscd == 1){
                let time_left = $(this).find('.essay-timer').html();
                userob.startTimer(essay_id, $(this).find('.essay-timer'), time_left);
            }
        });
        
        $('.reaction-section .reaction-motion button').click(userob.motionButtonClick);
        $('.reaction-section .your-score').change(userob.yourScoreClick);
        $('.reaction-section .your-comment button').click(userob.yourCommentClick);

    } else if($('body').hasClass('essay-response')){
        let essay_id = $('#create-essay-response-form input[name="id"]').val();
        let time_left = $('#create-essay-response-form .essay-timer').html();
        userob.startTimer(essay_id, $('.essay-timer'), time_left);
        if($('.wyswyg-form textarea.wyswyg-bg').length){
            let tiny_lc_configs = $.extend({
                selector: '.wyswyg-form textarea.wyswyg-bg',
                height: 500,
            }, userob.tiny_gl_configs);
            tinymce.init(tiny_lc_configs);
        }

        $('.reaction-section .reaction-motion button').click(userob.motionButtonClick);
        $('.reaction-section .your-score').change(userob.yourScoreClick);
        $('.reaction-section .your-comment button').click(userob.yourCommentClick);
    }
});
