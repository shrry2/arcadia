@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="col">
                <h2>部署リスト</h2>
            </div>
            <div class="col text-right">
                <a href="{{ route('office.add') }}" class="btn btn-success">新規登録</a>
            </div>
        </div>
        <div class="row">
            <div class="table-responsive">
                <table class="table table-hover" style="min-width: 900px;">
                    <thead>
                    <tr>
                        <th scope="col">部署名</th>
                        <th scope="col" style="max-width: 600px">所属人数</th>
                        <th scope="col">登録日時</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($offices as $office)
                        <tr>
                            <th>{{ $office->name }} <a href="{{ route('office.edit', $office->id) }}" class="btn btn-sm btn-outline-secondary ml-1">変更</a></th>
                            <td>{{ $office->members->count() }}名 <a href="{{ route('user.list') }}?office[]={{ $office->id }}" class="btn btn-sm btn-outline-secondary">一覧</a></td>
                            <td>{{ $office->created_at }}</td>
                            <td>
                                <a href="{{ route('office.delete', $office->id) }}" class="btn btn-sm btn-danger">
                                    削除
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
