@extends('adminlte::page')

@section('title', 'Mqa Adm')

@section('content_header')
    <h1 class="m-0">{{ __('companies') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card bg-dark">
                <div class="card-header">
                    <h3 class="card-title">{{ __('total companies') }}</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-dark text-nowrap table-with-sort">
                        <thead>
                        <tr>
                            <th></th>
                            <th {!! $paginator('id')->full !!}>id</th>
                            <th {!! $paginator('name')->full !!}>{{ __('name') }}</th>
                            <th {!! $paginator('cnpj')->full !!}>{{ __('cnpj') }}</th>
                            <th {!! $paginator('users_count')->full !!}>{{ __('users') }}</th>
                            <th {!! $paginator('zipcode')->full !!}>{{ __('zipcode') }}</th>
                            <th {!! $paginator('street')->full !!}>{{ __('street') }}</th>
                            <th {!! $paginator('address_number')->full !!}>{{ __('address_number') }}</th>
                            <th {!! $paginator('neighborhood')->full !!}>{{ __('neighborhood') }}</th>
                            <th {!! $paginator('city')->full !!}>{{ __('city') }}</th>
                            <th {!! $paginator('state')->full !!}>{{ __('state') }}</th>
                            <th {!! $paginator('country')->full !!}>{{ __('country') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($companies as $company)
                            <tr>
                                <td>
                                    <a href="{{ route('dashboard.companies.show', $company->id) }}"
                                       class="btn btn-pink">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                                <td>{{ $company->id }}</td>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->cnpj }}</td>
                                <td>{{ $company->users_count }}</td>
                                <td>{{ $company->zipcode }}</td>
                                <td>{{ $company->street }}</td>
                                <td>{{ $company->address_number }}</td>
                                <td>{{ $company->neighborhood }}</td>
                                <td>{{ $company->city }}</td>
                                <td>{{ $company->state }}</td>
                                <td>{{ $company->country }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $companies->links() }}
                </div>
            </div>
        </div>
    </div>
@stop
