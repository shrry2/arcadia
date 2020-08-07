@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="container">
                <a href="{{ route('intranet.list') }}">&laquo; イントラネットリスト</a>
            </div>
        </div>
        <div class="row mb-3">
            <div class="container">
                <h2>イントラネット情報の編集</h2>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card card-body">
                    <form method="POST" action="{{ route('intranet.update', $intranet->id) }}">
                        @csrf

                        <div class="form-group row">
                            <label for="office" class="col-sm-3 col-form-label text-md-right">所属</label>

                            <div class="col-sm-9">
                                @foreach($offices as $office)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="office" id="officeRadio_{{ $office->id }}" value="{{ $office->id }}"
                                               {{ $office->id == old('office', $intranet->office_id) ? 'checked="checked"' : ''}} required>
                                        <label class="form-check-label" for="officeRadio_{{ $office->id }}">{{ $office->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputNote" class="col-sm-3 col-form-label text-md-right">端末名・メモ（任意）</label>
                            <div class="col-sm-9">
                                <input type="text" name="note" class="form-control" id="inputNote" value="{{ old('note', $intranet->note) }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputIp" class="col-sm-3 col-form-label text-md-right">IPアドレス範囲</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <label for="inputIp" class="sr-only">IPアドレス</label>
                                    <input type="text" name="ip" id="inputIp" class="form-control col-10" value="{{ old('ip', $ip) }}" placeholder="xxx.xxx.xxx.xxx" required>
                                    <div class="input-group-prepend input-group-append">
                                        <div class="input-group-text">/</div>
                                    </div>
                                    <label for="inputMask" class="sr-only">サブネット</label>
                                    <input type="number" name="mask" id="inputMask" class="form-control col-2" value="{{ old('mask', $mask) }}" placeholder="xx">
                                </div>
                                <span id="ipAddressHelpText" class="form-text text-muted">
                                    接続元として判定するIPアドレスの範囲をCIDR表記（例: 203.0.113.0/24）で入力してください。サブネットマスクを省略した場合は/32（IPv4）または/128（IPv6）が補完されます。<br>
                                    現在利用中のコンピュータのIPアドレスは <code>{{ $currentIp }}</code> です。
                                </span>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group row">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary">更新</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
