<div id="mask" style="display: none;">
    <div class="blin"></div>
    <div class="caidai"></div>
    <div class="winning">
        <div class="red-head"></div>
        <div class="red-body"></div>
        <div id="card" class="">
            @foreach ($turntable->prizes as $prize)
                <img id="showimage{{$loop->iteration}}" style="width:100%;" src="{{ Storage::disk('admin')->url($prize->ShowImageUrlPath) }}">
            @endforeach
        </div>
        <a href="#" target="_self" class="btn"></a>
        <span id="close"></span>
    </div>
</div>