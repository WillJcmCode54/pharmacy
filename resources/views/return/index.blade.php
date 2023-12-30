
@extends('adminlte::page')

@section('title', 'Devoluciones')

@section('content_header')
<div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1>Devoluciones</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Devoluciones</li>
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
        <h3 class="card-title">listado de Devoluciones</h3>
    </div>
    <div class="card-body">
        {{-- Setup data for datatables --}}
    @php
        $heads = [
            'ID',
            'Referencia',
            'fecha',
            'fecha limite',
            'fecha de devolucion',
            'tipo de movimiento',
            'Estatus',
            'Cliente',
            ['label' => '#', 'no-export' => true],
        ];
    @endphp

{{-- Minimal example / fill data using the component slot --}}
<x-adminlte-datatable id="table1" :heads="$heads">
    @foreach( $returns as $return)
        <tr>
            <td>{{$return->id}}</td>
            <td>{{$return->code}}</td>
            @php
                $dateLoan = Carbon::parse($return->loan_date);
                $dateLoan = $dateLoan->format('d-m-Y');

                $dateReturn = Carbon::parse($return->return_date);
                $dateReturn = $dateReturn->format('d-m-Y');

                if (!is_null($return->real_date)) {
                    $dateReal = Carbon::parse($return->real_date);
                    $dateReal = $dateReal->format('d-m-Y');
                }else{
                    $dateReal = $return->real_date;
                }
            @endphp
            <td>{{$dateLoan}}</td>
            <td>{{$dateReturn}}</td>
            <td>{{$dateReal}}</td>
            <td>@if ($return->type_movement == "return") Devolucion @else Prestamo @endif</td>
            <td>
                @if (!is_null($return->customer))
                    {{$return->customer}}  {{$return->last_name}}
                @else
                    {{$return->user}} (Usuario)
                @endif
            </td>
            <td> 
                <div class="btn-group">
                    <a class="btn btn-xs btn-default text-teal" 
                        data-title="Movimiento {{$return->code}}" 
                        data-size="lg" 
                        data-url="{{route('return.view',['id'=> $return->id])}}"
                        data-action="show-modal"><i class="fa fa-lg fa-fw fa-eye"></i>
                    </a>
                    @if ($return->type_movement == 'lend')     
                        <a href="{{route('return.edit',['id'=> $return->id])}}" class="btn btn-xs btn-default text-primary" title="Editar"><i class="fa fa-lg fa-fw fa-pen"></i></a>
                        <button class="btn btn-xs btn-default text-warning"  
                            title="Asentar"
                            data-action="status-modal" 
                            data-url="{{route('return.status',['id'=> $return->id])}}" 
                            data-title="Asentar el movimiento {{$return->code}}"><i class="fas fa-fw fa-thumbtack "></i>
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

{{--Modal view--}}

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

