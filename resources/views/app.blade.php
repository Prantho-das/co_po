<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">


    <!-- Scripts -->
    @routes
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{ asset('js/sweetalert.js') }}"></script>
    <script src="{{ asset('js/chart.min.js') }}"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    {{-- <script type="text/javascript" src="{{ asset('js/loader.js') }}"></script> --}}
    @vite('resources/js/app.js')
    @inertiaHead
    <style>
        .v-calendar .input-field.long {
            min-width: 500px !important;
        }

        .v-calendar .input-field input {
            height: 40px !important;
        }

    </style>
</head>

<body class="font-sans antialiased">
    @inertia

    @env ('local')
    <script src="http://localhost:8080/js/bundle.js"></script>
    @endenv
</body>

</html>
