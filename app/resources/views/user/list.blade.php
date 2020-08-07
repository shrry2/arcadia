@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-8">
                <h2>スタッフリスト</h2>
            </div>
            @can('register users')
                <div class="col-4 text-right">
                    <div class="btn-group" role="group" aria-label="新規スタッフ登録">
                        <a href="{{ route('user.new') }}" class="btn btn-success">新規登録</a>
                    </div>
                </div>
            @endcan
        </div>
        <div class="row mb-2">
            <div class="container">
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseUserSearch">
                    スタッフの絞り込み @if($isFiltered) <span class="badge badge-light">適用中</span> @endif
                </button>
                @if($isFiltered)
                    <a href="{{ route('user.list') }}" class="btn btn-outline-secondary">絞り込み解除</a>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="collapse @if($isFiltered) show @endif container-fluid" id="collapseUserSearch">
                <div class="card card-body mb-2">
                    <form>
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="inputName">氏名（部分一致）</label>
                                <input type="text" name="name" class="form-control" id="inputName" value="{{ request()->input('name') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="inputEmail">メールアドレス（完全一致）</label>
                                <input type="email" name="email" class="form-control" id="inputEmail" value="{{ request()->input('email') }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <div><label>所属</label></div>
                                @foreach($offices as $office)
                                    <div class="form-check form-check-inline">
                                        <input name="office[]" class="form-check-input" type="checkbox" id="officeCheckbox_{{ $office->id }}" value="{{ $office->id }}"
                                            {{ in_array($office->id, $selectedOffices, true) ? 'checked="checked"' : ''}}>
                                        <label class="form-check-label" for="officeCheckbox_{{ $office->id  }}">{{ $office->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-md-6 mb-3">
                                <div><label>権限グループ</label></div>
                                @foreach($roles as $role)
                                    <div class="form-check form-check-inline">
                                        <input name="role[]" class="form-check-input" type="checkbox" id="roleCheckbox_{{ $role->id }}" value="{{ $role->id }}"
                                            {{ in_array($role->id, $selectedRoles, true) ? 'checked="checked"' : ''}}>
                                        <label class="form-check-label" for="roleCheckbox_{{ $role->id  }}">{{ $role->name }}</label>
                                    </div>
                                @endforeach
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
                        <button class="btn btn-primary" type="submit">絞り込みを実行</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="table-responsive">
                <table class="table table-hover" style="min-width: 900px;">
                    <thead>
                    <tr>
                        <th scope="col" style="min-width: 100px;">氏名</th>
                        <th scope="col">社員コード</th>
                        <th scope="col">メールアドレス</th>
                        <th scope="col">所属</th>
                        <th scope="col">権限グループ</th>
                        <th scope="col" style="min-width: 140px;"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <th scope="row">{{ $user->name }}</th>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <ul class="list-inline">
                                    @foreach($user->offices as $office)
                                        <li class="list-inline-item">{{ $office->name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul class="list-inline">
                                    @foreach($user->roles as $role)
                                        <li class="list-inline-item">{{ $role->name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary">
                                    編集
                                </a>
                                <a href="{{ route('user.delete', $user->id) }}" class="btn btn-danger">
                                    削除
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row justify-content-center">
            {{ $users->firstItem() }} - {{ $users->lastItem() }} / {{ $users->total() }}件中
        </div>
        <div class="row justify-content-center">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
@endsection
