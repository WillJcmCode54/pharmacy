
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
                <h3>{{$books}}</h3>
                <p>Libros Disponibles</p>
            </div>
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
            <a href="{{route('book.index')}}" class="small-box-footer">Mas<i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
        
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
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
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{$returns}}</h3>
                <p>Libros Devueltos</p>
            </div>
            <div class="icon">
                <i class="fas fa-retweet"></i>
            </div>
            <a href="{{route('return.index')}}" class="small-box-footer">Mas<i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
        
    <div class="col-lg-3 col-6">
    
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{$lends}}</h3>
                <p>Libros Prestados</p>
            </div>
            <div class="icon">
                <i class="fas fa-thumbtack"></i>
            </div>
            <a href="{{route('return.index')}}" class="small-box-footer">Mas<i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
<div class="row">
       
    <div class="col-lg-12">
        
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">Clientes en Fecha limites</h3>
            </div>
            <div class="card-body">
                @foreach ($defaulters as $key => $defaulter)
                    <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                        <p class="text-info text-xl"> 
                            <i class="fas fa-user-circle"></i>
                            <span class="pl-2 h6">{{ mb_strtoupper($defaulter->customer)}} {{mb_strtoupper($defaulter->last_name)}} </span>
                        </p>
                        <p class="d-flex flex-column text-right">
                            <span class="font-weight-bold">
                            <i class="fas fa-exclamation text-danger"></i> {{Carbon::parse($defaulter->return_date)->format('Y')}}
                            </span>
                            <span class="text-muted">{{$defaulter->book}} ({{Carbon::parse($defaulter->date)->format('Y')}})</span>
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
</div>

@stop
