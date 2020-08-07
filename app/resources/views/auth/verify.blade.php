@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">メールアドレスの検証</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            新しい検証リンクをあなたのメールアドレスに送信しました。
                        </div>
                    @endif

                    続行する前に、メールでお送りした検証リンクを確認してください。
                    メールを受け取っていない場合
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">検証リンクを再送信</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
