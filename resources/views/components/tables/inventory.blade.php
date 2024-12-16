<div class="table-responsive">
    @if($collection->items()->count() > 0)
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
                @foreach ($collection->items as $index => $item)
                    @if($item->pivot->qty > 0)
                        <tr>
                            <td>{{ $index + 1}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$collection->name}}</td>
                            <td>
                                <input type="number" name="qty[]" class="form-control text-center" value="{{$item->pivot->qty}}"  min="0" required>
                                <input type="hidden" name="stocks[]" class="form-control text-center" value="{{$collection->id}}" min="0" required>
                                <input type="hidden" name="items[]" class="form-control text-center" value="{{$item->id}}" min="0" required>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else                   
        <div class="text-center">لاتوجد بيانات</div>
    @endif
</div>
