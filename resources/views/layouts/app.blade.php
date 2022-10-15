<!DOCTYPE html>
<html lang="en-US">
    <head>
        <!-- Meta setup -->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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

        <!-- Main StyleSheet -->
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
        <link href="{{ asset('css/dcalendar.picker.css') }}" rel="stylesheet">       
        
        <!-- Responsive CSS -->
        <link href="{{ asset('css/responsive.css') }}" rel="stylesheet"> 
        
    </head>
    <body>
        <!--[if lte IE 9]> <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p><![endif]-->        
        
        @yield('content')
        
        <!-- Main jQuery -->
        <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
        
        <!-- Bootstrap Propper jQuery -->
        <script src="{{ asset('js/popper.js') }}"></script>
        
        <!-- Bootstrap jQuery -->
        <script src="{{ asset('js/bootstrap.js') }}"></script>
        
        <!-- Custom jQuery -->
        <script src=""></script>
        {{-- <script src="{{ asset('js/scripts.js') }}"></script> --}}
        
    </body>
</html>
