@if (isset($backLink))
<div class="row mb-4">
    <div class="container">
        @if (isset($backRoute))
            <a href="{{ route($backRoute) }}">&laquo; もどる</a>
        @else
            <a href="{{ url()->previous() }}">&laquo; もどる</a>
        @endif
    </div>
</div>
@endif
<div class="row mb-3">
    @if (isset($createRoute))
        <div class="col">
            <h2>{{ $title }}</h2>
        </div>
        <div class="col text-right">
            <a href="{{ route($createRoute) }}" class="btn btn-success">新規登録</a>
        </div>
    @else
        <div class="container">
            <h2>{{ $title }}</h2>
        </div>
    @endif
</div>
