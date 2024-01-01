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
    <div class="col-md-4">
        <p><Strong>Codigo de Referencia:</Strong> {{$movements->customer}}</p>
    </div>
    <div class="col-md-4">
        <p><Strong>total:</Strong> {{($movements->total < 0 ) ? number_format($movements->total*-1,2) : number_format($movements->total,2)}}</p>
    </div>
</div>
<div class="row p-2">
    <p><strong>Libros</strong></p>
    <div class="table-responsive">
        <table class="table table-md">
            <thead>
                <th>#</th>
                <th>Medicina</th>
                <th>Estanterias</th>
                <th>Cantidad</th>
                <th>Monto</th>
                <th>Subtotal</th>
            </thead>
            <tbody id="tbody">
                @foreach ($movementsDetails as $detail)
                <tr>
                    <td>{{$detail->id}}</td>
                    <td>{{$detail->name}}</td>
                    <td>{{$detail->shelf}}</td>
                    <td>{{ ($detail->quantity < 0) ? $detail->quantity*-1 : $detail->quantity}}</td>
                    <td>{{number_format($detail->amount,2)}}</td>
                    <td id="subtotal">{{($detail->subtotal < 0 ) ? number_format($detail->subtotal*-1,2) : number_format($detail->subtotal,2)}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
