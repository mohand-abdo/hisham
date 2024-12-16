<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label for="name">اسم الوحدة</label>
            <input id="name" class="form-control"  name="name" value="{{ old('name') ?? $item->name}}"/>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label for="category_id">القسم</label>
            <select id="category_id" class="form-control" name="category_id">
                @forelse ($categories as $category)
                    <option value="{{$category->id}}" {{$category->id == old('category_id')?'selected':'' }} {{ $category->id == $item->category_id?'selected':''}}>
                        {{$category->name}}
                    </option>
                @empty
                    <option>عفوا لا توجد اقسام متاحة</option>
                @endforelse
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-3">
        <div class="form-group">
            <label for="purches_price">سعر الشراء</label>
            <input type="number" id="purches_price" class="form-control"  name="purches_price" value="{{ old('purches_price') ?? $item->purches_price}}"/>
        </div>
    </div>
    
    <div class="col-3">
        <div class="form-group">
            <label for="sale_price">سعر البيع</label>
            <input type="number" id="sale_price" class="form-control"  name="sale_price" value="{{ old('sale_price') ?? $item->sale_price}}"/>
        </div>
    </div>
</div>




{{-- <div class="row">
    <div class="col-3">
        <div class="form-group">
            <label for="qty">الكمية</label>
            <input type="number" id="qty" class="form-control"  name="qty" value="{{ old('qty') ?? $item->qty}}"/>
        </div>
    </div>
</div> --}}
