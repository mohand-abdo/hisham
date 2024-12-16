<div class="table-responsive">
    @if($supplier_trans->count() > 0)
        <table class="table table-right table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">من</th>
                    <th scope="col">الى</th>
                    <th scope="col">التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($supplier_trans as $index => $supplier_tran)
                    <tr>
                        <td>{{ $index + 1}}</td>
                        <td>{{ $supplier_tran->from ? $supplier_tran->from : '-' }}</td>
                        <td>{{ $supplier_tran->to ? $supplier_tran->to : '-'}}</td>
                        <td>{{ date_format($supplier_tran->created_at,'Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="text-center">لاتوجد بيانات</div>
    @endif
</div>
<script>
    $('.table-right').DataTable({
        "language":{
            "url":"assets/js/arabic.json"
        },
    });
</script>