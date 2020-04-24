
var tiny_gl_configs = {
    'browser_spellcheck': true,
    'init_instance_callback': "tiny_gl_configs.shortcutsTinyMCEFunctions",
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

$(document).ready(function() {

// global
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('select[name="subject_switch"').change(function(){
        let subref = $(this).val();
        window.location.href = subref;
    });

    // subject picker
        $('#stash_form select[name="sub"]').on('change', function(){
            $('#subject-heading input[name="topic_id"]').val(0);
            $('#subject-heading input.sublabel').val('Pick...');
            $('#frame_modal .modal-dialog').empty();
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

        var prev_cat = 0;
        $('#select_heading').click(function(e){
            var sel_cat = $('#stash_form select[name="sub"] option:selected').val();
            if(prev_cat != sel_cat){
                prev_cat = sel_cat;
                $.ajax({
                    url:"/super/syllabus/picker?sub=" + sel_cat,
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
    // date pickers
        $('.pick-date').datepicker({
            autoclose: true,
            todayHighlight: true,
            todayBtn: true,
            format: 'yyyy-mm-dd'
        });

    /*** Pages ***/
    
    if($('body').hasClass('news')){
    // news
        /*show starts*/
        function fetchNews(dt)
        {
            if(typeof dt === 'undefined'){
                let searchParams = new URLSearchParams(window.location.search);
                if(searchParams.has('date')){
                    dt = searchParams.get('date');
                } else{
                    let now = new Date();
                    dt = date.format(now, 'YYYY-MM-DD');
                }
            }

            $.ajax({
                url:"/super/news/show",
                type:"POST",
                dataType: "html",
                data:{
                    date: dt
                },
                success: function(data){
                    $('#news_display').html(data);
                }   ,
                complete: function(){
                    news_fired = 0;
                }
            });
        }

        if($('#news_calendar_show').length){
            fetchNews();    
        }
        
        $('#news_calendar_show').datepicker({
            todayBtn: true,
            todayHighlight: true,
            toggleActive: true,
            beforeShowDay: function(date){
                if (date.getMonth() == (new Date()).getMonth())
                switch (date.getDate()){
                    case 4:
                    case 26:
                        return {
                            tooltip: 'Click to fetch news',
                            classes: 'active'
                        };
                }
            }
        }).on('changeDate', function(e) {
            var dt = e.date;
            var fmdt = date.format(dt, 'YYYY-MM-DD');
            fetchNews(fmdt);
        });

        /*Stash starts */
        if($('#stash_form textarea[name="news_content"]').length){
            let tiny_lc_configs = $.extend({
                selector: '#stash_form textarea[name="news_content"]',
                height: 500,
            }, tiny_gl_configs);
            tinymce.init(tiny_lc_configs);
        }

    } else if($('body').hasClass('media-lib')){
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
    } else if($('body').hasClass('theory')){
    // theory
        /*Stash starts */
        $('.select2').select2();
        if($('#stash_form textarea[name="theory_content"]').length){
            let tiny_lc_configs = $.extend({
                selector: '#stash_form textarea[name="theory_content"]',
                height: 500,
            }, tiny_gl_configs);
            tinymce.init(tiny_lc_configs);
        }
        if($('#stash_form textarea[name="theory_summary"]').length){
            let tiny_lc_configs = $.extend({
                selector: '#stash_form textarea[name="theory_summary"]',
                height: 500,
            }, tiny_gl_configs);
            tinymce.init(tiny_lc_configs);
        }
    } else if($('body').hasClass('essays')){
    // essays
        function fetchEssays(dt)
        {
            if(typeof dt === 'undefined'){
                let searchParams = new URLSearchParams(window.location.search);
                if(searchParams.has('date')){
                    dt = searchParams.get('date');
                } else{
                    let now = new Date();
                    dt = date.format(now, 'YYYY-MM-DD');
                }
            }

            $.ajax({
                url:"/super/essays/show",
                type:"POST",
                dataType: "html",
                data:{
                    date: dt
                },
                success: function(data){
                    $('#essays_display').html(data);
                }
            });
        }

        if($('#essays_calendar_show').length){
            fetchEssays();    
        }
        
        $('#essays_calendar_show').datepicker({
            todayBtn: true,
            todayHighlight: true,
            toggleActive: true
        }).on('changeDate', function(e) {
            var dt = e.date;
            var fmdt = date.format(dt, 'YYYY-MM-DD');
            fetchEssays(fmdt);
        });


        // stash
        if($('#stash_form textarea[name="essay_framework"]').length){
            let tiny_lc_configs = $.extend({
                selector: '#stash_form textarea[name="essay_framework"]',
                height: 500,
            }, tiny_gl_configs);
            tinymce.init(tiny_lc_configs);
        }
    } else if($('body').hasClass('syllabus')){
    // sylabus
        $('.card-tools select[name="subject_switch"]').on('change', function(){
            let subref = $(this).find('option:selected').attr('data-url');
            window.location.href = subref;
        });
    }
});
