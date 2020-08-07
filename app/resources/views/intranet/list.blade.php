@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h2>イントラネットリスト</h2>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col">
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseIntranetSearch">
                    部署絞り込み @if($isFiltered) <span class="badge badge-light">適用中</span> @endif
                </button>
                @if($isFiltered)
                    <a href="{{ route('intranet.list') }}" class="btn btn-outline-secondary">絞り込み解除</a>
                @endif
            </div>
            <div class="coltext-right">
                <a href="{{ route('intranet.new') }}" class="btn btn-success">新規登録</a>
            </div>
        </div>
        <div class="row mb-2">
            <div class="collapse @if($isFiltered) show @endif container-fluid" id="collapseIntranetSearch">
                <div class="card card-body mb-2">
                    <form method="GET" action="{{ route('intranet.list') }}">
                        <div class="form-row">
                            <div class="col">
                                <div><label>所属</label></div>
                                @foreach($offices as $office)
                                    <div class="form-check form-check-inline">
                                        <input name="office[]" class="form-check-input" type="checkbox" id="officeCheckbox_{{ $office->id }}" value="{{ $office->id }}"
                                            {{ in_array($office->id, $selectedOffices, true) ? 'checked="checked"' : ''}}>
                                        <label class="form-check-label" for="officeCheckbox_{{ $office->id  }}">{{ $office->name }}</label>
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
                        <button class="btn btn-primary mt-2" type="submit">絞り込みを実行</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="table-responsive">
                <table class="table table-hover" style="min-width: 900px;">
                    <thead>
                    <tr>
                        <th scope="col">部署</th>
                        <th scope="col">端末名・メモ</th>
                        <th scope="col">IPアドレス</th>
                        <th scope="col">登録日時</th>
                        <th scope="col" style="min-width: 140px;"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($intranets as $intranet)
                        <tr>
                            <td>{{ $intranet->office->name }}</td>
                            <td>{{ $intranet->note }}</td>
                            <td>{{ $intranet->ip_address }}</td>
                            <td>{{ $intranet->created_at }}</td>
                            <td>
                                <a href="{{ route('intranet.edit', $intranet->id) }}" class="btn btn-primary">
                                    編集
                                </a>
                                <a href="{{ route('intranet.delete', $intranet->id) }}" class="btn btn-danger">
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
            {{ $intranets->firstItem() }} - {{ $intranets->lastItem() }} / {{ $intranets->total() }}件中
        </div>
        <div class="row justify-content-center">
            {{ $intranets->withQueryString()->links() }}
        </div>
    </div>
@endsection
