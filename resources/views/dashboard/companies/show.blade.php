@extends('adminlte::page')

@section('title', 'Mqa Adm')

@section('content_header')
    <h1 class="m-0">{{ __('view company') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline bg-dark">
                <div class="card-body box-profile">
                    <h3 class="profile-username text-center">{{ $company->name }}</h3>
                    <p class="text-white text-center">{{ $company->cnpj }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item bg-dark">
                            <b>{{ __('users') }}</b> <a class="float-right">{{ $company->users->count() }}</a>
                        </li>
                    </ul>
                    @if($company->isActive())
                        <a href="{{ route('dashboard.companies.inactive', $company->id) }}"
                           class="btn btn-danger btn-block"><b>{{ __('inactive company') }}</b></a>
                    @else
                        <a href="{{ route('dashboard.companies.active', $company->id) }}"
                           class="btn btn-success btn-block"><b>{{ __('active company') }}</b></a>
                    @endif
                </div>
            </div>
            <div class="card card-primary bg-dark">
                <div class="card-header">
                    <h3 class="card-title">{{ __('about') }}</h3>
                </div>
                <div class="card-body">
                    <strong><i class="fas fa-circle-notch mr-1"></i> {{ __('is active') }}?</strong>
                    <p class="text-light">{{ __($company->isActive() ? 'yes' : 'no') }}</p>
                    <hr>
                    <strong><i class="fas fa-book mr-1"></i> {{ __('zipcode') }}</strong>
                    <p class="text-light">{{ $company->zipcode }}</p>
                    <hr>
                    <strong><i class="fas fa-book mr-1"></i> {{ __('street') }}</strong>
                    <p class="text-light">{{ $company->street }}</p>
                    <hr>
                    <strong><i class="fas fa-book mr-1"></i> {{ __('neighborhood') }}</strong>
                    <p class="text-light">{{ $company->neighborhood }}</p>
                    <hr>
                    <strong><i class="fas fa-book mr-1"></i> {{ __('address_number') }}</strong>
                    <p class="text-light">{{ $company->address_number }}</p>
                    <hr>
                    <strong><i class="fas fa-book mr-1"></i> {{ __('city') }}</strong>
                    <p class="text-light">{{ $company->city }}</p>
                    <hr>
                    <strong><i class="fas fa-book mr-1"></i> {{ __('state') }}</strong>
                    <p class="text-light">{{ $company->state }}</p>
                    <hr>
                    <strong><i class="fas fa-book mr-1"></i> {{ __('country') }}</strong>
                    <p class="text-light">{{ $company->country }}</p>
                    <hr>
                    <strong><i class="fas fa-book mr-1"></i> {{ __('latitude') }}</strong>
                    <p class="text-light">{{ $company->latitude }}</p>
                    <hr>
                    <strong><i class="fas fa-book mr-1"></i> {{ __('longitude') }}</strong>
                    <p class="text-light">{{ $company->longitude }}</p>
                    <hr>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-lin active" href="#timeline" data-toggle="tab">Timeline</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="timeline">
                            <div class="timeline timeline-inverse">
                                @foreach($timeline as $event)
                                    <div class="time-label">
                                        <span class="bg-danger">
                                          {{ $event->formatted->date }}
                                        </span>
                                    </div>
                                    <div>
                                        <i class="fas fa-info bg-primary"></i>

                                        <div class="timeline-item">
                                            <span class="time"><i class="far fa-clock"></i> {{ $event->formatted->time }}</span>
                                            <h3 class="timeline-header">{{ $event->title }}</h3>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
