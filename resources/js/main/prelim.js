$(document).ready(function() {

/*** Global ***/
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

/*** Pages ***/
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
            let thread = $(this).closest('.parent-comment').attr('data-thread');

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


    }
});
