<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KUSUMA | @yield('title', "Dashboard")</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset("AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css") }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset("AdminLTE-3.2.0/dist/css/adminlte.min.css") }}">
  <link rel="stylesheet" href="{{asset("css/utils.css")}}">

  <style>
    .divider {
        display: flex;
        flex-direction: row;
    }
    .divider:before, .divider:after{
        content: "";
        flex: 1 1;
        border-bottom: 1px solid;
        margin: auto;
    }
    .divider:before {
        margin-right: 10px
    }
    .divider:after {
        margin-left: 10px
    }
  </style>
  @livewireStyles
  @stack('addStyle')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  @include('includes._navbar')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('includes._sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-12">
                <h1>@yield('title', "Dashboard")</h1>
              </div>
            </div>
          </div>
          <!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content pb-5">
          @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @yield('content')
        </section>
        <!-- /.content -->
      </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">

    <strong>Copyright &copy; {{ \Carbon\Carbon::now()->format("Y") }}</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset("AdminLTE-3.2.0/plugins/jquery/jquery.min.js") }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset("AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset("AdminLTE-3.2.0/dist/js/adminlte.min.js") }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@livewireScripts

@if (Session::get("success"))
    <script>
        Swal.fire({
            icon: "success",
            title: "{{ Session::get("success") }}"
        });
    </script>
@endif

@if (Session::get("error"))
    <script>
        Swal.fire({
            icon: "error",
            title: "{{ Session::get('error') }}"
        });
    </script>
@endif

<script>
    $(".confirm_delete").on("click", function(){
        var form =  $(this).closest("form");
        event.preventDefault();

        Swal.fire({
            title: "Hapus Data Ini ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });;
    })
</script>

@stack('addScript')

</body>
</html>
