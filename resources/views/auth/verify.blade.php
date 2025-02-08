@extends('auth-master')

@section('pageTitle', 'Verify Account')

@section('content')
<div class="card">
    <div class="card-header">{{ __('Verify Your Email Address') }}</div>

    <div class="card-body">
        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                {{ __('A fresh verification link has been sent to your email address.') }}
            </div>
        @endif

        {{ __('Before proceeding, please check your email for a verification link.') }}
        {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
    </div>
</div>

<div class="row">
    <div class="col-sm-12 form-group">
        <a href="{{ route('logout') }}" class="btn btn-default btn-block">Logout</a>
    </div>
</div>
@endsection
