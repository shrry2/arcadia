@extends('layouts.app')

@section('content')
    <div class="container">
        @include('layouts.title-header', ['backLink' => true, 'title' => 'タスクの新規作成'])
        <div class="row">
            <div class="col">
                <div class="card card-body">
                    <form method="POST" action="{{ route('task.store') }}">
                        @csrf

                        <task-editor>
                            読み込んでいます...
                        </task-editor>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">新規タスクを作成</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
