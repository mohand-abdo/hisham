<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label for="name">الاسم</label>
            <input id="name" class="form-control"  name="name" value="{{ old('name') ?? $client->name}}"/>
        </div>
    </div>
</div>

@if ($client->id == '')
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="money">متاخرات</label>
                <input type="number" id="money" class="form-control"  name="price" value="{{old('price')}}"/>
            </div>
        </div>
    </div>
@endif

@for($i = 0 ; $i < 3 ; $i++)
    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">
                <label for="phone-{{$i}}">التلفون{{$i == 0 ? ' الاول':($i == 1 ? ' التاني' : ' التالت' )}}</label>
                <input type="number" id="phone-{{$i}}" class="form-control"  name="phone[]" value="{{ old('phone.'.$i) ?? $client->phone[$i] ?? ''}}"/>
            </div>
        </div>
    </div>
@endfor

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label for="name">العنوان</label>
            <textarea id="address" class="form-control" name="address" rows="4">{{ old('address') ?? $client->address }}</textarea>
        </div>
    </div>
</div>


<div class="form-group">
    <div class="custom-control custom-checkbox small">
        <input type="checkbox" class="custom-control-input" id="friend" name="friend"  {{old('friend') ? 'checked' : ($client->friend == 1 ? 'checked' : '')}}>
        <label class="custom-control-label" for="friend">زميل صاحب دكان</label>
    </div>
</div>


