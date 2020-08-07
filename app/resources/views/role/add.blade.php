@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="container">
                <a href="{{ route('role.list') }}">&laquo; 権限グループリスト</a>
            </div>
        </div>
        <div class="row">
            <div class="container">
                <h2>新規権限グループの作成</h2>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card card-body">
                    <form method="POST" action="{{ route('role.create') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="inputRoleName" class="col-sm-3 col-form-label text-sm-right">権限グループ名</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control" id="inputRoleName" value="{{ old('name') }}">
                            </div>
                        </div>
                        <fieldset class="form-group">
                            <div class="row">
                                <legend class="col-form-label col-sm-3 pt-0 text-sm-right">許可する操作</legend>
                                <div class="col-sm-9">
                                    @foreach ($permissions as $permission)
                                    <div class="form-check form-check-inline">
                                        <input name="permission[]" class="form-check-input" type="checkbox" id="checkboxForPermission_{{ $permission->id }}" value="{{ $permission->id }}"
                                            {{ in_array((string) $permission->id, old('permission', []), true) ? 'checked="checked"' : ''}}>
                                        <label class="form-check-label" for="checkboxForPermission_{{ $permission->id }}">{{ $permission->name }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </fieldset>
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
                            <div class="col-sm-9 offset-3">
                                <button type="submit" class="btn btn-primary">新規登録</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
