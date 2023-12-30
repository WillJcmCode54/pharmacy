@php
    use Carbon\Carbon;
@endphp
<div class="row">
    <div class="col-md-6">
        <p><Strong>Codigo de Referencia:</Strong> {{$lends->code}}</p>
    </div>
    <div class="col-md-6">
        <p><Strong>Tipo de Movimiento:</Strong> 
            @if ($lends->type_movement == "lend")
            Prestamo
            @else
            Devolucion
            @endif
        </p>
    </div>
    <div class="col-md-6">
        <p><Strong>Fecha de Prestamos:</Strong> {{Carbon::parse($lends->loan_date)->format('d-m-Y')}}</p>
    </div>
    <div class="col-md-6">
        <p><Strong>Fecha limite:</Strong> {{Carbon::parse($lends->return_date)->format('d-m-Y')}}</p>
    </div>

    <div class="col-md-12">
        <p><Strong>Cliente:</Strong> {{$lends->customer}}  {{$lends->last_name}}</p>
    </div>
</div>
<div class="row p-2">
    <p><strong>Libros</strong></p>
    <div class="table-responsive">
        <table class="table table-md">
            <thead>
                <th>#</th>
                <th>Libro</th>
                <th>Cantidad</th>
            </thead>
            <tbody id="tbody">
                @foreach ($movementsDetails as $detail)
                <tr>
                    <td>{{$detail->book_id}}</td>
                    <td>{{$detail->title}} de {{$detail->author}} ({{$detail->publication_year}})</td>
                    @php
                        $quantity = ($detail->quantity < 0 ) ? $detail->quantity * -1 : $detail->quantity  ;
                        $color = ($detail->quantity < 0 ) ? "danger" : "access"  ;
                    @endphp
                    <td><p class="text-{{$color}}">{{$quantity}}</p></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
