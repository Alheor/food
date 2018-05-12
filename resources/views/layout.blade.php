<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FoodBalance</title>
    <link rel="icon" type="image/png" href="{{asset('img/favicon.ico')}}"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css"
          integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Styles -->
    <link rel="stylesheet" href="/css/jqtree.css">
    <link rel="stylesheet" href="/css/stl.css">
    <link rel="stylesheet" href="/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="/css/mobail.css">
    <link rel="stylesheet" href="/css/iziToast.min.css">
    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="/js/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"
            integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1"
            crossorigin="anonymous"></script>
    <script src="/js/Modal.js"></script>
    <script src="/js/tree.jquery.js"></script>
    <script src="/js/bootstrap-datepicker.min.js"></script>
    <script src="/js/bootstrap-datepicker.ru.min.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/dish.js"></script>
    <script src="/js/product.js"></script>
    <script src="/js/foodDiary.js"></script>
    <script src="/js/iziToast.min.js"></script>
    <script src="/js/Chart.min.js"></script>
</head>
<body>
<div class="progress">
    <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100">
    </div>
</div>
<div class="smart">
    <div class="container-fluid header rounded border-bottom bg-dark">
        <a href="{{ route('index') }}" style="padding: 0 !important;">
            <img class="logo" title="FoodBalance" src="/img/logo.png">
        </a>
        <h3 class="title">{{$pageTitle}}</h3>
        <nav class="navbar navbar-expand-md navbar-dark" style="right: 0; position: absolute;">
            @if (Route::has('login'))
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('foodDiaryList') }}">Мой дневник</a>
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
                        <a class="nav-link" href="{{ route('performance_list') }}">Физ. показатели</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('statistic') }}">Статистика</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('info') }}">
                            <i class="fa fa-info"></i>
                        </a>
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
                    <a href="{{ route('index') }}" title="Сводка">
                        <i class="fa fa-home" aria-hidden="true"></i>
                    </a>
                </td>
                <td class="rzd"><div></div></td>
                <td>
                    <a href="{{ route('products_and_dishes') }}" title="Продукты и блюда">
                        <i class="fa fa-cutlery" aria-hidden="true"></i>
                    </a>
                </td>
                <td class="rzd"><div></div></td>
                <td>
                    <a href="{{ route('statistic') }}" title="Моя статистика">
                        <i class="fa fa-pie-chart" aria-hidden="true"></i>
                    </a>
                </td>
                <td class="rzd"><div></div></td>
                <td>
                    <a class="nav-link" href="{{ route('info') }}" title="О системе">
                        <i class="fa fa-info"></i>
                    </a>
                </td>
            </tr>
        </table>
    </div>
    <div class="footer rounded bg-dark">
        <div class="container">
            <div class="info">
                <img src="{{asset('img/full_logo.png')}}"/><br/>
                © {{date('Y')}} FoodBalance
            </div>
        </div>
    </div>
</div>
</body>
</html>