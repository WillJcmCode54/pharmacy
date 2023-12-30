
@extends('adminlte::page')

@section('title', 'Devoluciones')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1>Devoluciones</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
                    <li class="breadcrumb-item" ><a href="{{route('return.index')}}">Devoluciones</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    @if($message = Session::get('success'))
        <x-adminlte-alert theme="success" title="Exito" dismissable>
            {{$message}}
        </x-adminlte-alert>
    @endif
    @if($message = Session::get('error'))
        <x-adminlte-alert theme="danger" title="Error" dismissable> 
            {{$message}}
        </x-adminlte-alert>
    @endif

    @php
        use Carbon\Carbon;
    @endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Editar Devoluciones</h3>
    </div>
    <form action="{{ route('return.update', ['id'=> $returns->id]) }}" method="post">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p><Strong>Codigo de Referencia:</Strong> {{$returns->code}}</p>
                </div>
                <div class="col-md-4">
                    <p><Strong>Tipo de Devoluciones:</Strong> Devolver</p>
                </div>
                <div class="col-md-4">
                    @php
                        $dateLoan = Carbon::parse($returns->loan_date);
                        $dateLoan = $dateLoan->format('d-m-Y');
        
                        $dateReturn = Carbon::parse($returns->return_date);
                        $dateReturn = $dateReturn->format('d-m-Y');
                    @endphp
                    <p><strong>Fecha Prestamos</strong>{{$dateLoan}}</p>
                    <p><strong>Fecha limite</strong>{{$dateReturn}}</p>
                </div>
                <div class="col-md-12">
                    <p><strong>Fecha Devolucion</strong></p>
                    <div class="input-group mb-3">
                        <input type="date" name="date_real" class="form-control @error('date_real') is-invalid @enderror"
                            value="{{ (old('date_real')) ? old('date_real') : Carbon::today()->format("Y-m-d") }}" placeholder="{{ __('Fecha de Devolucion') }}" required="required"autofocus pattern="\d{4}">
                            <div class="input-group-append">
                                <div class="input-group-text bg-primary">
                                    <span class="fas fa-calendar {{ config('adminlte.classes_auth_icon', '') }}"></span>
                                </div>
                            </div>
                        @error('date_real')
                            <span class="invalid-feedback" role="alert" style="display: block!important;">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>    
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-md">
                            <thead>
                                <th>#</th>
                                <th>Libro</th>
                                <th>Cantidad</th>
                            </thead>
                            <tbody id="tbody">
                                @foreach ($movementsDetails as $detail)
                                    <tr id="{{$detail->book_id}}">
                                        <input type="hidden" name="book_id[]" value="{{$detail->book_id}}">
                                        <td>{{$detail->book_id}}</td>
                                        <td>{{$detail->title}} de {{$detail->author}} ({{$detail->publication_year}})</td>
                                        @php
                                            $quantity = ($detail->quantity < 0 ) ? $detail->quantity * -1 : $detail->quantity  ;
                                        @endphp
                                        <td><input type="hidden" name="quantity[{{$detail->book_id}}]" class="form-control quantity" value="{{$quantity}}">{{$quantity}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- Register button --}}
        <div class="card-footer">
            <div class="card-tools">
                <button type="submit" class="btn btn-success {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                    <span class="fas fa-save"></span> Guardar
                </button>
            </div>
        </div>
    </form>
</div>
@stop