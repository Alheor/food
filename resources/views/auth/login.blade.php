@extends('layout')
@section('content')
    <?php $pageTitle = 'Авторизация'; ?>
    <div class="row">
        <div class="col-12 col-sm-5">
            <h2>Вход в систему</h2>
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
                                <input type="email" class="form-control" name="email"
                                       value="{{ old('email') }}"
                                       placeholder="E-mail" required="" autofocus="">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="{{ $errors->has('password') ? ' has-error' : '' }}">
                                <input type="password" class="form-control" name="password"
                                       placeholder="Пароль" required="">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"
                                           name="remember" {{ old('remember') ? 'checked' : '' }}> Запомнить
                                </label>
                            </div>
                        </div>
                        <div class="row main-footer-box">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">
                                    Войти
                                </button>
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Восстановить пароль
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-1"> </div>
        <div class="col-12 col-sm-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h2>Регистрация</h2>
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <input id="name" placeholder="Имя" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input id="email" placeholder="E-Mail" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif

                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <input id="password" placeholder="Пароль" type="password" class="form-control" name="password" required>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <input id="password-confirm" placeholder="Повтор пароля" type="password" class="form-control" name="password_confirmation" required>
                        </div>
                        <div class="row main-footer-box">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    Зарегистрироваться
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
