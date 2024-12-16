<tr class="row-{{$request->id}}">
    <td> {{$request->name}}</td>
    <td> {{$request->stock}}</td>
    <td>
        <select class="form-control select_stock select_{{$request->stock_id.'_'.$request->id}}" name="to_stock_id[]" data-url="{{route('stock.select_qty.ajax')}}" data-id="{{$request->id}}" data-stock="{{$request->stock_id}}" data-qty="{{$request->qty}}">
            <option value>اختر مخزن</option>
            @foreach($stocks as $stock)
                <option value="{{$stock->id}}">{{$stock->name}}</option>
            @endforeach
        </select>
    </td>
    <td>
    <input type="number" class="form-control input-sm input-qty dis input_{{$request->stock_id.'_'.$request->id}}" disabled value="1" min="1" data-id="{{$request->id}}" data-stock="{{$request->stock_id}}" data-qty="{{$request->qty}}"/>
    </td>
    <td>
        <a href="" class="btn btn-danger btn-sm btn-remove-product rounded-circle remove_{{$request->stock_id.'_'.$request->id}}" id="{{ $request->id}}" data-qty="{{$request->qty}}" data-stock="{{$request->stock_id}}">
            <i class="fa fa-trash"></i>
        </a>
        <input type="hidden" name="from_product_id[]" value="{{$request->id}}"/>
        <input type="hidden" name="from_stock_id[]" value="{{$request->stock_id}}"/>
        <input type="hidden" class="from_qty_{{$request->stock_id}}_{{$request->id}}" name="from_qty[]" value="{{$request->qty - 1}}"/>
        <input type="hidden" class="to_qty_{{$request->stock_id}}_{{$request->id}}" name="to_qty[]" data-qty=""/>
    </td>
</tr>