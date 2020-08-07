@extends('layouts.app')

@section('jsData')
    <script>
        window.initialData = {
        };
    </script>
@endsection

@section('content')
    <div class="container">
        @include('layouts.title-header', ['title' => 'ようこそ '.Auth::user()->name.'さん'])
        <div class="row">
            <div class="col-sm-6 mt-3">
                <div class="row">
                    <div class="container">
                        <h3>共有ボード</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="container">
                        <h3>各部署の状況</h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 mt-3">
                <div class="row">
                    <div class="container">
                        <h3>割り当てられたタスク</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
