@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="container">
                <a href="{{ route('office.list') }}">&laquo; 部署リスト</a>
            </div>
        </div>
        <div class="row mb-3">
            <div class="container">
                <h2>部署情報の編集</h2>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card card-body">
                    <form method="POST" action="{{ route('office.update', $office->id) }}">
                        @csrf
                        <div class="form-group row">
                            <label for="inputOfficeName" class="col-sm-2 col-form-label text-sm-right">部署名</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control" id="inputOfficeName" value="{{ old('name', $office->name) }}" required autofocus>
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group row">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary">更新</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
