@php
    use Carbon\Carbon;
@endphp
<div class="row">
    <div class="col-lg-6 col-md-12">
        <strong>Nombre: </strong> <p>{{$medicine->name}}</p> 
    </div>
    <div class="col-lg-6 col-md-4">
        <strong>Fecha de caducidad: </strong> <p>{{Carbon::parse($medicine->expiration_date)->format('d-m-Y')}}</p> 
    </div>
    <div class="col-lg-4 col-md-3">
        <strong>Categoria: </strong> <p>{{$medicine->category}}</p> 
    </div>
    <div class="col-lg-4 col-md-3">
        <strong>Estanteria: </strong> <p>{{$medicine->shelf}}</p> 
    </div>
    <div class="col-lg-4 col-md-3">
        <strong>Precio: </strong> <p>{{$medicine->amount}}</p> 
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <strong>Descripcion: </strong> <p>{{$medicine->decription}}</p> 
    </div>
</div>
