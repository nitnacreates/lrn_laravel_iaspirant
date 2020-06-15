
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

var tiny_gl_small_configs = {
    'browser_spellcheck': true,
    'init_instance_callback': "tiny_gl_configs.shortcutsTinyMCEFunctions",
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
        $(document).on('click', '.pick-date', function(){
            if( $(this).hasClass('hasDatepicker') === false )  {
                $(this).datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    todayBtn: true,
                    format: 'yyyy-mm-dd'
                });
            }
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
                    dt = moment(searchParams.get('date')).format("YYYY-MM-DD");
                } else{
                    dt = moment().format("YYYY-MM-DD");
                }
            }

            $('#select_date').html(moment(dt).format('DD MMM,YY'));

            $.ajax({
                url:"/super/news/show",
                type:"POST",
                dataType: "html",
                data:{
                    date: dt
                },
                success: function(data){
                    $('#news_display').html(data);
                }
            });
        }

        if($('#full-clndr').length){
            let popN = {};

            function getMonthNews(month){
                let m = moment(month).startOf('month').format("YYYY_MM")
                if(_.has(popN, m)){
                    myCalendar.setEvents(popN[m]);
                } else{
                    let s = moment(month).startOf('month').format("YYYY-MM-DD"); 
                    let e = moment(month).endOf('month').format("YYYY-MM-DD"); 
                    $.ajax({
                        url:"/super/news/summon",
                        type:"POST",
                        dataType: "json",
                        data:{
                            type: 'month_news',
                            sdt: s,
                            edt: e
                        },
                        success: function(data){
                            popN[m] = data;
                            myCalendar.setEvents(popN[m]);
                        }
                    });
                }
            }

            var myCalendar = $('#full-clndr').clndr({
                template: $('#full-clndr-template').html(),
                forceSixRows: true,
                ignoreInactiveDaysInSelection: true,
                constraints: {
                    startDate: '2020-03-10',
                    endDate: '2021-06-01'
                },
                ready: function(){
                    getMonthNews(moment().format());
                },
                clickEvents: {
                    click: function(target) {
                        if($(target.element).hasClass('event')){
                            let dtck = moment(target.date).format("YYYY-MM-DD");    
                            fetchNews(dtck);
                        }
                    },
                    onMonthChange: function (month) {
                        getMonthNews(month);
                    }
                }
            });

            fetchNews();

            $('#news_display').on('click', '#news_date_action', function(){
                let dt = $(this).attr('data-date');
                let act = $(this).val();
                $.ajax({
                    url:"/super/news/summon",
                    type:"POST",
                    dataType: "json",
                    data:{
                        type: 'date_lock',
                        date: dt,
                        act: act
                    },
                    success: function(){
                        fetchNews(dt);
                    }
                });
            });
        }

        /*Stash starts */
        if($('#stash_form textarea[name="news_content"]').length){
            let tiny_lc_configs = $.extend({
                selector: '#stash_form textarea[name="news_content"]',
                height: 500,
            }, tiny_gl_configs);
            tinymce.init(tiny_lc_configs);

            $('#stash_form #check_link').click(function(){
                let ln = $('#stash_form input[name="news_link"]').val();
                let id = $('#stash_form input[name="id"]').val();
                if(ln.length){
                    $.ajax({
                        url:"/super/news/summon",
                        type:"POST",
                        dataType: "json",
                        data:{
                            type: 'news_link',
                            link: ln,
                            id: id
                        },
                        success: function(data){
                            $('#stash_form input[name="news_link"]').removeClass('is-invalid').addClass('is-valid');
                        },
                        error: function(data){
                            $('#stash_form input[name="news_link"]').removeClass('is-valid').addClass('is-invalid');
                        }
                    });
                } else{
                    $('#stash_form input[name="news_link"]').removeClass('is-valid').addClass('is-invalid');
                }
                return true;
            });

            $('#show_dates').on('click', '.delete-date', function(){
                if($('#show_dates tbody tr').length > 1){
                    $(this).closest('tr').remove();
                }
            });

            let min_new_show = $('#add_new_show').val();
            $('#add_new_show').click(function(){
                let crcnt = parseInt($(this).val());
                let date_tmpl = _.template($('#render_show_dates').html());
                $('#show_dates tbody').append(date_tmpl({'cntr' : crcnt}));
                $(this).val(++crcnt);
            });
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
        if($('#stash_form').length){
            $('.select2').select2();
        }

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
                    dt = moment().format("YYYY-MM-DD");
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

        if($('#full-clndr').length){
            let popE = {};

            function getMonthEssays(month){
                let m = moment(month).startOf('month').format("YYYY_MM")
                if(_.has(popE, m)){
                    myCalendar.setEvents(popE[m]);
                } else{
                    let s = moment(month).startOf('month').format("YYYY-MM-DD"); 
                    let e = moment(month).endOf('month').format("YYYY-MM-DD"); 
                    $.ajax({
                        url:"/super/essays/summon",
                        type:"POST",
                        dataType: "json",
                        data:{
                            type: 'month_essays',
                            sdt: s,
                            edt: e
                        },
                        success: function(data){
                            popE[m] = data;
                            myCalendar.setEvents(popE[m]);
                        }
                    });
                }
            }

            var myCalendar = $('#full-clndr').clndr({
                template: $('#full-clndr-template').html(),
                forceSixRows: true,
                ignoreInactiveDaysInSelection: true,
                constraints: {
                    startDate: '2020-03-10',
                    endDate: '2021-06-01'
                },
                ready: function(){
                    getMonthEssays(moment().format());
                },
                clickEvents: {
                    click: function(target) {
                        if($(target.element).hasClass('event')){
                            let dtck = moment(target.date).format("YYYY-MM-DD");    
                            fetchEssays(dtck);
                        }
                    },
                    onMonthChange: function (month) {
                        getMonthEssays(month);
                    }
                }
            });

            fetchEssays(); 
        }


        // stash
        if($('#stash_form textarea[name="framework"]').length){
            let tiny_lc_configs = $.extend({
                selector: '#stash_form textarea[name="framework"]',
                height: 500,
            }, tiny_gl_configs);
            tinymce.init(tiny_lc_configs);
        }
        if($('#stash_form textarea[name="references"]').length){
            let tiny_lc_configs = $.extend({
                selector: '#stash_form textarea[name="references"]',
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
    } else if($('body').hasClass('question_bank')){
    // Question bank
        tinymce.init($.extend({
            selector: '#stash_form textarea[name="question"]',
            height: 300,
        }, tiny_gl_small_configs));
        tinymce.init($.extend({
            selector: '#stash_form textarea[name="secret_text"]',
            height: 200,
        }, tiny_gl_small_configs));

        let tiny_lc_configs = $.extend({
            selector: '#stash_form textarea[name="explanation"]',
            height: 500,
        }, tiny_gl_configs);
        tinymce.init(tiny_lc_configs);
    } else if($('body').hasClass('prelim_stash')){
    // Prelim Stash

        tinymce.init($.extend({
            selector: '#stash_form textarea[name="question"]',
            height: 300,
        }, tiny_gl_small_configs));
        tinymce.init($.extend({
            selector: '#stash_form textarea[name="secret_text"]',
            height: 200,
        }, tiny_gl_small_configs));

        let tiny_lc_configs = $.extend({
            selector: '#stash_form textarea[name="explanation"]',
            height: 500,
        }, tiny_gl_configs);
        tinymce.init(tiny_lc_configs);
    }
});
