<!DOCTYPE html>
<html lang="ar">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>@yield('title')</title>

  {!! Html::style('assets/vendor/fontawesome-free/css/all.min.css') !!}
  {!! Html::style('assets/css/sb-admin-2.min.css') !!}
  {!! Html::style('assets/css/dataTables.bootstrap4.min.css') !!}
  @yield('css')
  {!! Html::style('assets/css/style.css') !!}

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div class="" id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion {{ slider_move() }}" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('home')}}">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Hisham</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="{{ route('home') }}">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>لوحة التحكم</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">


      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#purchase" aria-expanded="true" aria-controls="user">
          <i class="fa fa-cart-plus"></i>
          <span>المشتريات</span>
        </a>
        <div id="purchase" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-primary py-2 collapse-inner rounded">
            <h6 class="collapse-header">التحكم في المشتريات:</h6>
            <a class="collapse-item" href="{{route('purchase.create')}}">فاتورة شراء</a>
            <a class="collapse-item" href="{{route('purchase.index')}}">فواتير المشتريات المستحقة</a>
            <a class="collapse-item" href="{{route('purchase.uncash')}}">فواتير المشتريات غير المستحقة</a>
            <a class="collapse-item" href="{{route('purchase.time_out.close')}}">الفواتير التي اقترب  موعد الاستحقاق</a>
            <a class="collapse-item" href="{{route('purchase.time_out.over')}}">الفواتير التي تجاوزت موعد الاستحقاق</a>
          </div>
        </div>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider">

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#sale" aria-expanded="true" aria-controls="user">
          <i class="fa fa-cart-arrow-down"></i>
          <span>المبيعات</span>
        </a>
        <div id="sale" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-primary py-2 collapse-inner rounded">
            <h6 class="collapse-header">التحكم في المبيعات:</h6>
            <a class="collapse-item" href="{{route('sales.create')}}">فاتورة مبيعات</a>
            <a class="collapse-item" href="{{route('sales.index')}}">فواتير المبيعات المستحقة</a>
            <a class="collapse-item" href="{{route('sale.uncash')}}">فواتير المبيعات غير المستحقة</a>
            <a class="collapse-item" href="{{route('sale.time_out.close')}}">الفواتير التي اقترب  موعد الاستحقاق</a>
            <a class="collapse-item" href="{{route('sale.time_out.over')}}">الفواتير التي تجاوزت موعد الاستحقاق</a>
          </div>
        </div>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider">

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#building" aria-expanded="true" aria-controls="building">
          <i class="fas fa-fw fa-building"></i>
          <span>المخازن</span>
        </a>
        <div id="building" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-primary py-2 collapse-inner rounded">
            <h6 class="collapse-header">التحكم في المخازن:</h6>
            <a class="collapse-item" href="{{route('stock.index')}}">عرض المخازن</a>
            <a class="collapse-item" href="{{route('stock.create')}}">اضافة مخزن</a>
            <a class="collapse-item" href="{{route('stock.transform.create')}}"> التحويل بين المخازن</a>
            <a class="collapse-item" href="{{route('stock.inventory')}}">جرد المخازن</a>
            <a class="collapse-item" href="{{route('stock.decrase')}}">نواقص المخازن</a>
          </div>
        </div>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider">
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#report" aria-expanded="true" aria-controls="user">
          <i class="fa fa-book"></i>
          <span>التقارير</span>
        </a>
        <div id="report" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-primary py-2 collapse-inner rounded">
            <a class="collapse-item" href="{{route('report.purchases.today')}}">فواتير المشتريات اليومية</a>
            <a class="collapse-item" href="{{route('report.purchases.cash')}}">فواتير المشتريات المستحقة</a>
            <a class="collapse-item" href="{{route('report.purchases.uncash')}}">فواتير المشتريات غير المستحقة</a>
            <a class="collapse-item" href="{{route('report.sales.today')}}">فواتير المبيعات اليومية</a>
            <a class="collapse-item" href="{{route('report.sales.cash')}}">فواتير المبيعات المستحقة</a>
            <a class="collapse-item" href="{{route('report.sales.uncash')}}">فواتير المبيعات غير المستحقة</a>
            <a class="collapse-item" href="{{route('report.clients.money')}}">كشف حساب بالديون لك</a>
            <a class="collapse-item" href="{{route('report.suppliers.money')}}">كشف حساب بالديون عليك</a> 
            <a class="collapse-item" href="{{route('report.clients.trans')}}">كشف حساب عميل</a> 
            <a class="collapse-item" href="{{route('report.suppliers.trans')}}">كشف حساب مورد</a> 
            <a class="collapse-item" href="{{route('report.money.save')}}">كشف حساب الخزينة</a> 
            <a class="collapse-item" href="{{route('report.money.bank')}}">كشف حساب البنك </a> 
            <a class="collapse-item" href="{{route('report.wared')}}">كشف الايرادات  </a> 
            <a class="collapse-item" href="{{route('report.monserfe')}}">كشف المصروفات </a> 
          </div>
        </div>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider">

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#supplier" aria-expanded="true" aria-controls="user">
          <i class="fa fa-user"></i>
          <span>الموردين</span>
        </a>
        <div id="supplier" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-primary py-2 collapse-inner rounded">
            <h6 class="collapse-header">التحكم في الموردين:</h6>
            <a class="collapse-item" href="{{route('supplier.index')}}">عرض الموردين</a>
            <a class="collapse-item" href="{{route('supplier.create')}}">اضافة مورد</a>
          </div>
        </div>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider">

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#client" aria-expanded="true" aria-controls="user">
          <i class="fa fa-user-circle"></i>
          <span>العملاء</span>
        </a>
        <div id="client" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-primary py-2 collapse-inner rounded">
            <h6 class="collapse-header">التحكم في العملاء:</h6>
            <a class="collapse-item" href="{{route('client.index')}}">عرض العملاء</a>
            <a class="collapse-item" href="{{route('client.create')}}">اضافة عميل</a>
          </div>
        </div>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider">

      <li class="nav-item">
        <a class="nav-link" href="{{route('wared.index')}}">
          <i class="fa fa-heart"></i>
          <span>الايرادات</span>
        </a>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider">

      <li class="nav-item">
        <a class="nav-link" href="{{route('monserf.index')}}">
          <i class="fas fa-heartbeat"></i>
          <span>المصروفات</span>
        </a>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider">


      <!-- Nav Item - Tables -->
      <li class="nav-item">
        <a class="nav-link" href="{{route('setting')}}">
          <i class="fas fa-fw fa-wrench"></i>
          <span>الاعدادات</span></a>
      </li>
      <!-- Divider -->
      {{-- <hr class="sidebar-divider">


      <!-- Nav Item - Tables -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#client" aria-expanded="true" aria-controls="user">
          <i class="fa fa-fw fa-users"></i>
          <span></span>
        </a>
        <div id="client" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-primary py-2 collapse-inner rounded">
            <h6 class="collapse-header">التحكم في المستت:</h6>
            <a class="collapse-item" href="{{route('user.index')}}">عرض العملاء</a>
            <a class="collapse-item" href="{{route('user.create')}}">اضافة عميل</a>
          </div>
        </div>
      </li> --}}

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle" data-url="{{ route('slide') }}"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" data-url="{{ route('slide') }}">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav mr-auto flex-row-reverse ">
            <!-- Nav Item - Alerts -->
            {{-- <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter">{{ count(alert_item()[0]) }}</span>
              </a>
              <!-- Dropdown - Alerts -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h3 class="dropdown-header">
                  الاشعارات 
                </h3>
                @if (alert_item()[0] > 0)
                    
                @for ($i = 0; $i < count(alert_item()[0]) ; $i++)
                <a class="dropdown-item d-flex align-items-center flex-row" href="{{route('stock.decrase')}}">
                  <div class="ml-3">
                    <div class="icon-circle bg-warning">
                      <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">{{alert_item()[0][$i]}}</div>
                    <span class="font-weight-bold">هنالك نقص في المنتج {{alert_item()[1][$i]}}</span>
                  </div>
                </a>
                @endfor
                @endif
                
              </div>
            </li> --}}

            {{-- <div class="topbar-divider d-none d-sm-block"></div> --}}

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="img-profile rounded-circle" src="{{asset('assets/img/anime3.png') }}">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->f_name ?? ''}} {{ auth()->user()->l_name ?? ''}}</span>
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('items.qty') }}">
                  <i class="fas fa-user fa-sm fa-fw ml-2 text-gray-400"></i>
                  بضاعة اول المدة
                </a>
                <a class="dropdown-item" href="{{route('setting')}}">
                  <i class="fas fa-cogs fa-sm fa-fw ml-2 text-gray-400"></i>
                  الاعدادات
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw ml-2 text-gray-400"></i>
                  تسجيل خروج
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800">@yield('title_nav')</h1>
          @yield('content')

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span> جميع الحقوق محفوظة <a href="#" class="about_me" data-toggle="modal" data-target=".programmer">لدينـا</a> &copy; {{date('Y')}}</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

      <div class="modal fade programmer" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">نبذة عن المبرمج</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <label for="price">الاسم</label>
                            <input type="text" class="form-control" value="مهند عبد الوهاب البدوي" disabled readonly>
                        </div>
                        <div class="col-6">
                            <label for="price">السكن </label>
                            <input type="text" class="form-control" value="الخرطوم" disabled readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="price">رقم التلفون الاول</label>
                            <input type="text" class="form-control" value="0912101595" disabled readonly>
                        </div>
                        <div class="col-6">
                            <label for="price">رقم التلفون التاني </label>
                            <input type="text" class="form-control" value="0121496141" disabled readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">مستعد للمغادرة</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">هل تريد فعلا الخروج</div>
        <div class="modal-footer">
          <a class="btn btn-primary" href="{{ route('logout') }}"onclick="event.preventDefault();document.getElementById('logout-form').submit();">خروج</a>
          <button class="btn btn-secondary" type="button" data-dismiss="modal">الغاء</button>
        </div>
      </div>
    </div>
  </div>

  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      @csrf
  </form>
  {!! Html::script('assets/vendor/jquery/jquery.min.js') !!}
  {!! Html::script('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') !!}
  {!! Html::script('assets/vendor/jquery-easing/jquery.easing.min.js') !!}
  {!! Html::script('assets/js/jquery.dataTables.min.js') !!}
  {!! Html::script('assets/js/dataTables.bootstrap4.min.js') !!}
  {!! Html::script('assets/js/sb-admin-2.min.js') !!}

  <script>
    $('body').on('click','#sidebarToggle',function(){
      var url = $(this).data('url');
      $.ajax({
            method : 'GET',
            url : url,
        });
    });

    $('body').on('click','#sidebarToggleTop',function(){
      var url = $(this).data('url');
      $.ajax({
            method : 'GET',
            url : url,
        });
    });
  </script>
  @yield('js')

</body>

</html>

