<!DOCTYPE html>
<html lang="ar">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>تسجيل الدخول</title>

  {!! Html::style('assets/vendor/fontawesome-free/css/all.min.css') !!}
  {!! Html::style('assets/css/sb-admin-2.min.css') !!}

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row d-flex flex-row-reverse">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">مرحبا بك !</h1>
                  </div>
                  <form method="POST" action="{{ route('login') }}" class="user">
                    @csrf
                    <div class="form-group">
                        <input type="email" class="form-control form-control-user @error('email') is-invalid @enderror" id="exampleInputEmail" name="email" value="{{ old('email') }}" aria-describedby="emailHelp" placeholder="البريـد الالكترونـــــي" required autocomplete="email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user @error('password') is-invalid @enderror" id="exampleInputPassword" name="password" placeholder="كلمـــــة المــــرور">
                      @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                      تسجيـــــل الدخــــــول
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  {!! Html::script('assets/vendor/jquery/jquery.min.js') !!}
  {!! Html::script('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') !!}
  {!! Html::script('assets/vendor/jquery-easing/jquery.easing.min.js') !!}
  {!! Html::script('assets/js/sb-admin-2.min.js') !!}

</body>

</html>

