@extends('adminlte::page')

@section('title', 'Mqa Adm')

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('users') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Responsive Hover Table</h3>

                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control float-right"
                                   placeholder="Search">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('name') }}</th>
                            <th>{{ __('email') }}</th>
                            <th>{{ __('phone') }}</th>
                            <th>{{ __('cpf') }}</th>
                            <th>{{ __('created_at') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->cpf }}</td>
                                <td>{{ $user->created_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0">
                        @unless($users->currentPage() === 1)
                            <li class="page-item">
                                <a class="page-link" href="?page={{ $users->currentPage() - 1 }}">«</a>
                            </li>
                        @endunless
                        @for($i = 1; $i <= $users->lastPage(); $i++)
                            <li class="page-item {{$i === $users->currentPage() ? 'active' : ''}}"><a class="page-link" href="?page={{ $i }}">{{ $i }}</a></li>
                        @endfor
                        @unless($users->currentPage() === $users->lastPage())
                            <li class="page-item">
                                <a class="page-link" href="?page={{ $users->currentPage() + 1 }}">»</a>
                            </li>
                        @endunless
                    </ul>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
@stop
