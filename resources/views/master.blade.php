<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Rent-A-Car Hit Auto</title>
    <link href="{{URL::asset('css/reset.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/css/stilovi.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/css/bootstrap.css')}}" rel="stylesheet">


</head>

<body>


    <div id="app" v-cloak>
        <table width="100%" cellspacing="0" cellpadding="0">
            <tbody>
                <tr id="header-row">
                    <td style="padding-bottom: 5px;">
                        <table width="100%" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td>
                                        <a href="/">
                                            <img id="logo-image" src="/images/hit-auto-og-logotip.png"
                                                style="height: 60px;" />
                                        </a>
                                    </td>

                                    <td style="padding-right: 20px;font-size:1.2em">
                                        {{-- <div style="display:flex;align-items: center;justify-content: right;">
                                            <div style="padding-right: 20px;"> Logovani korisnik: @if(
                                                null !==
                                                auth()->user())
                                                {{auth()->user()->acName}} {{auth()->user()->acSurname}}
                                        @endif
    </div>
    <form class="form-inline" action="/logout" method="POST"> <input style="min-width: 150px !important;
                                                    width: 150px !important;"
            class="btn btn-warning border border-dark" type="submit" value="Odjavi se">
        @csrf
    </form>

    </div> --}}


    </td>
    </tr>
    </tbody>
    </table>
    </td>
    </tr>
    {{-- <tr id="navbar-row">
                    <td>
                        <menu-component></menu-component>
                    </td>
                </tr> --}}
    <tr id="main-row">
        <td>
            <div id="main_component">
                @yield('content')

            </div>



        </td>
    </tr>
    <tr id="footer-row">
        <td align="center">© {{ date('Y') }}. Copyright by HitAuto</td>
    </tr>
    </tbody>
    </table>



    </div>


    <style>
        [v-cloak] {
            display: none;
        }

        #main_component {
            padding: 10px;
        }

        body {
            font-family: "RenaultLife";
        }


        .blank {
            background: white
        }

        .rnt {
            background: violet
        }

        .int {
            background: yellow
        }

        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        select {
            min-width: 150px;
        }


        @font-face {
            font-family: "RenaultLife-Bold";
            src: url("{{ URL::to('/fonts/RenaultLife-Bold.ttf')    }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }




        @font-face {
            font-family: 'RenaultLife';
            src: url("{{ URL::to('/fonts/RenaultLife.ttf')    }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }
    </style>



    <script type="text/javascript" src="{{URL::asset('js/app.js')}}"></script>
</body>

</html>
