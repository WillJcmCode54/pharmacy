@php
    use Carbon\Carbon;
    $date = Carbon::parse($book->publication_year);
    $date = $date->format('d-m-Y');
@endphp
<div class="row">
    <div class="col-lg-6 col-md-12">
        <strong>Título: </strong> <p>{{$book->title}}</p> 
    </div>
    <div class="col-lg-6 col-md-4">
        <strong>Autor: </strong> <p>{{$book->author}}</p> 
    </div>
    <div class="col-lg-4 col-md-3">
        <strong>Editorial: </strong> <p>{{$book->editorial}}</p> 
    </div>
    <div class="col-lg-4 col-md-3">
        <strong>Año de publicacion: </strong> <p>{{$date}}</p> 
    </div>
    <div class="col-lg-4 col-md-3">
        <strong>Estanteria: </strong> <p>{{$book->shelf}}</p> 
    </div>
    <div class="col-lg-4 col-md-3">
        <strong>Genero: </strong> <p>{{$book->genre}}</p> 
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <strong>Descripcion: </strong> <p>{{$book->decription}}</p> 
    </div>
</div>
