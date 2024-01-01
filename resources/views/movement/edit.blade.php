
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
        <h3 class="card-title">Editar Movimiento</h3>
    </div>
    <form action="{{ route('movement.update',['movement'=> $movements->id]) }}" method="post">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p><Strong>Codigo de Referencia:</Strong> {{$movements->code}}</p>
                </div>
                <div class="col-md-4">
                    <p><Strong>Fecha:</Strong> {{Carbon::parse($movements->created_at)->format('d-m-Y')}}</p>
                </div>
                <div class="col-md-4">
                    <p><Strong>Tipo de Movimiento:</Strong> 
                    @if ($movements->type_movement == "load")
                        Cargar
                    @else
                        Descargar
                    @endif
                    </p>
                    <input type="hidden" name="type_movement" value="{{$movements->type_movement}}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <x-adminlte-select2 name="medicine_id_select" id="medicine_id_select" label="Medicina" label-class="text-lightblue"
                        igroup-size="md" data-placeholder="{{__('Agregar Medicina...')}}">
                        <x-slot name="prependSlot">
                            <div class="input-group-text bg-gradient-info">
                                <i class="fas fa-medicine"></i>
                            </div>
                        </x-slot>
                        <option>{{__('Agregar Medicina...')}}</option>
                        @foreach ($medicines as $medicine)
                            <option value="{{ $medicine->id }}"> {{ $medicine->name }} {{$medicine->shelf}}{{ $medicine->category}}</option>
                        @endforeach
                    </x-adminlte-select2>
                    @error('medicine_id')
                        <span class="invalid-feedback" role="alert" style="display: block!important;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="col-md-6 col-sm-12">
                    <x-adminlte-select2 name="customer_id" id="customer_id_select" label="Cliente" label-class="text-lightblue"
                        igroup-size="md" data-placeholder="{{__('Agregar Cliente...')}}">
                        <x-slot name="prependSlot">
                            <div class="input-group-text bg-gradient-info">
                                <i class="fas fa-book"></i>
                            </div>
                        </x-slot>
                        <option>{{__('Agregar Cliente...')}}</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}"  {{ (old('customer_id') == $customer->id || $customer->id == $movements->customer_id) ? 'selected' :''}}> {{ $customer->name }} {{$customer->last_name}}</option>
                        @endforeach
                    </x-adminlte-select2>
                    @error('customer_id')
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
                                <th>Medicina</th>
                                <th>Estanterias</th>
                                <th>Cantidad</th>
                                <th>Monto</th>
                                <th>Subtotal</th>
                                <th><i class="fa fa-lg fa-fw fa-trash"></i></th>
                            </thead>
                            <tbody id="tbody">
                                @foreach ($movementsDetails as $detail)
                                    <tr id="{{$detail->medicine_id}}">
                                        <input type="hidden" name="medicine_id[]" value="{{$detail->medicine_id}}">
                                        <td>{{$detail->medicine_id}}</td>
                                        <td>{{$detail->medicine}}</td>
                                        <td>{{$detail->shelf}}</td>
                                        <td><input type="number" name="quantity[{{$detail->medicine_id}}]" class="form-control quantity" value="{{ ($detail->quantity < 0) ? $detail->quantity*-1 : $detail->quantity}}"  min="0" step="any"/></td>
                                        <td><input type="hidden" name="amount[{{$detail->medicine_id}}]" class="form-control" value="{{$detail->amount}}"/>{{number_format($detail->amount,2)}}</td>
                                        <td id="subtotal"><input type="hidden" name="subtotal[{{$detail->medicine_id}}]" class="form-control subtotal" value="{{($detail->subtotal < 0 ) ? $detail->subtotal*-1 : $detail->subtotal }}" />{{($detail->subtotal < 0 ) ? number_format($detail->subtotal*-1,2) : number_format($detail->subtotal,2)}}</td>
                                        <td><a class="btn btn-outline-danger delete-article"><i class="fas fa-trash-alt delete-article"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot  >
                                <td colspan="5" class="text-right">TOTAL </td>
                                <td id="total">
                                    <input type="hidden" name="total" id="total" value="{{($movements->total < 0 ) ? $movements->total*-1 : $movements->total}}"/>{{($movements->total < 0 ) ? $movements->total*-1 : $movements->total}}
                            </tfoot>
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
        var selectItems = $('#medicine_id_select');

        // total
        function total() {
            let subtotal = document.querySelectorAll('input.subtotal');
                var total = 0;
                subtotal.forEach(element => {
                    total += parseFloat(element.value);
                });
                document.querySelector("td#total").innerHTML= `<input type="hidden" name="total" id="total" value="${parseFloat(total).toFixed(2)}"/>${parseFloat(total).toFixed(2)}`;
            
        }
        //============ Add product ==================
        selectItems.on("select2:select", async function (e) {
            var la = selectItems.select2('data');
            const id_article = la[0].id;
            if($.isNumeric(id_article)){
                var response = await fetch(`/medicine/check/${id_article}`, {
                    headers: { 'Content-Type': 'application/json' }
                 });
                const item = await response.json();
                
                if (!$(`tr#${item.id}`).length) {
                    var quantity = 1;
                    let htmltable = `<tr id="${item.id}">
                                        <input type="hidden" name="medicine_id[]" value="${item.id}">
                                        <td>${item.id}</td>
                                        <td>${item.name}</td>
                                        <td>${item.shelf}</td>
                                        <td><input type="number" name="quantity[${item.id}]" class="form-control quantity" value="${quantity}"  min="0" step="any"></td>
                                        <td><input type="hidden" name="amount[${item.id}]" class="form-control" value="${(item.amount)}">${parseFloat(item.amount).toFixed(2)}</td>
                                        <td id="subtotal"><input type="hidden" name="subtotal[${item.id}]" class="form-control subtotal" value="${(quantity * item.amount)}" >${parseFloat(quantity * item.amount).toFixed(2)}</td>
                                        <td><a class="btn btn-outline-danger delete-article"><i class="fas fa-trash-alt delete-article"></i></a></td>
                                    </tr>`;
                    $('tbody').append(htmltable);
                }
                $("#medicine_id").val('').trigger('change')

                total();
            }
        });


        document.addEventListener('click', function(e){
            var event = e.target;
            if(event.closest('a.delete-article')){
                let itemRemove = event.closest('tr');
                itemRemove.remove();
                total();
            }
        });

        document.addEventListener('input', function (e) {
            var event = e.target
            if(event.closest('input.quantity')){
                let quantity = event.value;
                let tr = event.closest('tr');
                let amount = tr.querySelector('td > input[name="amount['+tr.id+']"]').value;
                let td_subtotal = tr.querySelector('td#subtotal');
                td_subtotal.innerHTML= `<input type="hidden" name="subtotal[${tr.id}]" class="form-control subtotal" value="${(parseFloat(quantity * amount).toFixed(2))}" >${parseFloat(quantity * amount).toFixed(2)}`;
                total();
            }
        })

        
    </script>
@stop