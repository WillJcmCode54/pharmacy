
@extends('adminlte::page')

@section('title', 'Movimientos')

@section('content_header')
<div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1>Movimientos de inventario</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Movimiento de inventario</li>
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
    <x-adminlte-alert theme="danger" title="Atencion" dismissable> 
        {{$message}}
    </x-adminlte-alert>
@endif
@if($message = Session::get('warning'))
    <x-adminlte-alert theme="warning" title="Atencion" dismissable> 
        {{$message}}
    </x-adminlte-alert>
@endif
@php
    use Carbon\Carbon;
@endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title">listado de Movimientos</h3>
        <div class="card-tools">
            {{-- <a class="btn btn-primary btn-md" href="{{route('movement.create')}}">{{ __('Crear') }}</a> --}}
            <form action="{{route('movement.create')}}" method="get">
                <div class="btn-group">
                    <button type="submit" value="load" name="type" class="btn btn-success">Cargar</button>
                    <button type="submit" value="download" name="type" class="btn btn-danger">Descargar</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body">
        {{-- Setup data for datatables --}}
    @php
        $heads = [
            'ID',
            'Referencia',
            'fecha',
            'tipo de movimiento',
            'Estatus',
            'Cliente',
            ['label' => '#', 'no-export' => true],
        ];
    @endphp

{{-- Minimal example / fill data using the component slot --}}
<x-adminlte-datatable id="table1" :heads="$heads">
    @foreach( $movements as $movement)
        <tr>
            <td>{{$movement->id}}</td>
            <td>{{$movement->code}}</td>
            @php
                $date = Carbon::parse($movement->create_at);
                $date = $date->format('d-m-Y');
                @endphp
            <td>{{$date}}</td>
            @switch($movement->type_movement)
                @case("load")
                    <td>Cargar</td>
                    @break
                @case("download")
                    <td>Descargar</td>
                        @break
                @case("lend")
                    <td>Prestado</td>
                        @break
                    @default
                    <td>Devuelto</td>
                @endswitch
            <td><span class="badge @if($movement->status == 'saved') bg-success @else bg-warning @endif">{{$movement->status}}</span></td>
            <td>
                @if (!is_null($movement->customer))
                    {{$movement->customer}}
                @else
                    {{$movement->user}} (Usuario)
                @endif
            </td>
            <td> 
                <div class="btn-group">
                    <a class="btn btn-xs btn-default text-teal" 
                        data-title="Movimiento {{$movement->code}}" 
                        data-size="lg" 
                        data-url="{{route('movement.show',['movement'=> $movement->id])}}"
                        data-action="show-modal"><i class="fa fa-lg fa-fw fa-eye"></i>
                    </a>
                    @if ($movement->status == 'saved')     
                        <a href="{{route('movement.edit',['movement'=> $movement->id])}}" class="btn btn-xs btn-default text-primary" title="Editar"><i class="fa fa-lg fa-fw fa-pen"></i></a>
                        <button class="btn btn-xs btn-default text-warning"  
                            title="Asentar"
                            data-action="status-modal" 
                            data-url="{{route('movement.status',['movement'=> $movement->id])}}" 
                            data-title="Asentar el movimiento {{$movement->code}}"><i class="fas fa-fw fa-thumbtack "></i>
                        </button>
                        <button class="btn btn-xs btn-default text-danger" 
                            title="Eliminar"
                            data-action="delete-modal" 
                            data-url="{{route('movement.destroy',['movement'=> $movement->id])}}" 
                            data-title="Eliminar el movimiento {{$movement->code}}"><i class="fa fa-lg fa-fw fa-trash"></i>
                        </button>
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
</x-adminlte-datatable>

{{-- modal Status --}}
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSLabel"></h5>
                <a type="a" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <form action="#" method="post" id="statusFormModal">
                <div id="modalSContent" class="modal-body">
                Al asentar el registro este no podra ser editado.
                @csrf
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning" type="submit">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>
 {{-- Modal delete --}}
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDLabel"></h5>
                <a type="a" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <form action="#" method="post" id="deleteFormModal">
                @csrf
                @method('delete')
                <div id="modalDContent" class="modal-body">
                    Una vez presiones acceptar se eliminara el registro parmanentemente
                @csrf
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="submit">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>



{{--Modal show--}}

<div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" id="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel"></h5>
                <a type="a" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div id="modalContent" class="modal-body">

            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script>
    document.addEventListener('click',async function (e) {
        if (e.target.closest('a[data-action="show-modal"]')) {
            let target = e.target.closest('a[data-action="show-modal"]');
            e.preventDefault();
            document.querySelector('#modalLabel').innerHTML= target.dataset.title;
            const response = await fetch(target.dataset.url);
            const body =  await response.text();
            document.querySelector('#modalContent').innerHTML = body;
            document.querySelector('#modal-dialog').classList.add('modal-'+target.dataset.size);
            $('#commonModal').modal('show');
        }

        if(e.target.closest('button[data-action="delete-modal"]')){
            let getDelete = e.target.closest('button[data-action="delete-modal"]');
            console.log(getDelete);
            e.preventDefault();
            document.querySelector('#modalDLabel').innerHTML= getDelete.dataset.title;
            document.querySelector('form[id="deleteFormModal"]').action= getDelete.dataset.url
            $('#deleteModal').modal('show');
        }

        if(e.target.closest('button[data-action="status-modal"]')){
            let getStatus = e.target.closest('button[data-action="status-modal"]');
            console.log(getStatus);
            e.preventDefault();
            document.querySelector('#modalSLabel').innerHTML= getStatus.dataset.title;
            document.querySelector('form[id="statusFormModal"]').action= getStatus.dataset.url
            $('#statusModal').modal('show');
        }
        
    })
</script>
@stop

