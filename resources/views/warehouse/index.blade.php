
@extends('adminlte::page')

@section('title', 'Almacen')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Almacen</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Almacen</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
@php
    use Carbon\Carbon;
@endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Listado de Medicinas</h3>
    </div>
    <div class="card-body">
        {{-- Setup data for datatables --}}
        @php
            $heads = [
                'ID',
                'Nombre',
                'Caduca',
                'Estantería',
                'Categoria',
                'Monto',
                'Cantidad',
                'Descripcion',
            ];
        @endphp

        {{-- Minimal example / fill data using the component slot --}}
        <x-adminlte-datatable id="table1" :heads="$heads" theme="light" striped hoverable >
            @foreach($medicines as $medicine)
                <tr>
                    <td>{{$medicine->id}}</td>
                    <td>            
                        <img src="{{asset($medicine->img)}}" alt="{{$medicine->name}}" class="img-circle img-size-50 mr-2">
                        {{$medicine->name}}
                    </td>
                    <td>{{Carbon::parse($medicine->expiration_date)->format('d-m-Y')}}</td>
                    <td>{{$medicine->shelf}}</td>
                    <td>{{$medicine->category}}</td>
                    <td>{{$medicine->amount}}</td>
                    <td>{{$medicine->quantity}}</td>
                    <td>{{$medicine->decription}}</td>
                </tr>
            @endforeach
        </x-adminlte-datatable>

    </div>
</div>
@stop

