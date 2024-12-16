<div class="table-responsive">
    @if($client_trans->count() > 0)
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
                @foreach ($client_trans as $index => $client_tran)
                    <tr>
                        <td>{{ $index + 1}}</td>
                        <td>{{ $client_tran->from ? $client_tran->from : '-' }}</td>
                        <td>{{ $client_tran->to ? $client_tran->to : '-'}}</td>
                        <td>{{ date_format($client_tran->created_at,'Y-m-d') }}</td>
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