
@extends('adminlte::page')

@section('title', 'Movimientos')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1>Movimientos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
                    <li class="breadcrumb-item" ><a href="{{route('movement.index')}}">Movimientos</a></li>
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

    @php
        use Carbon\Carbon;
    @endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Crear Movimiento</h3>
    </div>
    <form action="{{ route('movement.store') }}" method="post">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p><Strong>Codigo de Referencia:</Strong> {{$newReferece}}</p>
                    <input type="hidden" name="code" value="{{$newReferece}}">
                </div>
                <div class="col-md-4">
                    <p><Strong>Fecha:</Strong> {{Carbon::today()->format('d-m-Y')}}</p>
                    <input type="hidden" name="date" value="{{Carbon::today()->format('Y-m-d')}}">
                </div>
                <div class="col-md-4">
                    <p><Strong>Tipo de Movimiento:</Strong> 
                    @if ($type_movement == "load")
                        Cargar
                    @else
                        Descargar
                    @endif
                    </p>
                    <input type="hidden" name="type_movement" value="{{$type_movement}}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <x-adminlte-select2 name="book_id_select" id="book_id_select" label="Libro" label-class="text-lightblue"
                        igroup-size="md" data-placeholder="{{__('Agregar Libro...')}}">
                        <x-slot name="prependSlot">
                            <div class="input-group-text bg-gradient-info">
                                <i class="fas fa-book"></i>
                            </div>
                        </x-slot>
                        <option>{{__('Agregar Libro...')}}</option>
                        @foreach ($books as $book)
                            <option value="{{ $book->id }}"> {{ $book->title }} {{ $book->author}} ({{Carbon::parse($book->publication_year)->format('d-m-Y')}})</option>
                        @endforeach
                    </x-adminlte-select2>
                    @error('book_id')
                        <span class="invalid-feedback" role="alert" style="display: block!important;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
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
                                <th><i class="fa fa-lg fa-fw fa-trash"></i></th>
                            </thead>
                            <tbody id="tbody">
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
@section('js')
    <script>
        var selectItems = $('#book_id_select');

        //============ Add product ==================
        selectItems.on("select2:select", async function (e) {
            var la = selectItems.select2('data');
            const id_article = la[0].id;
            if($.isNumeric(id_article)){
                var response = await fetch(`/book/check/${id_article}`, {
                    headers: { 'Content-Type': 'application/json' }
                 });
                const item = await response.json();
                if (!$(`tr#${item.id}`).length) {
                    var quantity = 1;
                    let htmltable = `<tr id="${item.id}">
                                        <input type="hidden" name="book_id[]" value="${item.id}">
                                        <td>${item.id}</td>
                                        <td>${item.title} de ${item.author} (${item.publication_year})</td>
                                        <td><input type="number" name="quantity[${item.id}]" class="form-control quantity" value="${quantity}"  min="1" step="any"></td>
                                        <td><a class="btn btn-outline-danger delete-article"><i class="fas fa-trash-alt delete-article"></i></a></td>
                                    </tr>`;
                    $('tbody').append(htmltable);
                }
                $("#book_id").val('').trigger('change')
            }
        });

        //======== Delete Item =======
        $(document).on('click','.delete-article', function(event) {
            var tr = event.target.closest("tr");
            let RemoveItems =  $(`tr#${tr.id}`);
            RemoveItems.remove();
        });
    </script>
@stop