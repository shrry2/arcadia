@extends('layouts.app')

@section('content')
    <div class="container">
        @include('layouts.title-header', ['title' => 'タスクリスト', 'createRoute' => 'task.create'])
        <div class="row">
            <div class="table-responsive">
                <table class="table table-hover" style="min-width: 900px;">
                    <thead>
                    <tr>
                        <th scope="col">状況</th>
                        <th scope="col">タイトル</th>
                        <th scope="col">担当者</th>
                        <th scope="col">作成者</th>
                        <th scope="col">作成日時</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tasks as $task)
                        <tr>
                            <th>{{ $task->status }}</th>
                            <td>{{ $task->title  }}</td>
                            <td>{{ $task->members->first()->name }}</td>
                            <td>{{ $task->user->name }}</td>
                            <td>{{ $task->created_at }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary">
                                    詳細・編集
                                </button>
                                <a href="{{ route('task.delete', $task->id) }}" class="btn btn-sm btn-danger">
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
            {{ $tasks->firstItem() }} - {{ $tasks->lastItem() }} / {{ $tasks->total() }}件中
        </div>
        <div class="row justify-content-center">
            {{ $tasks->withQueryString()->links() }}
        </div>
    </div>
@endsection
