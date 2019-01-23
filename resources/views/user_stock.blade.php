<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>
@if(session()->has('message'))
    <div class="alert alert-warning text-center">
        {{ session()->get('message') }}
    </div>
@endif
<div class="container mt-4">
    <div class="row">
        <div class="col-8 offset-2">
            <h2 class="text-center">Alınan Hisse Senetleri</h2>
            <table class="table table-dark align-middle">
                <thead>
                <tr>
                    <th scope="col">Hisse Senedi</th>
                    <th scope="col">Şirket</th>
                    <th scope="col">Adet</th>
                </tr>
                </thead>
                <tbody>
                @foreach($stocks as $stock)

                    <tr>
                        <th scope="row">{{$stock->stocks->code}}</th>
                        <td>{{$stock->stocks->company}}</td>
                        <td>{{$stock->count}}</td>

                        <td><a class="btn btn-primary" href="/stock/sell/{{$stock->id}}">Sat</a></td>

                    </tr>



                @endforeach()
                </tbody>
            </table>
            <td><a class="btn btn-primary" href="/stock/buy">Yeni Hisse Senedi Al</a></td>
        </div>
    </div>
</div>
</body>
</html>
