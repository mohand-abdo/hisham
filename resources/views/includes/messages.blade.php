<div class="row">
    <div class="col">        
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{session('success')}}
            </div>
        @endif
    </div>
    @if ($errors->any())
        <div class="col-lg-12 alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('error'))
        <div class="col-lg-12 alert alert-danger">
            {{session('error')}}
        </div>
    @endif
</div>