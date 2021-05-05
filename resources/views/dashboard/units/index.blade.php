@extends('adminlte::page')

@section('title', 'Mqa Adm')

@section('content_header')
    <h1 class="m-0">{{ __('units') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card bg-dark">
                <div class="card-header">
                    <h3 class="card-title">{{ __('total units') }}</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-dark text-nowrap table-with-sort">
                        <thead>
                        <tr>
                            {{-- <th></th>--}}
                            <th {!! $paginator('id')->full !!}>id</th>
                            <th {!! $paginator('unit')->full !!}>{{ __('unit') }}</th>
                            <th {!! $paginator('foods_count')->full !!}>{{ __('total foods') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($units as $unit)
                            <tr>
                                {{-- <td>--}}
                                {{-- <a href="{{ route('dashboard.unit.show', $unit->id) }}"--}}
                                {{--ss="btn btn-pink">--}}
                                {{-- <i class="fas fa-eye"></i>--}}
                                {{--</a>--}}
                                {{--</td>--}}
                                <td>{{ $unit->id }}</td>
                                <td>{{ $unit->unit }}</td>
                                <td>{{ $unit->foods_count }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $units->links() }}
                </div>
            </div>
        </div>
    </div>
@stop
