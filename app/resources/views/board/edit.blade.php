@extends('layouts.app')

@section('content')
    <div class="container">
        @include('layouts.title-header', ['backLink' => true, 'backRoute' => 'board.index', 'title' => 'ボードの編集'])
        <div class="row">
            <div class="col">
                <div class="card card-body">
                    <form method="POST" action="{{ route('board.update', $board->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <label for="inputBoardName" class="col-sm-2 col-form-label text-sm-right">ボードの名前</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control" id="inputBoardName" value="{{ old('name', $board->name) }}">
                            </div>
                        </div>
                        <fieldset class="form-group">
                            <div class="row">
                                <legend class="col-form-label col-sm-2 pt-0 text-sm-right">所属部署</legend>
                                <div class="col-sm-10">
                                    @foreach ($offices as $office)
                                        <div class="form-check form-check-inline">
                                            <input name="office[]" class="form-check-input" type="checkbox" id="checkboxForOffice_{{ $office->id }}" value="{{ $office->id }}"
                                                {{ in_array($office->id, old('office', $board->offices->pluck('id')->toArray()), true) ? 'checked="checked"' : ''}}>
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
                                <button type="submit" class="btn btn-primary">変更を保存</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card mt-3">
                    <div class="card-header">ボードを削除</div>
                    <div class="card-body">
                        <div class="alert alert-danger">
                            <strong>ボードの削除に関する注意事項</strong>
                            <ul class="mb-0">
                                <li>一度削除したボード情報はもとに戻せません</li>
                                <li>このボードに所属する{{ $board->tasks->count() }}件のタスク情報も同時に削除されます</li>
                            </ul>
                        </div>
                        <div class="text-right">
                            上記注意事項を確認のうえ、削除を実行する場合はこのボタンを押してください →
                            <delete-button form-element-id="deleteForm_{{ $board->id }}" delete-button-text="ボードを削除"></delete-button>
                        </div>
                        <form id="deleteForm_{{ $board->id }}" action="{{ route('board.destroy', $board->id) }}" method="POST" style="display: none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
