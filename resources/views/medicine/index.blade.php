
@extends('adminlte::page')

@section('title', 'Libros')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1>Libros</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Libros</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
@php
    use Carbon\Carbon;
@endphp
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
<div class="card">
    <div class="card-header">
        <h3 class="card-title">listado de Libros</h3>
        <div class="card-tools">
            <a class="btn btn-primary btn-md" href="{{route('medicine.create')}}">{{ __('Crear') }}</a>
        </div>
    </div>
    <div class="card-body">
        {{-- Setup data for datatables --}}
    @php
        $heads = [
            'ID',
            'Nombre',
            'Descripcion',
            'Caduca',
            'Monto',
            'Estantería',
            'Categoria',
            ['label' => '#', 'no-export' => true],
        ];
    @endphp

{{-- Minimal example / fill data using the component slot --}}
<x-adminlte-datatable id="table1" :heads="$heads">
    @foreach( $medicines as $medicine)
        <tr>
            <td>{{$medicine->id}}</td>
            <td>{{$medicine->name}}</td>
            <td>{{$medicine->decription}}</td>
            <td>{{Carbon::parse($medicine->expiration_date)->format('d-m-Y')}}</td>
            <td>{{$medicine->amount}}</td>
            <td>{{$medicine->shelf}}</td>
            <td>{{$medicine->category}}</td>
            <td> 
                <div class="btn-group">
                    <a class="btn btn-xs btn-default text-teal" 
                        data-title="Cliente {{$medicine->name}}" 
                        data-size="lg" 
                        data-url="{{route('medicine.show',['medicine'=> $medicine->id])}}"
                        data-action="show-modal"><i class="fa fa-lg fa-fw fa-eye"></i>
                    </a>
                    <a href="{{route('medicine.edit',['medicine'=>$medicine->id])}}" class="btn btn-xs btn-default text-primary" title="Edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>
                    <button class="btn btn-xs btn-default text-danger" 
                        data-action="delete-modal" 
                        data-url="{{route('medicine.destroy',['medicine'=> $medicine->id])}}" 
                        data-title="Eliminar a {{$medicine->name}}"><i class="fa fa-lg fa-fw fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    @endforeach
</x-adminlte-datatable>

{{-- modal delete --}}
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
                <div id="modalDContent" class="modal-body">
                Una vez presiones acceptar se eliminara el registro parmanentemente
                @csrf
                @method('delete')
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
        const target = e.target.closest('a[data-action="show-modal"]');
        if (target) {
            e.preventDefault();
            document.querySelector('#modalLabel').innerHTML= target.dataset.title;
            const response = await fetch(target.dataset.url);
            const body =  await response.text();
            document.querySelector('#modalContent').innerHTML = body;
            document.querySelector('#modal-dialog').classList.add('modal-'+target.dataset.size);
            $('#commonModal').modal('show');
        }else{
            var getDelete = e.target.closest('button[data-action="delete-modal"]');
            if(getDelete){
                e.preventDefault();
                document.querySelector('#modalDLabel').innerHTML= getDelete.dataset.title;
                document.querySelector('form[id="deleteFormModal"]').action= getDelete.dataset.url
                $('#deleteModal').modal('show');
            }
        }
    })
</script>
@stop

