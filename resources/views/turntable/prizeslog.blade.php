
<div class="">
<div class="panel-heading text-center  turntableinfotop">中奖名单</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-12">
                <ul class="demo2" style="overflow-y: hidden; height: 20px;">	
                <li style="display:none;" class="news-item text-center">恭喜 获得</li>									
                </ul>
            </div>
        </div>
    </div>
</div>
<script src="{{ admin_asset('/turntable/jquery.bootstrap.newsbox.min.js')}} "></script>
<script>
$(function(){
    $(".demo2").bootstrapNews({
            newsPerPage: 1,
            autoplay: true,
			pauseOnHover: false,
			navigation: false,
            direction: 'down',
            newsTickerInterval: 2500,
            onToDo: function () {
                //console.log(this);
            }
        });
    getAllTickets();
    setInterval(function () {
        getAllTickets();
    },25000);
    function getAllTickets(){
        $.ajax({
            url: "/nineturntable/getAllTickets",
            async: false,
            type: "GET",
            dataType: "json",
            data: { id: $('#tid').val() },
            success: function (data) {
                if (!$.isEmptyObject(data)) {
                    $('.demo2').html('');
                    $.each(data, function (i, o) {
                        var html='<li style="display:none;" class="news-item text-center">恭喜'+o.NickName+' 获得'+o.PrizeName+'</li>'
                        $('.demo2').append(html);
                    });
                }
            }
        });
    }
});
</script>