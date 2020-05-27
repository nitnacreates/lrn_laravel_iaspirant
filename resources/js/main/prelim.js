$(document).ready(function() {

/*** Global ***/
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

/*** Comments ***/
    if($('section#comments').length){
        $('.comment-box .clear-content').click(function(){
            $('.comment-box .replying-to').addClass('d-none').removeClass('d-block');
            $('.comment-box textarea.user-comment').val("");
            $('.comment-box input[name="comment_thread"]').val("");
            $('.comment-box input[name="to_account"]').val("");
        });
        $('.comment-box button.comment-submit').click(function(){
            let tx = $('.comment-box textarea.user-comment').val();
            tx = _.escape(tx);
            $.ajax({
                url:"/comment/populate",
                type:"POST",
                dataType: "json",
                data:{
                    type: 'user_comment',
                    cmtx: tx,
                    cmpt: $('.comment-box input[name="comment_path"]').val(),
                    thread: $('.comment-box input[name="comment_thread"]').val(),
                    toacc: $('.comment-box input[name="to_account"]').val()
                },
                success: function(data){
                    if(data.message == 1){
                        bootbox.alert("Success!! You message will appear post moderation");
                    }
                },
                error: function(data){
                    console.log(data.message);
                },
                complete: function(){
                    $('.comment-box .replying-to').addClass('d-none').removeClass('d-block');
                    $('.comment-box textarea.user-comment').val("");
                    $('.comment-box input[name="comment_thread"]').val("");
                    $('.comment-box input[name="to_account"]').val("");
                }
            });
        });

        $('.parent-comment .user-response .send-reply').click(function(){
            let thread = $(this).closest('.parent-comment').attr('data-id');

            let tocm = $(this).closest('li').find('.user-to-comment').html();
            let toid = $(this).closest('li').find('.user-to-name').attr('data-user');
            let tonm = $(this).closest('li').find('.user-to-name').html();

            $('.comment-box input[name="comment_thread"]').val(thread);
            $('.comment-box input[name="to_account"]').val(toid);
            $('.comment-box .reply-for-message').html(tocm);
            $('.comment-box textarea.user-comment').val("@"+tonm+" ");

            $('.comment-box .replying-to').removeClass('d-none').addClass('d-block');
            $(".comment-box").get(0).scrollIntoView();
        });

        $('.parent-comment .user-response .comment-like').click(function(){
            let likes = $(this).find('.total-likes').html();
            likes = parseInt(likes) || 0;
            let target = $(this).find('.total-likes');
        });

        var cr_fr = 1;
        $('.parent-comment .user-response .user-reaction').click(function(){
            if(cr_fr){
                cr_fr = 0;

                let rt = $(this).attr('data-resp');
                let ctr = $(this).find('.reaction-counter').html();
                let cid = $(this).closest('li').attr('data-id');
                ctr = parseInt(ctr) || 0;
                $(this).find('.reaction-counter').html((ctr+1));
                let target = $(this).closest('.user-response');

                $.ajax({
                    url:"/comment/populate",
                    type:"POST",
                    dataType: "json",
                    data:{
                        type: 'user_reaction',
                        cmpt: $('.comment-box input[name="comment_path"]').val(),
                        cid: cid,
                        rt: rt
                    },
                    success: function(data){
                        if(data.hasOwnProperty("likes")){
                            $(target).find('.total-likes').html(data.likes);
                        }
                        if(data.hasOwnProperty("dislikes")){
                            $(target).find('.total-dislikes').html(data.dislikes);
                        }
                    },
                    error: function(data){
                        console.log(data.message);
                    },
                    complete: function(){
                        cr_fr = 1;
                    }
                });
            }
        });

    }
/*** Pages ***/
    if($('body').hasClass('prelim')){
        // prelims
        if($('#question_track').length){

            var prelim_ob = function () {
                this.tqs = parseInt($('#question-number').attr('data-total_ques'));

                this.check_point = function(){
                    $.ajax({
                        url:"/prelim/summon",
                        type:"POST",
                        dataType: "json",
                        data:{
                            type: 'check_point',
                            roll_id: pub_globes.roll_id,
                            answers: JSON.stringify(pub_globes.answers)
                        },
                        success: function(data){

                        },
                        error: function(data) {
                            if(data.hasOwnProperty("pl") && (data.pl == "1")){
                                bootbox.alert(data.message);
                                window.location.reload();
                            }
                        }
                    });
                };

                this.qcheck = function(q) {
                    pub_globes.answers[q] = pub_globes.answers[q] || {'c':'','r':'','e':[]}; 
                }

                this.load_ques = function(qid) {
                    this.qcheck(qid);
                    var qid = parseInt(qid);

                    if(qid && (qid <= preob.tqs)){
                        $('#question-number').html(qid + " / " + preob.tqs);
                        
                        if(qid == 1){
                            $('#prev_question').val(qid);
                        } else{
                            $('#prev_question').val((qid-1));
                        }

                        if(qid == 100){
                            $('#next_question, #review_question, #save_question').val(qid);
                        } else{
                            $('#next_question, #review_question, #save_question').val((qid+1));
                        }
                        
                        let qc = $('#question_block #pre-ques-' + qid)[0].outerHTML;
                        $('#current_question').empty().html(qc);

                        if(pub_globes.answers[qid]['r']){
                            $('#current_question').find('input[name="q_option"][value="'+ pub_globes.answers[qid]['r'] +'"]').prop('checked', true);    
                        }

                        if(pub_globes.answers[qid]['c']){
                            $('#current_question').find('input[name="q_option"][value="'+ pub_globes.answers[qid]['c'] +'"]').prop('checked', true);    
                        }
                        
                        _.each(pub_globes.answers[qid]['e'], function(num){
                            $('#current_question').find('.question-options label.option-label-'+num+ ' span').addClass('eliminate');
                        });

                        $('#question_track button').removeClass('active');
                        $('#question_track :button[value="'+qid+'"]').addClass('active');
                    }
                };

            };
            var preob = new prelim_ob();


            // options
            $('#current_question').on('click', '.question-options button', function(){
                let qs =parseInt($(this).closest('.ques-wrapper').attr('data-ques'));

                let co = $(this).val();
                // union is ok as q is unique
                if(_.contains(pub_globes.answers[qs]['e'], co)){
                    pub_globes.answers[qs]['e'] = _.reject(pub_globes.answers[qs]['e'], function(eo){ return eo == co});
                    $(this).closest('.form-check-label').find('.option-content').removeClass('eliminate');
                } else {
                    pub_globes.answers[qs]['e'] = _.union(pub_globes.answers[qs]['e'], [co]);
                    $(this).closest('.form-check-label').find('.option-content').addClass('eliminate');
                }
                preob.check_point();
            });

            // page load inits
            (function(){
                _.each(pub_globes.answers, function(v,k){
                    if(v.r){
                        $('#question_track :button[value="'+k+'"]').removeClass('btn-outline-secondary btn-success').addClass('btn-warning');
                    }
                    if(v.c){
                        $('#question_track :button[value="'+k+'"]').removeClass('btn-outline-secondary btn-warning').addClass('btn-success');
                    }
                })
            })();
            (function(){
                let ct = $('#countdown').html();
                let secleft = moment.duration(ct).asSeconds();
                let end_secs = moment().add(secleft, 's');

                var ctm = countdown(function(ts) {
                    if(ts.value > 0){
                        let cd = moment.utc(ts.value).format('HH:mm:ss');
                        $('#countdown').html(cd);
                    } else{
                        window.clearInterval(ctm);
                        window.location.reload();
                    }
                }, end_secs, countdown.SECONDS);
            })();
            preob.load_ques(1);

            $('#question_track button').click(function(){
                preob.load_ques($(this).val());
            });

            $('#prev_question').click(function(){
                let prev = parseInt($(this).val());
                if(prev){
                    preob.load_ques(prev);
                }
            });

            $('#next_question').click(function(){
                let nxt = parseInt($(this).val());
                let qid = parseInt($('#current_question').find('.ques-wrapper').attr('data-ques'));
                
                $('#current_question').find('input[name="q_option"]').prop('checked', false);
                $('#question_track :button[value="'+qid+'"]').removeClass('btn-warning btn-success').addClass('btn-outline-secondary');
                pub_globes.answers[qid]['c'] = '';
                pub_globes.answers[qid]['r'] = '';
                preob.check_point();

                if(nxt <= preob.tqs){
                    preob.load_ques(nxt);
                } else{
                    bootbox.alert('You are on last question');
                }
            });

            $('#review_question').click(function(){
                let nxt = parseInt($(this).val());
                let qid = parseInt($('#current_question').find('.ques-wrapper').attr('data-ques'));
                
                let rv = $('#current_question').find('input[name="q_option"]:checked').val();
                if(rv){
                    pub_globes.answers[qid]['r'] = rv;
                    pub_globes.answers[qid]['c'] = '';
                    preob.check_point();

                    $('#question_track :button[value="'+qid+'"]').removeClass('btn-outline-secondary btn-success').addClass('btn-warning');
                    if(nxt <= preob.tqs){
                        preob.load_ques(nxt);
                    } else{
                        bootbox.alert('You are on last question');
                    }
                } else{
                    bootbox.alert('No option selected');
                }
            });

            $('#save_question').click(function(){
                let nxt = parseInt($(this).val());
                let qid = parseInt($('#current_question').find('.ques-wrapper').attr('data-ques'));
                
                let cv = $('#current_question').find('input[name="q_option"]:checked').val();
                if(cv){
                    pub_globes.answers[qid]['c'] = cv;
                    pub_globes.answers[qid]['r'] = '';
                    preob.check_point();

                    $('#question_track :button[value="'+qid+'"]').removeClass('btn-outline-secondary btn-warning').addClass('btn-success');
                    if(nxt <= preob.tqs){
                        preob.load_ques(nxt);
                    } else{
                        bootbox.alert('You are on last question');
                    }
                } else{
                    bootbox.alert('No option selected');
                }
            });

            $('#end_test').click(function(){
                bootbox.confirm("This is your final submission. Are you sure?", function(result){
                    if(result){
                        $.ajax({
                            url:"/prelim/summon",
                            type:"POST",
                            dataType: "json",
                            data:{
                                type: 'end_roll',
                                roll_id: pub_globes.roll_id,
                                answers: JSON.stringify(pub_globes.answers)
                            },
                            success: function(data){
                                if(data.hasOwnProperty("pl") && (data.pl == "1")){
                                    bootbox.alert(data.message);
                                    window.location.reload();
                                }
                            },
                            error: function(data) {
                                if(data.hasOwnProperty("pl") && (data.pl == "1")){
                                    bootbox.alert(data.message);
                                    window.location.reload();
                                }
                            }
                        });
                    }
                });
            });

        }

        // charts
        if($('#attempt-area').length){

            $('#attempt-area .bar-chart').each(function(){
                let cbc = $(this);
                let roll = $(this).attr('data-roll');

                let crda = {'labels' : [], 'user_ans' : [], 'corrects' : []};

                _.each(pub_globes.bar_bag[roll], function(dt, sub){
                    crda["labels"].push(sub);
                    crda["user_ans"].push(parseInt(dt['u']));
                    crda["corrects"].push(parseInt(dt['t']));
                });

                new Chart(cbc, {
                    type: 'bar',
                    data: {
                        labels: crda.labels,
                        datasets: [{
                            label: 'Total',
                            data: crda.corrects,
                            backgroundColor: 'rgba(61, 112, 191, 0.2)',
                            borderWidth: 1
                        },{
                            label: 'Correct',
                            data: crda.user_ans,
                            backgroundColor: 'rgba(61, 112, 191, 0.8)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                ticks: {
                                    beginAtZero: true
                                },
                                stacked: true
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            });

            
            $('#attempt-area .pie-chart').each(function(){
                let cpc = $(this);
                new Chart(cpc, {
                    type: 'pie',
                    data: {
                        labels: ['Correct', 'Wrong', 'Skipped'],
                        datasets: [{
                            label: 'Total marks',
                            data: [$(cpc).attr('pie-correct'), $(cpc).attr('pie-wrong'), $(cpc).attr('pie-skipped')],
                            backgroundColor: [
                                '#cfe67c',
                                '#e67c7c',
                                '#e6d07c',
                            ]
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });
            });
        }
    }
});
