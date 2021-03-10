@extends('adminlte::page')

@section('title', 'Mqa Adm')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-4">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('total users') }}</span>
                    <span class="info-box-number">{{ $counters->users->total }}</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('active users') }}</span>
                    <span class="info-box-number">{{ $counters->users->active }}</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('inactive users') }}</span>
                    <span class="info-box-number">{{ $counters->users->inactive }}</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('total companies') }}</span>
                    <span class="info-box-number">{{ $counters->companies->total }}</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('active companies') }}</span>
                    <span class="info-box-number">{{ $counters->companies->active }}</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('inactive companies') }}</span>
                    <span class="info-box-number">{{ $counters->companies->inactive }}</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('total units') }}</span>
                    <span class="info-box-number">{{ $counters->units->total }}</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('active units') }}</span>
                    <span class="info-box-number">{{ $counters->units->active }}</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('inactive units') }}</span>
                    <span class="info-box-number">{{ $counters->units->inactive }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('total foods') }}</span>
                    <span class="info-box-number">{{ $counters->foods->total }}</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('active foods') }}</span>
                    <span class="info-box-number">{{ $counters->foods->active }}</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('inactive foods') }}</span>
                    <span class="info-box-number">{{ $counters->foods->inactive }}</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('total interests') }}</span>
                    <span class="info-box-number">{{ $counters->foods_interests->total }}</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('active interests') }}</span>
                    <span class="info-box-number">{{ $counters->foods_interests->active }}</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('inactive interests') }}</span>
                    <span class="info-box-number">{{ $counters->foods_interests->inactive }}</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('total foods available') }}</span>
                    <span class="info-box-number">{{ $counters->foods_available->total }}</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('active foods available') }}</span>
                    <span class="info-box-number">{{ $counters->foods_available->active }}</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('inactive foods available') }}</span>
                    <span class="info-box-number">{{ $counters->foods_available->inactive }}</span>
                </div>
            </div>
        </div>
    </div>
@stop
