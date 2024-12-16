<div class="table-responsive">
    <table class="table table-right table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
        <thead>
            <tr>
                <th scope="col">اسم المورد</th>
                <th scope="col">التلفون</th>
                <th scope="col">العنوان</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $purchase->supplier->name }}</td>
                <td>{{ implode($purchase->supplier->phone ,'-') }}</td>
                <td>{{ $purchase->supplier->address }}</td>
            </tr>
        </tbody>
    </table>
</div>
