
@extends('adminlte::page')

@section('title', 'Categorías')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1>Categorías</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
                    <li class="breadcrumb-item" ><a href="{{route('category.index')}}">Categorías</a></li>
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
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Editar Categoría</h3>
    </div>
    <form action="{{ route('category.update',['id'=> $category->id]) }}" method="post">
        @csrf
        @method("PUT")
        <div class="card-body">
            {{-- Name field --}}
            <div class="row">
                <div class="col-12">
                    <div class="input-group mb-3">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ $category->name }}" placeholder="{{ __('Nombre') }}" required="required"autofocus>
            
                        <div class="input-group-append">
                            <div class="input-group-text bg-primary">
                                <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                            </div>
                        </div>
            
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <x-adminlte-textarea name="decription" label="Descripcion" rows=5  igroup-size="sm" placeholder="Description...">{{ $category->decription }}
                        <x-slot name="prependSlot">
                            <div class="input-group-text bg-primary">
                                <i class="fas fa-lg fa-file-alt text-white"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-textarea>
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
