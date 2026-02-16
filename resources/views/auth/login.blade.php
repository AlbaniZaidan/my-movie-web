@extends('app')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h2 class="auth-title">Welcome Back!</h2>
        <p class="auth-subtitle">Log in to manage your favorites</p>
        
        @if (Session::has('info'))
            <div class="alert alert-warning">
                <i class="glyphicon glyphicon-info-sign"></i> {{ Session::get('info') }}
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
                <input type="text" name="email" class="auth-input" placeholder="Email Address" required>
            </div>

            <div class="form-group">
                <input type="password" name="password" class="auth-input" placeholder="Password" required>
            </div>

            <button type="submit" class="auth-btn">Login</button>       
        </form>

        <div class="auth-footer">
            <p>New to MyMovie? <a href="{{ url('/register') }}" class="auth-link">Create an account</a></p>
        </div>
    </div>
</div>
@endsection