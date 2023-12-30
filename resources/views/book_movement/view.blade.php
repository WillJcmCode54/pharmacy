@php
    use Carbon\Carbon;
@endphp
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
                <tr id="{{$detail->book_id}}">
                    <input type="hidden" name="book_id[]" value="{{$detail->book_id}}">
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
