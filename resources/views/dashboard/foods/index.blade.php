@extends('adminlte::page')

@section('title', 'Mqa Adm')

@section('content_header')
    <h1 class="m-0">{{ __('foods') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card bg-dark">
                <div class="card-header">
                    <h3 class="card-title">{{ __('total foods') }}</h3>
                    <a href="{{ route('dashboard.foods.create') }}" class="float-right btn btn-success">
                        <i class="fas fa-plus pr-1"></i>
                        {{ __('add food') }}
                    </a>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-dark text-nowrap table-with-sort">
                        <thead>
                        <tr>
                            {{-- <th></th>--}}
                            <th {!! $paginator('id')->full !!}>id</th>
                            <th {!! $paginator('name')->full !!}>{{ __('name') }}</th>
                            <th {!! $paginator('approved')->full !!}>{{ __('approved?') }}</th>
                            <th {!! $paginator('approved_by')->full !!}>{{ __('approved by') }}</th>
                            <th {!! $paginator('requested_by')->full !!}>{{ __('requested by') }}</th>
                            <th {!! $paginator('units_count')->full !!}>{{ __('total units') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($foods as $food)
                            <tr>
                                {{-- <td>--}}
                                {{-- <a href="{{ route('dashboard.unit.show', $unit->id) }}"--}}
                                {{--ss="btn btn-pink">--}}
                                {{-- <i class="fas fa-eye"></i>--}}
                                {{--</a>--}}
                                {{--</td>--}}
                                <td>{{ $food->id }}</td>
                                <td>{{ $food->name }}</td>
                                <td>{{ __(($food->approved ? 'yes' : 'no')) }}</td>
                                <td>{{ $food->approvedByAdmin->name ?? '-' }}</td>
                                <td>{{ $food->requestedByUser->name ?? '-' }}</td>
                                <td>{{ $food->units_count }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $foods->links() }}
                </div>
            </div>
        </div>
    </div>
@stop
