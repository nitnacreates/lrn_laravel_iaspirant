@extends('front.layouts.skeleton')

@section('content')
<h1 class="page-title">Contact Us</h1>
<div class="row">
    <div class="col">
        <p>For any query, feedback, bug report, feature request, please send a query below, or drop an email on <a href="mailto:support@iaspirant.com">support@iaspirant.com</a>, or call us on 0120 4128557 (11am to 5pm).</p>
        <p><strong>Address:</strong><br/>
            IASPIRANT, <br/>
            252, SECTOR 1,<br/>
            Near Amity International School, <br/>
            GHAZIABAD, UTTAR PRADESH<br/>
            PIN: 201010
        </p>
    </div>
</div>
<div class="row mt-3 pt-3 border-top">
    <div class="col">
        <p>We usually revert back in 1-3 business days.</p>
        <form role="form" id="stash_form" method="post" action="{{ route('front::manifest', ['action' => 'contact']) }}">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="first_name">First Name<sup class="text-danger">*</sup></label>
                    <input type="text" class="form-control" id="first_name" placeholder="First Name" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" pattern="[a-zA-z]{2,20}" autofocus>
                    <small class="form-text text-muted">No spaces</small>
                    @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="last_name">Last Name<sup class="text-danger">*</sup></label>
                    <input type="text" class="form-control" id="last_name" placeholder="Last Name" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" pattern="[a-zA-z]{2,20}" autofocus>
                    @error('last_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="email">E-Mail Address<sup class="text-danger">*</sup></label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="E Mail">
                <small class="form-text text-muted">Only gmail, yahoo, rediff, zoho, outlook allowed</small>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="mobile">Phone<sup class="text-danger">*</sup></label>
                <input id="mobile" type="phone" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required autocomplete="mobile" pattern="[0-9]{10}" autofocus placeholder="Mobile">
                <small id="mobile" class="form-text text-muted">Your 10 digit phone number</small>
                @error('mobile')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="message">Message<sup class="text-danger">*</sup></label>
                <textarea  name="message" id="message" class="form-control @error('message') is-invalid @enderror" required placeholder="Your query">{{ old('message') }}</textarea>
                @error('message')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                {!! NoCaptcha::display() !!}
                @error('g-recaptcha-response')
                    <span class="invalid-feedback" role="alert">
                       <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                    </span>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <div class="col-2"></div>
</div>
@endsection

@push('scripts_foot')
{!! NoCaptcha::renderJs() !!}
@endpush
