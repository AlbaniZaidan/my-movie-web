@extends('app')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h2 class="auth-title">Create Account</h2>
        <p class="auth-subtitle">Join us to save your favorite movies</p>

        @if (count($errors) > 0)
            <div class="alert alert-warning">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach ($errors->all() as $error)
                        <li><i class="glyphicon glyphicon-exclamation-sign"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('/register') }}" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            
            <div class="form-group">
                <input type="text" name="name" class="auth-input" placeholder="Username" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <input type="email" name="email" class="auth-input" placeholder="Email Address" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <input type="password" name="password" class="auth-input" placeholder="Password" required>
            </div>

            <button type="submit" class="auth-btn">Register</button>       
        </form>

        <div class="auth-footer">
            <p>Already have an account? <a href="{{ url('/login') }}" class="auth-link">Log in here</a></p>
        </div>
    </div>
</div>
@endsection