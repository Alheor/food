@extends('layout')
@section('content')
    @php $pageTitle = 'Вход в систему'; @endphp
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="form-group col-md-8">
                                <input type="email" class="form-control" name="email"
                                       value="{{ old('email') }}" required autofocus
                                       placeholder="E-mail" required="" autofocus="">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="{{ $errors->has('password') ? ' has-error' : '' }}">
                            <div class="form-group col-md-8">
                                <input type="password" class="form-control" name="password"
                                       require placeholder="Пароль" required="" autofocus="">

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

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Войти
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Забыли пароль?
                                </a>

                                <a class="btn btn-link" href="{{ route('register') }}">
                                    Регистрация
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
@endsection
