<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FoodBalance</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
            integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
            crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" href="{{asset('img/favicon.ico')}}"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css"
          integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"
            integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1"
            crossorigin="anonymous"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link rel="stylesheet" href="/css/jqtree.css">
    <link rel="stylesheet" href="/css/stl.css">
    <link rel="stylesheet" href="/css/test.css">
    <link rel="stylesheet" href="/css/bootstrap-datepicker.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="/js/Modal.js"></script>
    <script src="/js/tree.jquery.js"></script>
    <script src="/js/bootstrap-datepicker.min.js"></script>
    <script src="/js/bootstrap-datepicker.ru.min.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/dish.js"></script>
    <script src="/js/product.js"></script>
    <script src="/js/foodDiary.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
</head>
<body>
<div class="smart">
    <div class="container-fluid header rounded border-bottom bg-dark">
        <a href="{{ route('index') }}" style="padding: 0 !important;">
            <img class="logo" title="FoodBalance" src="/img/logo.png">
        </a>
        <h3 class="title">{{$pageTitle}}</h3>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top" style="left: unset !important;">
            @if (Route::has('login'))
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('foodDiaryList') }}">Дневник питания</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Еда
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="{{ route('products') }}">Продукты</a>
                            <a class="dropdown-item" href="{{ route('dishes') }}">Блюда</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('performance_list') }}">Физические показатели</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('statistic') }}">Статистика</a>
                    </li>
                </ul>
            @endif
        </nav>
    </div>
    <div class="container container-body">
        <div class="jumbotron">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid fixed-bottom rounded border-top footer-smart bg-dark">
        <table>
            <tr>
                <td>
                    <a href="{{ route('foodDiaryList') }}">
                        <i class="fa fa-book" aria-hidden="true"></i>
                    </a>
                </td>
                <td class="rzd"><div></div></td>
                <td>
                    <a href="{{ route('products') }}">
                        <i class="fa fa-cutlery" aria-hidden="true"></i>
                    </a>
                </td>
                <td class="rzd"><div></div></td>
                <td>
                    <a href="{{ route('statistic') }}">
                        <i class="fa fa-pie-chart" aria-hidden="true"></i>
                    </a>
                </td>
                <td class="rzd"><div></div></td>
                <td>
                    <a href="{{ route('performance_list') }}">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
        </table>
    </div>
    <div class="footer rounded bg-dark">
        <div class="container">
            <div class="info">
                <img src="{{asset('img/full_logo.png')}}"/><br/>
                © {{date('Y')}} FoodBalance | <a href="mailto:support@foodbalance.pro" style="color: inherit;">support@foodbalance.pro</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>