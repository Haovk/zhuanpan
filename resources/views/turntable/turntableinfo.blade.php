<style>
.turntableinfotop{
    background-image: url("turntable/image/bg3.png");background-size: 100%;
}
</style>
    <div class="">
        <div class="panel-heading text-center  turntableinfotop">主办方介绍</div>
        <div class="panel-body">
            {!! $turntable->FullInfo !!}
        </div>
    </div>
    <div class="">
        <div class="panel-heading text-center  turntableinfotop">活动规则</div>
        <div class="panel-body">
            {!! $turntable->RuleInfo !!}
        </div>
    </div>
    <div class="">
        <div class="panel-heading text-center  turntableinfotop">兑换说明</div>
        <div class="panel-body">
            {!! $turntable->PrizeInfo !!}
        </div>
    </div>

