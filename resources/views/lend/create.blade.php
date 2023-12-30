
@extends('adminlte::page')

@section('title', 'Prestamos')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1>Prestamos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
                    <li class="breadcrumb-item" ><a href="{{route('lend.index')}}">Prestamos</a></li>
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
        <h3 class="card-title">Crear Prestamos</h3>
    </div>
    <form action="{{ route('lend.store') }}" method="post">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p><Strong>Codigo de Referencia:</Strong> {{$newReferece}}</p>
                    <input type="hidden" name="code" value="{{$newReferece}}">
                </div>
                <div class="col-md-4">
                    <p><Strong>Tipo de Prestamos:</Strong> Prestar</p>
                </div>
                <div class="col-md-4">
                    @php
                        $startDate = (old('dateRange')) ? explode("-", old('dateRange'))[0] : Carbon::today();
                        $endDate = (old('dateRange')) ? explode("-", old('dateRange'))[1] : Carbon::today();
                        $config = [
                            "timePicker" => false,
                            "startDate" => $startDate,
                            "endDate" => $endDate,
                            "locale" => [
                                "format" => "YYYY/MM/DD",
                                "fromLabel"=> "Desde",
                                "toLabel"=> "Hasta",
                                "daysOfWeek" => ["D","L","M","M","J","V","S"],
                                "monthNames"=> ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
                            ]
                        ];
                        @endphp

                    {{-- Label and placeholder --}}
                    <x-adminlte-date-range name="dateRange" :config="$config" label-class="@error('dateRange') is-invalid @enderror">
                        <x-slot name="prependSlot">
                            <div class="input-group-text bg-gradient-info">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                    @error('dateRange')
                        <span class="invalid-feedback" role="alert" style="display: block!important;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
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
                <div class="col-md-6 col-sm-12">
                    <x-adminlte-select2 name="customer_id" id="customer_id" label="Cliente" label-class="text-lightblue"
                        igroup-size="md" data-placeholder="{{__('Agregar Cliente...')}}">
                        <x-slot name="prependSlot">
                            <div class="input-group-text bg-gradient-info">
                                <i class="fas fa-user"></i>
                            </div>
                        </x-slot>
                        <option >{{__('Agregar Cliente...')}}</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{(old('customer_id')) ? "selected": ''}}> {{ $customer->name }} {{ $customer->last_name }}</option>
                        @endforeach
                    </x-adminlte-select2>
                    @error('customer_id')
                        <span class="invalid-feedback" role="alert">
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