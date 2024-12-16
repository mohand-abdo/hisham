<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label for="name">الاسم</label>
            <input id="name" class="form-control"  name="name" value="{{ old('name') ?? $supplier->name}}"/>
        </div>
    </div>
</div>

@if ($supplier->id == '')
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="money">متاخرات</label>
                <input type="number" id="money" class="form-control"  name="money" value="{{old('money') ?? $supplier->money}}"/>
            </div>
        </div>
    </div>
@endif

@for($i = 0 ; $i < 3 ; $i++)
    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">
                <label for="phone-{{$i}}">التلفون{{$i == 0 ? ' الاول':($i == 1 ? ' التاني' : ' التالت' )}}</label>
                <input type="number" id="phone-{{$i}}" class="form-control"  name="phone[]" value="{{ old('phone.'.$i) ?? $supplier->phone[$i] ?? ''}}"/>
            </div>
        </div>
    </div>
@endfor

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label for="name">العنوان</label>
            <textarea id="address" class="form-control" name="address" rows="4">{{ old('address') ?? $supplier->address }}</textarea>
        </div>
    </div>
</div>