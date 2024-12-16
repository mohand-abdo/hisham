<div class="row">
    <div class="col-7">
        <div class="form-group">
            <label for="name">اسم الخزينة</label>
            <input id="name" class="form-control"  name="name" value="{{ old('name') ?? $safe->name}}"/>
        </div>
    </div>
</div>

@if ($safe->id == '')
    <div class="row">
        <div class="col-7">
            <div class="form-group">
                <label for="money">المبلغ </label>
                <input id="money" class="form-control"  name="money" value="{{ old('money') ?? $safe->money}}"/>
            </div>
        </div>
    </div>
@endif
