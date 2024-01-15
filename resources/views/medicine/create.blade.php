
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
                    <li class="breadcrumb-item active">Crear</li>
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
    @php
        use Carbon\Carbon;
    @endphp
    <div class="card-header">
        <h3 class="card-title">Crear Medicina</h3>
    </div>
    <form action="{{ route('medicine.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            {{-- Name field --}}
            <div class="row">
                <div class="col-md-6">
                    <label for="name">Nombre</label>
                    <div class="input-group mb-3">
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="{{ __('Nombre') }}" required="required"autofocus>
            
                        <div class="input-group-append">
                            <div class="input-group-text bg-primary">
                                <span class="fas fa-book {{ config('adminlte.classes_auth_icon', '') }}"></span>
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
                        <input type="date" name="expiration_date" id="expiration_date" class="form-control @error('expiration_date') is-invalid @enderror" value="{{ old('expiration_date') ? old('expiration_date') : Carbon::today()->format('Y-m-d') }}" placeholder="{{ __('Fecha de caducidad') }}" required="required"autofocus pattern="\d{4}">
                    
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
                            <option value="{{$shelf->id}}" {{old('shelf_id') == $shelf->id ? 'selected' :''}}>{{$shelf->name}}</option>
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
                            <option value="{{$category->id}}" {{old('category_id') == $category->id ? 'selected' :''}}>{{$category->name}}</option>
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
                    <label for="amount">Monto</label>
                    <div class="input-group mb-3">
                        <input type="number" name="amount" id="amount" class="form-control" value="{{old('amount')}}" min="0" step="any">
                        <div class="input-group-append">
                            <div class="input-group-text bg-primary">
                                <span class="fas fa-money-bill-alt {{ config('adminlte.classes_auth_icon', '') }}"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <label for="img">Imagen</label>
                <div class="row justify-content-center w-100">
                    <img id="imgPreview" width="100" height="100"/>
                    <div class="input-group mb-3">
                        <input type="file" name="img" id="img" class="form-control" accept="image/png,image/jpeg">
                        <div class="input-group-append">
                            <div class="input-group-text bg-primary">
                                <span class="fas fa-file-image {{ config('adminlte.classes_auth_icon', '') }}"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <x-adminlte-textarea name="decription" label="Descripcion" rows=5  igroup-size="sm" placeholder="Inserte descripcion">{{ old('decription') }}
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

@section('js')
<script>

    document.addEventListener('change', function (event) {
        //Recuperamos el input que desencadeno la acción
        const input = event.target;
        if(input.closest('input[type="file"]'))
        {            
                //Recuperamos la etiqueta img donde cargaremos la imagen
                $imgPreview = document.querySelector("img#imgPreview");
            
                // Verificamos si existe una imagen seleccionada
                if(!input.files.length) return
            
                //Recuperamos el archivo subido
                file = input.files[0];
            
                //Creamos la url
                objectURL = URL.createObjectURL(file);
            
                //Modificamos el atributo src de la etiqueta img
                $imgPreview.src = objectURL;
        }
    });

                    
</script>
@stop