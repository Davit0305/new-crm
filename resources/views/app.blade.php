<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" value="{{ csrf_token() }}" />

        <title>База заказов</title>
        <link rel="icon" href="{{ asset("/icons/favicon.ico") }}" type="image/x-icon" />
        <link rel="shortcut icon" href="{{ asset("/icons/favicon.ico") }}" type="image/x-icon" />
        <link href="{{ mix('css/app.css') }}" type="text/css" rel="stylesheet" />
        <script defer src="https://cdn.socket.io/4.4.0/socket.io.js"></script>
        <!--    <script defer src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>-->
        <script src="https://api-maps.yandex.ru/2.1/?apikey=a9e8e360-29d1-47eb-96b2-f57ddfad972e&lang=ru_RU" type="text/javascript"></script>
    </head>

    <body>
        <div id="app"></div>
        <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
    </body>
</html>
