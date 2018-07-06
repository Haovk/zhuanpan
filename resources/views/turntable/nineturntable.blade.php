<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{$turntable->Name}}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ admin_asset('/vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ admin_asset('/vendor/laravel-admin/sweetalert/dist/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ admin_asset('/vendor/laravel-admin/toastr/build/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ admin_asset('/turntable/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ admin_asset('/turntable/commonmobile.css') }}">
    <link rel="stylesheet" href="{{ admin_asset('/turntable/index.css') }}">
    <link rel="stylesheet" href="https://cdn.bootcss.com/weui/1.1.2/style/weui.min.css">
    <link rel="stylesheet" href="https://cdn.bootcss.com/jquery-weui/1.2.0/css/jquery-weui.min.css">
    <script src="{{ admin_asset('/vendor/laravel-admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js')}} "></script>    
  </head>
  <!-- 移动端适配 -->
  <script>
        var html = document.querySelector('html');
        changeRem();
        window.addEventListener('resize', changeRem);

        function changeRem() {
            var width = html.getBoundingClientRect().width;
            html.style.fontSize = width / 10 + 'px';
        }
    </script>
<body style="background: {{$turntable->BackColor}};">

<audio id="bgmusic" autoplay="autoplay" loop="loop">
    <source src="{{ Storage::disk('admin')->url($turntable->BackMusicPath) }}" type="audio/mp3">
    您的浏览器不支持 audio 标签。
</audio>
<div id="wrap" style="{{'background-image: url("'.Storage::disk("admin")->url($turntable->BackImagePath).'");'}}">
    <!--我的奖品-->
    @include('turntable.myprizes')
    <div class="stars-box">
        <span class="stars"></span>
        <span class="stars"></span>
        <span class="stars"></span>
        <span class="stars"></span>
        <span class="stars"></span>
        <span class="stars"></span>
        <span class="stars"></span>
    </div>
    
    <!--主体-->
    <div class="main">
        <!--游戏区域-->
        <div class="box">
            <span class="coin"></span>
            <h2>您今日还有 <span id="change">{{ $tuser->PrizeNumber }}</span> 次抽奖机会</h2>
            <input id="uid" type="hidden" value="{{ $tuser->UId }}"/>
            <input id="tid" type="hidden" value="{{ $turntable->Id }}"/>
            <ul class="light clearfix">
                <li class="fl">
                    <p class="blin"></p>
                    <p class=""></p>
                    <p class="blin"></p>
                    <p class=""></p>
                </li>
                <li class="fr">
                    <p class=""></p>
                    <p class="blin"></p>
                    <p class=""></p>
                    <p class="blin"></p>
                </li>
            </ul>
            <!--九宫格-->
            @include('turntable.prizes')
        </div>
		<div class="">
		</div>
    </div>
    <!--中奖提示-->
    @include('turntable.prizetips')
</div>
<div style="margin-top: -2.0rem;position: relative;">
    <!--转盘过期时间-->
    @include('turntable.turntableouttime')
    <!--所有玩家中奖记录-->
    @if($turntable->IsShowPrizeName==1)
        @include('turntable.prizeslog')
    @endif
    <!--转盘相关说明-->
    @include('turntable.turntableinfo')
</div>
    <!--分享模块-->
    @if($turntable->IsShare==1)
        @include('turntable.share')
    @endif
    <!-- Bootstrap 3.3.5 -->    
    <script src="{{ admin_asset('/turntable/swiper.jquery.min.js')}}"></script>
    <script src="{{ admin_asset('/vendor/laravel-admin/AdminLTE/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{ admin_asset('/vendor/laravel-admin/sweetalert/dist/sweetalert.min.js')}}"></script>
    <script src="{{ admin_asset('/vendor/laravel-admin/toastr/build/toastr.min.js')}}"></script>
    <script src="{{ admin_asset('/turntable/h5_game_common.js')}}"></script>
    <script src="{{ admin_asset('/turntable/index.js')}}"></script>
    
   </body>
</html>
{{Log::info('页面结束')}}