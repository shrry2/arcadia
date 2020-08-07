@extends('layouts.app')

@section('content')
    <div class="container">
        @include('layouts.title-header', ['backLink' => true, 'title' => 'ボードの新規作成'])
        <div class="row">
            <div class="col">
                <div class="card card-body">
                    <form method="POST" action="{{ route('board.store') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="inputBoardName" class="col-sm-2 col-form-label text-sm-right">ボードの名前</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control" id="inputBoardName" value="{{ old('name') }}">
                            </div>
                        </div>
                        <fieldset class="form-group">
                            <div class="row">
                                <legend class="col-form-label col-sm-2 pt-0 text-sm-right">所属部署</legend>
                                <div class="col-sm-10">
                                    @foreach ($offices as $office)
                                        <div class="form-check form-check-inline">
                                            <input name="office[]" class="form-check-input" type="checkbox" id="checkboxForOffice_{{ $office->id }}" value="{{ $office->id }}"
                                                {{ in_array((string) $office->id, old('office', []), true) ? 'checked="checked"' : ''}}>
                                            <label class="form-check-label" for="checkboxForOffice_{{ $office->id }}">{{ $office->name }}</label>
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
                            <div class="col-sm-10 offset-2">
                                <button type="submit" class="btn btn-primary">新規作成</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
