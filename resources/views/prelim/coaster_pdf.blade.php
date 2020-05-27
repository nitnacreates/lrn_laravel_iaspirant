<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<style>
    @page { margin: 0.2in 0in 0in;}

    body { 
        font-size: 11px;
        font-family: TimesNewRoman,Times New Roman,Times,Baskerville,Georgia,serif; 
        color: #666;
    }
    p, li, ol, ul, table, tr, td, h1, h2, h3, h4, h5, h6, span, div {
        margin: 0;
        padding: 0;
    }

    ol, ul{
        padding-left: 30px;
    }

    main.container{
        margin: 10px 0;
        padding: 0;
    }

    footer {
        position: fixed;
        bottom: 0px;
        left: 0;
        right: 0;
        height: 12px;
        padding: 3px;

        /** Extra personal styles **/
        background-color: #1b350c;
    }
    .foot-bag{
        color: #dcdcdc;
    }
    .float-left{ float: left; }
    .float-right{ float: right; }

    ol, ul{list-style-position: inside;}
    
    .no-break{}

    .question-wrapper{
        border: 1px solid #999;
        padding: 8px;
        margin: 14px;
    }
    .question-section, .option-section, .question-explanation{
        padding-bottom: 5px;
    }
    .active-green{
        background-color: #dafbda;
    }
    img{
        text-align: center;
        max-width: 60%;
    }

</style>

<body>
    <footer>
        <div class="foot-bag float-left">IASPIRANT - Attempt - {{ $response->attempt }}</div>
        <div class="foot-bag float-right">Copyright &copy;
            <?php echo date("Y");?> IASPIRANT</div>
    </footer>
    <main class="container">
        <div class="question-container">
            @foreach($questions as $ques)
            <div class="question-wrapper">
                <div class="question-section no-break">
                    <p><strong>Question: </strong>{{ $ques->display_id }}</p>
                    <div class="question-part">{!! $ques->question !!}</div>
                </div>
                <div class="option-section no-break">
                    <p><strong>Options: </strong></p>
                    <ol type="A" class="question-options pl-3">
                        <li @if($ques->answer == 'a') class="active-green" @endif>{!! $ques->option_a !!}</li>
                        <li @if($ques->answer == 'b') class="active-green" @endif>{!! $ques->option_b !!}</li>
                        <li @if($ques->answer == 'c') class="active-green" @endif>{!! $ques->option_c !!}</li>
                        <li @if($ques->answer == 'd') class="active-green" @endif>{!! $ques->option_d !!}</li>
                    </ol>
                    <p><strong>Your Answer:</strong> {{ array_key_exists($ques->display_id, $user_answers) ? $user_answers[$ques->display_id] : '-' }}</p>
                    <p class="active-green"><strong>Answer: </strong>{{ $ques->answer }}</p>
                </div>
                <div class="question-explanation no-break">
                    <p><strong>Explanation: </strong></p>
                    {!! $ques->explanation !!}
                </div>
            </div>
            @endforeach
        </div>
    </main>
</body>

</html>
