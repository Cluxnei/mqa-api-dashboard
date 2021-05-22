@extends('adminlte::page')

@section('title', 'Mqa Adm')

@section('content_header')
    <h1 class="m-0">{{ __('add new food') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card bg-dark">
                <form role="form" action="{{ route('dashboard.foods.store') }}" method="POST">
                    @csrf
                    <div class="card-header">
                        <h3 class="card-title">{{ __('add new food') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            @error('name')
                            <div class="alert alert-danger m-1">{{ $errors->first('name') }}</div>
                            @enderror
                            <label for="name">{{ __('name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                   name="name"
                                   placeholder="{{ __('name') }}" value="{{ old('name') }}">
                        </div>
                        <div class="form-group">
                            @error('units')
                            <div class="alert alert-danger m-1">{{ $errors->first('units') }}</div>
                            @enderror
                            <label for="units">{{ __('units') }}</label>
                            <select class="select2 form-control" id="units" name="units[]" multiple="multiple">
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id}}" {{ old('units') && in_array($unit->id, old('units'), false) ? 'selected' : '' }}>{{ $unit->unit }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <button type="submit" class="btn btn-primary">{{ __('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
