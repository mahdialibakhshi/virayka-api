<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="robots" content="noindex,nofollow"/>
  <title> @yield('title')</title>
  <link rel="icon" type="image/png" href="{{ asset('home/images/icons/favicon.png') }}">
  <!-- Custom styles for this template-->
  <link href="{{ asset('admin/css/admin.css') }}" rel="stylesheet">
  <link href="{{ asset('admin/css/leaflet.css') }}" rel="stylesheet">
  @yield('style')
</head>
<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    @include('admin.sections.sidebar')
    <!-- End of Sidebar -->
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Topbar -->
        @include('admin.sections.topbar')
        <!-- End of Topbar -->
        <!-- Begin Page Content -->
        <div class="container-fluid">
          @yield('content')
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->
      <!-- Footer -->
      @include('admin.sections.footer')
      <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->
  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  @include('admin.sections.scroll_top')
  <!-- JavaScript-->
  {{--    //ckEditor--}}
  <script src="{{ asset('admin/fullCKEditor/ckeditor/ckeditor.js') }}"></script>
  <script src="{{ asset('admin/js/admin.js') }}"></script>
  <script src="{{ asset('admin/js/leaflet.js') }}"></script>
  <script>
      //remove style
      CKEDITOR.on('instanceReady', function (ev) {
          ev.editor.on('paste', function (evt) {
              if (evt.data.type == 'html') {
                  evt.data.dataValue = evt.data.dataValue.replace(/ style=".*?"/g, '');
              }
          }, null, null, 9);
      });
  </script>

@include('sweet::alert')

@yield('script')

</body>

</html>
