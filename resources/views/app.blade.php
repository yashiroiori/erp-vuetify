<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
        <script src="{{ mix('/js/app.js') }}" defer></script>
        @routes
    </head>
    <body>
        @inertia
    </body>
</html>