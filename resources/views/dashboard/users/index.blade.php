@extends('adminlte::page')

@section('title', 'Mqa Adm')

@section('content_header')
    <h1 class="m-0">{{ __('users') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card bg-dark">
                <div class="card-header">
                    <h3 class="card-title">{{ __('total users') }}</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-dark text-nowrap table-with-sort">
                        <thead>
                        <tr>
                            <th></th>
                            <th {!! $paginator('id')->full !!}>id</th>
                            <th {!! $paginator('name')->full !!}>{{ __('name') }}</th>
                            <th {!! $paginator('email')->full !!}>{{ __('email') }}</th>
                            <th {!! $paginator('phone')->full !!}>{{ __('phone') }}</th>
                            <th {!! $paginator('cpf')->full !!}>{{ __('cpf') }}</th>
                            <th {!! $paginator('companies_count')->full !!}>{{ __('companies') }}</th>
                            <th {!! $paginator('is_admin')->full !!}>{{ __('is admin') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <a href="{{ route('dashboard.users.show', $user->id) }}" class="btn btn-pink">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->cpf }}</td>
                                <td>{{ $user->companies_count }}</td>
                                <td>{{ __($user->is_admin ? 'yes' : 'no') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
@stop
