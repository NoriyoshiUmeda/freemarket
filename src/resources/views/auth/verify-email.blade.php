@extends('layouts.app')

@section('title', 'メール認証誘導画面')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
    <div class="verify-container">
        <p class="verify-message">登録していただいたメールアドレスに認証メールを送付しました。<br>
           メール認証を完了してください。
        </p>

        <a href="https://mailtrap.io/" target="_blank" class="verify-btn">
            認証はこちらから
        </a>

        <form method="POST" action="{{ route('verification.send') }}" class="mt-3">
            @csrf
            <button type="submit" class="resend-link">認証メールを再送する</button>
        </form>
    </div>
@endsection
