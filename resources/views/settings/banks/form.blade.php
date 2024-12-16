<div class="row">
    <div class="col-7">
        <div class="form-group">
            <label for="name">اسم البنك</label>
            <input id="name" class="form-control"  name="name" value="{{ old('name') ?? $bank->name}}"/>
        </div>
    </div>
</div>

@if ($bank->id == '')
    <div class="row">
        <div class="col-7">
            <div class="form-group">
                <label for="money">المبلغ </label>
                <input id="money" class="form-control"  name="money" value="{{ old('money') ?? $bank->money}}"/>
            </div>
        </div>
    </div>
@endif