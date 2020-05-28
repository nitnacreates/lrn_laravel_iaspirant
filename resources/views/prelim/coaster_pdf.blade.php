<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<style>
    @page { margin: 0; padding: 0;}

    body { 
        font-size: 11px;
        font-family: TimesNewRoman,Times New Roman,Times,Baskerville,Georgia,serif; 
        color: #666;
        margin-top: 1.2cm;
        margin-left: 1cm;
        margin-right: 1cm;
        margin-bottom: 0cm;
        padding: 0;
    }
    header {
        position: fixed;
        top: 0cm;
        left: 0cm;
        right: 0cm;
        height: 0.5cm;

        /** Extra personal styles **/
        background-color: #75a843;
        color: white;
        text-align: center;
        padding: 0.2cm;
    }
    footer {
        position: fixed; 
        bottom: 0cm; 
        left: 0cm; 
        right: 0cm;
        height: 0.5cm;

        /** Extra personal styles **/
        background-color: #75a843;
        color: white;
        text-align: center;
        padding: 0.2cm;
    }
    .foot-bag { color: #dcdcdc; }
    .float-left { float: left; }
    .float-right { float: right; }

    p, strong, b, li, ol, ul, table, tr, td, h1, h2, h3, h4, h5, h6, div, span {
        margin: 0;
        padding: 0;
    }

    ol, ul {
        padding-left: 0.5cm;
        list-style-position: inside;
    }
    
    .question-wrapper{
        border: 0.04cm solid #999;
        margin: 0.4cm;
        padding: 0.2cm;
    }

    .question-section, .option-section, .question-explanation{
        padding-bottom: 0.2cm;
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
    <header>
        IASPIRANT - {{ auth()->user()->name }} - {{ auth()->user()->email }}
    </header>
    <footer>
        <div class="foot-bag float-left">Attempt - {{ $response->attempt }}</div>
        <div class="foot-bag float-right">Copyright &copy; <?php echo date("Y");?> IASPIRANT</div>
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
