@extends('front.layouts.skeleton')

@section('content')
<h1 class="page-title">Prelim Prelude</h1>

<p><strong>Attempt number: </strong> {{ $future_attempt }}</p>

<h3>Exam Instructions</h3>
<ul class="pl-3">
    <li>All questions are in <strong>english</strong></li>
    <li>You will have 2 hours to complete the test</li>
    <li>There are a total of 100 MCQs with 4 options each. There is only one response per question</li>
    <li>All questions carry equal marks, with maximum marks being 200</li>
    <li>Every right answer carries 2 marks; every wrong answer carries 1/3<sup>rd</sup> penalty; unattempted questions will not be marked</li>
</ul>
<h3>App Instructions</h3>
<ul class="pl-3">
    <li>Only 1<sup>st</sup> attempt will be considered for rankings</li>
    <li>There will be a countdown timer to show remaining time. Test will auto submit when countdown completes </li>
    <li>Test can be completed sooner by clicking on 'Complete Test' on last question tab</li>
    <li>Upon marking an answer, you can either click 'Review and Continue' or 'Save and Continue' to proceed further.</li>
    <li>'Save and Continue' will turn your question tab green. Only these questions will be considered for marking</li>
    <li>'Review and Continue' will turn your question tab orange. It can be revisited later before final submission</li>
    <li>To help with elimination method, every option will have a cross (X) sign to its left to lowlight it</li>
    <li>Make sure javascript and cookies are enabled for entire duration of test</li>
    <li>Do not click on site's/phone's back/refresh buttons. To navigate only use tabs on page</li>
</ul>

<p></p>
@if($future_attempt == 1)
    <p>This is your 1<sup>st</sup> attempt. Be fair and practice self-restraint. Jai Hind and best of luck.</p>
@else
    <p>This is your attempt number {{ $future_attempt }}. Only first attempts are considered for rankings. Use these attempts for practicing and evaluation</p>
@endif
    
<form role="form" id="prelude_form" method="post" action="{{ route('prelim', ['action' => 'prelude']) }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $id }}">
    <button type="submit" name="start_test" value="1" class="btn btn-primary">Start Exam</button>
</form>


@stop
