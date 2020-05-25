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

            var tqs = parseInt($('#question-number').attr('data-total_ques'));
            
            function qcheck(q){
                pub_globes.answers[q] = pub_globes.answers[q] || {'c':'','r':'','e':[]}; 
            }

            function check_point()
            {
                console.log(pub_globes.answers);
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
            }

            function load_ques(qid)
            {
                qcheck(qid);
                var qid = parseInt(qid);

                if(qid && (qid <= tqs)){
                    $('#question-number').html(qid + " / " + tqs);
                    
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
            }

            // options
            $('#current_question').on('click', '.question-options button', function(){
                let qs =parseInt($(this).closest('.ques-wrapper').attr('data-ques'));

                let co = $(this).val();
                if(_.contains(pub_globes.answers[qs]['e'], co)){
                    pub_globes.answers[qs]['e'] = _.reject(pub_globes.answers[qs]['e'], function(eo){ return eo == co});
                    $(this).closest('.form-check-label').find('.option-content').removeClass('eliminate');
                } else {
                    pub_globes.answers[qs]['e'] = _.union(pub_globes.answers[qs]['e'], [co]);
                    $(this).closest('.form-check-label').find('.option-content').addClass('eliminate');
                }
                check_point();
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
            load_ques(1);

            $('#question_track button').click(function(){
                load_ques($(this).val());
            });

            $('#prev_question').click(function(){
                let prev = parseInt($(this).val());
                if(prev){
                    load_ques(prev);
                }
            });

            $('#next_question').click(function(){
                let nxt = parseInt($(this).val());
                let qid = parseInt($('#current_question').find('.ques-wrapper').attr('data-ques'));
                
                $('#current_question').find('input[name="q_option"]').prop('checked', false);
                $('#question_track :button[value="'+qid+'"]').removeClass('btn-warning btn-success').addClass('btn-outline-secondary');
                pub_globes.answers[qid]['c'] = '';
                pub_globes.answers[qid]['r'] = '';
                check_point();

                if(nxt <= tqs){
                    load_ques(nxt);
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
                    check_point();

                    $('#question_track :button[value="'+qid+'"]').removeClass('btn-outline-secondary btn-success').addClass('btn-warning');
                    if(nxt <= tqs){
                        load_ques(nxt);
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
                    check_point();

                    $('#question_track :button[value="'+qid+'"]').removeClass('btn-outline-secondary btn-warning').addClass('btn-success');
                    if(nxt <= tqs){
                        load_ques(nxt);
                    } else{
                        bootbox.alert('You are on last question');
                    }
                } else{
                    bootbox.alert('No option selected');
                }
            });

        }
    }
});
