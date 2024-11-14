<!DOCTYPE html>
<html>
    <head>
        <title>Iseweb</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <!-- Styles -->
        <style type="text/css">
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }
            .content { text-align: center; }
            .title { font-size: 84px; }

            .table-lice {
                width: 100%;
                border: 1px solid #afafaf;
                border-collapse: collapse;
            }
            .table-lice th, .table-lice td {
                text-align: left;
                border: 1px solid #afafaf;
                border-collapse: collapse;
                padding-top: 5px;
                padding-bottom: 5px;
            }
            .table-lice thead th {
                background-color: #60c2d8;
                color: #FFF;
                padding: 10px;
                font-size: 16px;
            }

            .table-lice tbody td {
                font-size: 14px;
            }

            h4, h5, h2, h3 {
                color: #636b6f;
            }

            button.btn-action, a.btn-action {
                margin-top: 1.5rem;
                text-decoration: none;
                padding: 1rem;
                border-radius: 8px;
                background-color: #60c2d8;
                color: #636b6f;
                font-size: 1.4rem;
                border: solid 1.5px #afafaf;
                margin: .5rem;
                font-weight: 600;
            }

            div.w-100{
                width: 100%;
            }

            div.div-header {
                width: 90%; 
                padding-left: 5%;
                padding-right: 5%;
                padding-top: 20px;
                padding-bottom: 10px;
                background-color: #60c2d8;
                border-bottom: solid 2px #636b6f;
                text-align: center;
            }

            .titulo {
                font-size: 18px; 
                text-align: center;
                list-style: none;
            }

            .lista li{
                list-style: none;
                padding: 10px;
                font-size: 18px;
                color: #636b6f;
            }

            p.p-body{
                font-size: 16px;
                color: #636b6f;
            }

            table.table-detalle-dual tbody tr td{
                padding: 6px;
                border: 1.5px solid #afafaf;
                border-collapse: collapse;
            }

            table.table-detalle-dual tbody tr td:first {
                color: #60c2d8;
            }

            table.table-detalle-dual thead tr th {
                background-color: #60c2d8;
                color: #FFF;
            }

            .w-100 {
                width: 100%;
            }

        </style>
    </head>
    <body>
        <br />
        <div class="container box" style="width: 100%">
            @yield('content')
        </div>
        
        @yield('confidencialidad')
    </body>
</html>