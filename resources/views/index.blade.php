@extends('layout')
@section('content')
    <h1>Рацион питания</h1>
    <a class="btn btn-primary" href="{{ route('foodDiaryLoadDay') }}" role="button">Сегодняшний рацион</a>
    <a class="btn btn-primary" href="{{ route('foodDiaryNewDay') }}" role="button">Новый день</a>
@endsection