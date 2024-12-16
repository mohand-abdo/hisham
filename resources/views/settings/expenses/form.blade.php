<div class="row">
    <div class="col-7">
        <div class="form-group">
            <label for="name">اسم المصروف</label>
            <input id="name" class="form-control"  name="name" value="{{ old('name') ?? $expense->name}}"/>
        </div>
    </div>
</div>
