<div class="row">
    <div class="col-7">
        <div class="form-group">
            <label for="name">اسم المخزن</label>
            <input id="name" class="form-control"  name="name" value="{{ old('name') ?? $stock->name}}"/>
        </div>
    </div>
</div>
