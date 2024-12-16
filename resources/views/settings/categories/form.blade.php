<div class="row">
    <div class="col-7">
        <div class="form-group">
            <label for="name">اسم القسم</label>
            <input id="name" class="form-control"  name="name" value="{{ old('name') ?? $category->name}}"/>
        </div>
    </div>
</div>
