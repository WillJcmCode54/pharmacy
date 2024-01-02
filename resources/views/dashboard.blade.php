
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop
@php
    use Carbon\Carbon;
@endphp
@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{$medicines}}</h3>
                <p>Medicinas Disponibles</p>
            </div>
            <div class="icon">
                <i class="fas fa-hand-holding-medical"></i>
            </div>
            <a href="{{route('medicine.index')}}" class="small-box-footer">Mas<i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
        
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{$customers}}</h3>
                <p>Clientes Registrados</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{route('customer.index')}}" class="small-box-footer">Mas<i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
        
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{$load}}</h3>
                <p>Medicinas Cargada</p>
            </div>
            <div class="icon">
                <i class="fas fa-truck-loading"></i>
            </div>
            <a href="{{route('movement.index')}}" class="small-box-footer">Mas<i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
        
    <div class="col-lg-3 col-6">
    
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{$download}}</h3>
                <p>Medicinas Descargada</p>
            </div>
            <div class="icon">
                <i class="fas fa-dolly"></i>
            </div>
            <a href="{{route('movement.index')}}" class="small-box-footer">Mas<i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
<div class="row">
       
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    Top 5 Medicina cargadas en el periodo {{Carbon::now()->startOfMonth()->format('d/m/Y')}} - {{ Carbon::now()->endOfMonth()->format('d/m/Y')}}
                </h4>
            </div>
            <div class="card-body">
                <table class="table table-striped table-valign-middle text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Medicina</th>
                            <th>Categoria</th>
                            <th>Cantidad Cargada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topMediciesLoad as $key => $item)   
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$item->medicine}}</td>
                            <td>{{$item->category}}</td>
                            <td>{{$item->subtotal}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    Top 5 Medicina descargadas en el periodo {{Carbon::now()->startOfMonth()->format('d/m/Y')}} - {{Carbon::now()->endOfMonth()->format('d/m/Y')}}
                </h4>
            </div>
            <div class="card-body">
                <table class="table table-striped table-valign-middle text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Medicina</th>
                            <th>Categoria</th>
                            <th>Cantidad Descargada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topMediciesDownload as $key => $item)   
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$item->medicine}}</td>
                            <td>{{$item->category}}</td>
                            <td>{{$item->subtotal * -1}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@stop
