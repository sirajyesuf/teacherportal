<!DOCTYPE html>
<html lang="en-US">
    <head>
        <!-- Meta setup -->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="keywords" content="">
        <meta name="decription" content="">
        <meta name="designer" content="">
        
        <!-- Title -->
        <title>@yield('title') - {{env('APP_NAME') }} </title>
        
        <!-- Fav Icon -->
        <link rel="icon" href="{{ asset('images/favicon.ico') }}" />   

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <!-- Include Bootstrap -->
        <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
        <link href="{{ asset('css/vendor/jquery-ui.min.css') }}" rel="stylesheet">       
        <link href="{{ asset('css/vendor/jquery.toast.css') }}" rel="stylesheet">

        <!-- Main StyleSheet -->         
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
        <link href="{{ asset('css/dcalendar.picker.css') }}" rel="stylesheet">
        <!-- DateTime Picker CSS -->
        <link href="{{ asset('css/vendor/bootstrap-datetimepicker.css') }}" id="theme" rel="stylesheet">       
        
        <!-- Responsive CSS -->
        <link href="{{ asset('css/responsive.css') }}" rel="stylesheet"> 
        
        <!-- Page CSS -->
        @yield('css')

        <script>

        </script>
        
    </head>
    <body>
        <!--[if lte IE 9]> <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p><![endif]-->        
        
        @yield('content')
        
        <!-- Main jQuery -->
        <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
        <script src="{{ asset('js/vendor/jquery-ui.min.js') }}"></script>        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
        <script src="//cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
        
        <!-- Bootstrap Propper jQuery -->
        <script src="{{ asset('js/popper.js') }}"></script>
        
        <!-- Bootstrap jQuery -->
        <script src="{{ asset('js/bootstrap.js') }}"></script>
        <script src="{{ asset('js/vendor/jquery.toast.js') }}"></script>
        
        <!-- Custom jQuery -->
        
        {{-- <script src="{{ asset('js/scripts.js') }}"></script> --}}
        <script type="text/javascript">
            function showMessage(type = "info", message = "") {
                $.toast({
                    heading: message,
                    position: {
                        right: 15,
                        top: 15
                    },
                    loaderBg: '#ff6849',
                    icon: type,
                    hideAfter: 3500,
                    stack: 6
                })
            }

            var _token = "{{ csrf_token() }}";            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            });
            
        </script>        
        @yield('scripts')
        @yield('pagejs')
    </body>
</html>
