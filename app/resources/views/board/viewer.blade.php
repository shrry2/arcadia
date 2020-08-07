@extends('layouts.app')

@section('jsData')
    <script>
        window.initialData = {
        };
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="container mb-3">
                @foreach ($board->offices as $office)
                    <span class="badge badge-primary">{{ $office->name }}</span>
                @endforeach
            </div>
        </div>
        @include('layouts.title-header', ['title' => $board->name])
        <board-viewer
            data-url="{{ route('board.task.index', $board->id) }}"
            task-store-url="{{ route('board.task.store', $board->id) }}"
        ></board-viewer>
    </div>
@endsection
