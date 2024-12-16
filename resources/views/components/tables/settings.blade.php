<div class="table-responsive">
    @if($collection->count() > 0)
        <table class="table table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{$name}}</th>
                    <th scope="col" class="text-center w-25">التحكم</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($collection as $index => $item)
                    <tr>
                        <td>{{ $index + 1}}</td>
                        <td>{{$item->name}}</td>
                        <td class="text-center">
                            <a href="{{route($url.'.edit',$item->id)}}" class="btn btn-warning btn-sm rounded-circle" data-toggle="tooltip" data-original-title="تعديل">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{route($url.'.destroy',$item->id)}}" method="post" style="display:inline-block">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger btn-sm rounded-circle" data-toggle="tooltip">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else                   
        <div class="text-center">لاتوجد بيانات</div>
    @endif
</div>