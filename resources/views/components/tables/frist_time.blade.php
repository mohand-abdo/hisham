<div class="table-responsive">
    @if($items->count() > 0)
        <table class="table table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">اسم الصنف</th>
                    <th scope="col">اسم المخزن</th>
                    <th scope="col" class="text-center">الكمية</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $index => $item)
                        <tr>
                            <td>{{ $index + 1}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$stock->name}}</td>
                            <td>
                                <input type="number" name="qty[]" class="form-control text-center" value="{{ $item->stocks()->where('stock_id', $stock->id)->exists() ? $item->stocks()->find($stock->id)->pivot->qty : '0' }}"  min="0" required>
                                <input type="hidden" name="stocks[]" class="form-control text-center" value="{{$stock->id}}" min="0" required>
                                <input type="hidden" name="items[]" class="form-control text-center" value="{{$item->id}}" min="0" required>
                            </td>
                        </tr>
                @endforeach
            </tbody>
        </table>
    @else                   
        <div class="text-center">لاتوجد بيانات</div>
    @endif
</div>
