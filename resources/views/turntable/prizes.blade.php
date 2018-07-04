<ul class="play clearfix">
@foreach ($turntable->prizes as $prize)
    @if($loop->iteration == 5)
        <!--开始按钮-->
        <li id="btn"></li>
        <!--开始按钮-->
    @endif
    <li class="prize">
        <div>
            <img src="{{ Storage::disk('admin')->url($prize->ImageUrlPath) }}">
            <p>{{ $prize->PrizeName }}</p>
        </div>
    </li>
@endforeach
</ul>