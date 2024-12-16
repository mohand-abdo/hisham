<div class="table-responsive">
    <table class="table table-right table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">اسم المنتج</th>
                <th scope="col">السعر</th>
                <th scope="col">الكمية</th>
                <th scope="col">الاجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchase->items as $index => $item)
                <tr>
                    <td>{{ $index + 1}}</td>
                    <td>{{ $item->name }}</td>
                    <td>
                        @if ($purchase->uncash_type == 1 || $purchase->uncash_type =='' )
                            {{ $item->pivot->item_price }}
                        @else
                            {{ $item->purches_price }}
                        @endif
                    </td>
                    <td>{{ $item->pivot->qty }}</td>
                    <td>
                        @if ($purchase->uncash_type == 1 || $purchase->uncash_type == '')
                            {{ $item->pivot->item_price * $item->pivot->qty }}
                        @else
                            {{$item->pivot->qty * $item->purches_price}}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
