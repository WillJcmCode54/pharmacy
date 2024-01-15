@php
    use Carbon\Carbon;
@endphp
<div class="container">
    
    <div class="row aling-items-center">
        <div class="col-md-7">
            <strong>Nombre: </strong> <p>{{$medicine->name}}</p> 
            <strong>Fecha de caducidad: </strong> <p>{{Carbon::parse($medicine->expiration_date)->format('d-m-Y')}}</p> 
        </div>
        <div class="col-md-5" style="        max-width: 10rem;        max-height: 10rem;">
            <img src="{{asset($medicine->img)}}" alt="{{$medicine->name}}" style="width: 90%;height: 90%;">
        </div>
    </div>
    <div class="row">
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
</div>
