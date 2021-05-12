@extends('adminlte::page')

@section('title', 'Mqa Adm')

@section('content_header')
    <h1 class="m-0">{{ __('view user') }}</h1>
@stop

@section('content')
    <div class="row">
        @unless($user->isActive())
            <div class="col-12">
                <div class="alert alert-dismissible bg-red">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> {{ __('alert') }}!</h5>
                    {{ __('this users is inactive') }}
                </div>
            </div>
        @endunless
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline bg-dark">
                <div class="card-body box-profile">
                    <h3 class="profile-username text-center">{{ $user->name }}</h3>
                    <p class="text-white text-center">{{ $user->email }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item bg-dark">
                            <b>{{ __('companies') }}</b> <a class="float-right">{{ $user->companies->count() }}</a>
                        </li>
                    </ul>
                    @if($user->isActive())
                        <a href="{{ route('dashboard.users.inactive', $user->id) }}"
                           class="btn btn-danger btn-block"><b>{{ __('inactive user') }}</b></a>
                    @else
                        <a href="{{ route('dashboard.users.active', $user->id) }}"
                           class="btn btn-success btn-block"><b>{{ __('active user') }}</b></a>
                    @endif
                </div>
            </div>
            <div class="card card-primary bg-dark">
                <div class="card-header">
                    <h3 class="card-title">{{ __('about') }}</h3>
                </div>
                <div class="card-body">
                    <strong><i class="fas fa-stop-circle mr-1"></i> {{ __('is admin?') }}</strong>
                    <p class="text-light">{{ __($user->isAdmin() ? 'yes' : 'no') }}</p>
                    <hr>
                    <strong><i class="fas fa-circle-notch mr-1"></i> {{ __('is active?') }}</strong>
                    <p class="text-light">{{ __($user->isActive() ? 'yes' : 'no') }}</p>
                    <hr>
                    <strong><i class="fas fa-book mr-1"></i> {{ __('cpf') }}</strong>
                    <p class="text-light">{{ $user->cpf }}</p>
                    <hr>
                    <strong><i class="fas fa-phone mr-1"></i> {{ __('phone') }}</strong>
                    <p class="text-light">{{ $user->phone }}</p>
                    <hr>
                    <strong><i class="fas fa-envelope mr-1"></i> {{ __('email') }}</strong>
                    <p class="text-light">{{ $user->email }}</p>
                    <hr>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-lin active" href="#timeline"
                                                data-toggle="tab">{{ __('timeline') }}</a>
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
