
@extends('adminlte::page')

@section('title', 'Medicinas')

@section('content_header')

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1>Medicinas</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
                    <li class="breadcrumb-item" ><a href="{{route('medicine.index')}}">Medicinas</a></li>
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
        <h3 class="card-title">Editar Medicina</h3>
    </div>
    <form action="{{ route('medicine.update', ['medicine' => $medicine->id]) }}" method="post">
        @csrf
        @method('PUT')
        <div class="card-body">
            {{-- Name field --}}
            <div class="row">
                <div class="col-md-6">
                    <label for="name">Nombre</label>
                    <div class="input-group mb-3">
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') ? old('name') :  $medicine->name }}" placeholder="{{ __('Nombre') }}" required="required"autofocus>
            
                        <div class="input-group-append">
                            <div class="input-group-text bg-primary">
                                <span class="fas fa-medicine {{ config('adminlte.classes_auth_icon', '') }}"></span>
                            </div>
                        </div>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="expiration_date">Fecha de Caducidad</label>
                    <div class="input-group mb-3">
                        <input type="date" name="expiration_date" id="expiration_date" class="form-control @error('expiration_date') is-invalid @enderror" value="{{ old('expiration_date') ? old('expiration_date') : $medicine->expiration_date }}" placeholder="{{ __('Fecha de caducidad') }}" required="required"autofocus pattern="\d{4}">
                    
                        <div class="input-group-append">
                            <div class="input-group-text bg-primary">
                                <span class="fas fa-calendar {{ config('adminlte.classes_auth_icon', '') }}"></span>
                            </div>
                        </div>
                        @error('expiration_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {{-- With prepend slot, label and data-placeholder config --}}
                    <label for="shelf_id">Estantería</label>
                    <x-adminlte-select2 name="shelf_id" id="shelf_id" label-class="text-lightblue" igroup-size="md" data-placeholder="Estanteria">
                        <x-slot name="appendSlot">
                            <div class="input-group-text bg-primary">
                                <i class="fas fa-border-all"></i>
                            </div>
                        </x-slot>
                        <option>Seleccione Estanteria</option>
                        @foreach ($shelfs as $shelf)
                            <option value="{{$shelf->id}}" {{ (old('shelf_id') == $shelf->id || $shelf->id == $medicine->shelf_id) ? 'selected' :''}}>{{$shelf->name}}</option>
                        @endforeach
                    </x-adminlte-select2>
                    
                    @error('shelf_id')
                        <span class="invalid-feedback" role="alert" style="display: block!important;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-6">
                    {{-- With prepend slot, label and data-placeholder config --}}
                    <label for="category_id">Categoria</label>
                    <x-adminlte-select2 name="category_id" id="category_id" label-class="text-lightblue" igroup-size="md" data-placeholder="Categoria">
                        <x-slot name="appendSlot">
                            <div class="input-group-text bg-primary">
                                <i class="fas fa-fw fa-file-alt "></i>
                            </div>
                        </x-slot>
                        <option>Seleccione Categoria</option>
                        @foreach ($categories as $category)
                            <option value="{{$category->id}}" {{old('category_id') == $category->id || $category->id == $medicine->category_id ? 'selected' :''}}>{{$category->name}}</option>
                        @endforeach
                    </x-adminlte-select2>
                    
                    @error('category_id')
                        <span class="invalid-feedback" role="alert" style="display: block!important;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="amount">Monto</label>
                        <input type="number" name="amount" id="amount" class="form-control" value="{{old('amount') ? old('amount') : $medicine->amount}}" min="0" step="any">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <x-adminlte-textarea name="decription" label="Descripcion" rows=5  igroup-size="sm" placeholder="Inserte descripcion">{{ old('decription') ? old('decription') : $medicine->decription }}
                        <x-slot name="prependSlot">
                            <div class="input-group-text bg-primary">
                                <i class="fas fa-pen-alt text-white"></i>
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
