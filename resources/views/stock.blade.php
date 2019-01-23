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
<body class="h-100">
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
<div class="container mt-4">
    <div class="row">
        <div class="col-8 offset-2">
            <h2 class="text-center">Alınması Tavsiye Edilen Hisse Senetleri</h2>
            <table class="table table-dark align-middle">
                <thead>
                <tr>
                    <th scope="col">Hisse Senedi</th>
                    <th scope="col">Şirket</th>
                    <th scope="col">Yarı Dönemki kazancı %</th>
                    <th scope="col">Fiyat</th>
                </tr>
                </thead>
                <tbody>
                @foreach($stocks as $stock)
                    <tr>
                        <th scope="row">{{$stock->code}}</th>
                        <td>{{$stock->company}}</td>
                        <td>{{$stock->rate}}</td>
                        <td>{{$stock->values->last()->value}}</td>
                        <td><a class="btn btn-primary" href="/stock/buy/{{$stock->id}}">Satın Al</a></td>

                    </tr>
                @endforeach()
                </tbody>
            </table>
            <td><a class="btn btn-primary" href="/home">Alınan hisse senetleri</a></td>
        </div>
    </div>
</div>
</body>
</html>
