@extends('layout')
@section('content')
    <?php $pageTitle = 'О системе'; ?>
    <h1>Дневник питания FoodBalance</h1>
    Если у вас возникли какие-либо вопросы пишите на почту:
    <a href="mailto:support@foodbalance.pro">support@foodbalance.pro</a><br/><br/>
    <div style="margin: 0 auto; width: 150px; color: #aaa; padding: 10px;" class="copyright rounded bg-dark">
        <div style="width: 130px;"  class="copyright">
            <img src="{{asset('img/full_logo.png')}}" />
        </div>
    </div>
    <div style="margin: 0 auto; width: 150px;" class="copyright">
    © {{date('Y')}} FoodBalance
    </div>
@endsection