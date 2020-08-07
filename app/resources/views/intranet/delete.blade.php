@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="container">
                <a href="{{ route('intranet.list') }}">&laquo; 部署リスト</a>
            </div>
        </div>
        <div class="row">
            <div class="container">
                <h2>イントラネットの削除</h2>
            </div>
        </div>
        <div class="row">
            <div class="container">
                <div class="card mb-3">
                    <div class="card-header">
                        削除対象
                    </div>
                    <div class="card-body">
                        <dl class="m-0">
                            <dt>部署</dt>
                            <dd>{{ $intranet->office->name }}</dd>
                            <dt>端末名・メモ</dt>
                            <dd>{{ $intranet->note }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="alert alert-danger">
                    <strong>イントラネットの削除に関する注意事項</strong>
                    <ul class="mb-0">
                        <li>一度削除したイントラネット情報はもとに戻せません</li>
                        <li>削除されたイントラネットからのアクセスは、削除以降社外からのアクセスとして取り扱われます</li>
                    </ul>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="text-right">
                            <form action="{{ route('intranet.destroy', $intranet->id) }}" method="post">
                                @method('delete')
                                @csrf
                                注意事項を確認したうえで削除を実行する場合は、こちらのボタンを押してください → <button type="submit" class="btn btn-danger">イントラネットを削除</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
