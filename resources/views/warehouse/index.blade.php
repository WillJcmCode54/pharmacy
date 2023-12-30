
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
        <h3 class="card-title">listado de Libros</h3>
        <div class="card-tools">
            <a class="btn btn-primary btn-md" href="{{route('book.create')}}">{{ __('Crear') }}</a>
        </div>
    </div>
    <div class="card-body">
        {{-- Setup data for datatables --}}
    @php
        $heads = [
            'ID',
            'Título',
            'Autor',
            'Editiorial',
            'Estantería',
            'Año de publicacion',
            'Genero',
            'Existecia',
            'Descripcion',
        ];
    @endphp

{{-- Minimal example / fill data using the component slot --}}
<x-adminlte-datatable id="table1" :heads="$heads">
    @foreach( $books as $book)
        <tr>
            <td>{{$book->id}}</td>
            <td>{{$book->title}}</td>
            <td>{{$book->author}}</td>
            <td>{{$book->editorial}}</td>
            <td>{{$book->shelf}}</td>
            @php
                $date = Carbon::parse($book->publication_year);
                $date = $date->format('d-m-Y');
            @endphp
            <td>{{$date}}</td>
            <td>{{$book->genre}}</td>
            <td>{{$book->quantity}}</td>
            <td>{{$book->decription}}</td>
        </tr>
    @endforeach
</x-adminlte-datatable>



@stop

