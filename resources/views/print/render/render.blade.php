<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>{{$title}}</title>
    <link href="{{URL::asset('/css/paper.css')}}" rel="stylesheet">

    <style>
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

        body {
            font-family: "RenaultLife";
            font-size: 8pt;
        }

        div {
            font-size: 8pt;
        }

        .border_bottom {
            /* border-top: 1px solid black; */
            padding-top: 10px;
        }
    </style>
</head>


<body class="A4">
    <div id="app">
        <invoice-component :prop_data="{{$prop_data}}" title="{{$title}}"></invoice-component>
    </div>

    <script type="text/javascript" src="{{URL::asset('js/print/print.js')}}"></script>


    <style>


    </style>


</body>



</html>