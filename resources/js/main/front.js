function window_resized() {
    if (window.matchMedia('(max-width: 767px)').matches) {
        // header
        $('header nav .sidebar-toggler').click(function() {
            var sbar = $(this).attr('data-sidebar');
            var clside = (sbar == 'left') ? 'right' : 'left';

            $('header nav .sidebar-toggler.' + clside + '-bar-toggle').find('i').removeClass('fa-angle-double-' + clside).addClass('fa-angle-double-' + sbar);
            $('main .' + clside + '-side').addClass('d-none');

            $(this).toggleClass('prec-hdn');
            $(this).find('i').toggleClass('fa-angle-double-right fa-angle-double-left');

            $('main .' + sbar + '-side').toggleClass('d-none');
        });

        // close others
        $('header .search-toggler .search-box').addClass('dropdown-menu');
    } else {
        $('header .search-toggler .search-box').removeClass('dropdown-menu');
    }
}

$(document).ready(function() {
// global
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // resize
    window_resized();
    var resizeTimer;
    $(window).on('resize', function(e) {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            window_resized();
        }, 500);
    });

    // ------ click outside ------
    // header
    $('.navbar .search-toggler, .navbar .profile').on('show.bs.dropdown', function() {
        $('#navbarSupportedContent').collapse('hide');
    });
    $(document).click(function(e) {
        if (!$(".navbar-toggler").is(e.target) && $(".navbar-toggler").has(e.target).length === 0 && !$(e.target).closest('#navbarSupportedContent').length) {
            $('#navbarSupportedContent').collapse('hide');
        }
    });
    $('[data-toggle="tooltip"]').tooltip();

    if($('#full-clndr').length){
        let popN = {};

        function getMonthEvents(month){
            let m = moment(month).startOf('month').format("YYYY_MM")
            if(_.has(popN, m)){
                myCalendar.setEvents(popN[m]);
            } else{
                let s = moment(month).startOf('month').format("YYYY-MM-DD"); 
                let e = moment(month).endOf('month').format("YYYY-MM-DD"); 
                $.ajax({
                    url:pub_globes.clndr_summon,
                    type:"POST",
                    dataType: "json",
                    data:{
                        type: 'month_events',
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
                getMonthEvents(moment().format());
            },
            clickEvents: {
                click: function(target) {
                    if($(target.element).hasClass('event')){
                        let dtck = moment(target.date).format("YYYY-MM-DD");    
                    }
                },
                onMonthChange: function (month) {
                    getMonthEvents(month);
                }
            }
        });

        $('#full-clndr').on('click', '.clndr-grid .day a', function(){
            return $(this).closest('.day').hasClass('event');
        });
    }

    if($('#daily-questions').length){
        $('#today-answers').click(function(){
            var ans = {};
            $.each($('.ques-wrapper'), function() {
                ans[$(this).attr('data-ques')] = $(this).find(".form-check-input").is(":checked") ? $(this).find(".form-check-input:checked").val() : 0;
            });

            $.ajax({
                url:"/news/summon",
                type:"POST",
                dataType: "json",
                data:{
                    type: 'news_questions',
                    ans: JSON.stringify(ans),
                    date: $('#news_date').val()
                },
                success: function(data){
                    $('#your-score').html(data.score);
                    
                    if(data.ques_ans){
                        _.each(data.ques_ans, function(dt, q){
                            let a = dt.ans;
                            $('#question-block-'+q).find('.question-options li').has('.form-check-input[value="'+a+'"]').addClass('active');

                            $('#question-block-'+q).find('.explanation-block').removeClass('d-none').find('.question-explanation').empty().html(JSON.parse(dt.exp));
                        });
                    }

                    if(data.ust == '2'){
                        bootbox.alert("Only first attempts are considered for median score");    
                    }

                    if(data.ust == '3'){
                        bootbox.alert("Subscribe and track your progress");    
                    }

                },
                error: function(data){
                    bootbox.alert(data);
                }
            });
        });
    }

    /*** Pages ***/
    if($('body').hasClass('syllabus')){
        $('#syllabusSelect').on('change', function(){
            let subref = $(this).find('option:selected').attr('data-url');
            window.location.href = subref;
        });

        $('.toggle-answer').click(function(){
            $(this).closest('.card').find('.options li[data-right="1"]').toggleClass('text-success');
            $(this).closest('.card').find('.answer-block').collapse('toggle');
        })
    } else if($('body').hasClass('user_registeration')){
        $('#user_registration input[name="name"], #user_registration input[name="email"]').on("input", function(){
            let tp = $(this).attr('name');
            let vl = $(this).val();
            let ts = $(this);
            $(ts).closest('.form-group').find('.feedback-msg').remove();
            if(vl.length>4){
                $.ajax({
                    url:"/rear/user-register",
                    type:"POST",
                    dataType: "json",
                    data:{
                        type: tp,
                        val: vl
                    },
                    success: function(data){
                        if(data.code == "0"){
                           $(ts).removeClass('is-valid').addClass('is-invalid');
                           $(ts).closest('.form-group').append('<span class="invalid-feedback feedback-msg" role="alert"><strong>'+data.message+'</strong></span>');
                        } else{
                            $(ts).removeClass('is-invalid').addClass('is-valid');
                            $(ts).closest('.form-group').append('<span class="valid-feedback feedback-msg" role="alert"><strong>'+data.message+'</strong></span>');
                        }
                    },
                    error: function(error) {
                        
                    }
                });
            }
        });
    }
});
