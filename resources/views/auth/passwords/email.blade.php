@extends('layout')
@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h2 class="panel panel-default">
                <h2 class="form-signin-heading">Восстановление пароля</h2>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control" name="email"
                                       value="{{ old('email') }}" required
                                       placeholder="E-mail" required="" autofocus="">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Восстановить
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</div>
@endsection
