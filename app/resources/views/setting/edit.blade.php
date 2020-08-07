@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="container">
                <h2>全般設定</h2>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card card-body">
                    <form method="POST" action="{{ route('settings.update') }}">
                        @csrf

                        @foreach($settings as $setting)
                            <div class="form-group">
                                <label
                                    for="input_{{ $setting->key  }}">
                                    {{ $labels[$setting->key] }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control @error($setting->key) is-invalid @enderror"
                                    id="input_{{ $setting->key  }}"
                                    name="{{ $setting->key }}"
                                    value="{{ old($setting->key, $setting->value) }}">
                                @error($setting->key)
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-primary">変更を保存</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
