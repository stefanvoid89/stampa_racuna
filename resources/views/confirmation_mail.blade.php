<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Izvestaj o slanju faktura klijentima</title>
</head>

<body>

    <div style="color:green;font-size: 1,5rem">Klijenti kojima su isporuceni mailovi</div>
    <ul></ul>
    @foreach ($successful as $s)
    <li>{{ $s->client_name }}</li>
    @endforeach


    <div style="margin-top:30px;color:red;font-size: 1,5rem">Klijenti kojima nisu isporuceni mailovi</div>
    <ul></ul>
    @foreach ($unsuccessful as $u)
    <li>{{ $u->client_name }}</li>
    @endforeach

</body>

</html>