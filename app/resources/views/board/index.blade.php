@extends('layouts.app')

@section('content')
    <div class="container">
        @include('layouts.title-header', ['title' => 'ボードリスト', 'createRoute' => 'board.create'])
        <div class="row">
            <div class="table-responsive">
                <table class="table table-hover" style="min-width: 900px;" id="boardListTable">
                    <thead>
                    <tr>
                        <th scope="col">ボード名</th>
                        <th scope="col" style="max-width: 600px">所属部署</th>
                        <th scope="col">タスク数</th>
                        <th scope="col">作成日時</th>
                        <th style="min-width: 120px"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($boards as $board)
                        <tr>
                            <th>{{ $board->name }}</th>
                            <td>
                                <ul class="list-inline">
                                    @foreach($board->offices as $office)
                                        <li class="list-inline-item">{{ $office->name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $board->tasks->count() }}件</td>
                            <td>{{ $board->created_at }}</td>
                            <td>
                                <a href="{{ route('board.show', $board->id) }}" class="btn btn-sm btn-primary">
                                    表示
                                </a>
                                <a href="{{ route('board.edit', $board->id) }}" class="btn btn-sm btn-success">
                                    編集
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row justify-content-center">
            {{ $boards->firstItem() }} - {{ $boards->lastItem() }} / {{ $boards->total() }}件中
        </div>
        <div class="row justify-content-center">
            {{ $boards->withQueryString()->links() }}
        </div>
    </div>
@endsection
