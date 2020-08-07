@extends('layouts.app')

@section('jsData')
    <script>
        window.initialData = {
        };
    </script>
@endsection

@section('content')
    <div class="container">
        @include('layouts.title-header', ['title' => $intranetData->office->name])
        <div class="row">
            <div class="col-sm-6 mt-3">
                <div class="row">
                    <div class="container">

                    </div>
                </div>
                <working-member-list
                    api-list-url="{{ route('attendance.api.listMembers') }}"
                    api-finish-url="{{ route('attendance.api.finish') }}"
                ></working-member-list>
            </div>
            <div class="col-sm-6 mt-3">
                <div class="row">
                    <div class="container">
                        <p class="float-right m-0 py-1">
                            <strong>{{ $boards->count() }}件</strong>
                        </p>
                        <h3>共有ボード</h3>
                    </div>
                </div>
                <div class="list-group">
                    @foreach ($boards as $board)
                    <a href="{{ route('board.show', $board->id) }}" class="list-group-item list-group-item-action">
                        {{ $board->name }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
