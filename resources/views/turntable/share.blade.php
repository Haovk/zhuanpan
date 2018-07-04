<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>
            // wx.config({
            //     debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            //     appId: '@Model.jsSdkUiPackage.AppId', // 必填，公众号的唯一标识
            //     timestamp: '@Model.jsSdkUiPackage.Timestamp', // 必填，生成签名的时间戳
            //     nonceStr: '@Model.jsSdkUiPackage.NonceStr', // 必填，生成签名的随机串
            //     signature: '@Model.jsSdkUiPackage.Signature',// 必填，签名
            //     jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone'] // 必填，需要使用的JS接口列表
            // });
            wx.config(<?php echo $app->jssdk->buildConfig(array('onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone'), false) ?>);
            wx.ready(function () {
                document.getElementById('bgmusic').play();
                // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
                wx.onMenuShareTimeline({
                    title: '{{ $turntable->ShareTitle }}', // 分享标题 分享到朋友圈
                    link: '{{ admin_asset("/nineturntable?id=".$turntable->Id) }}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                    imgUrl: '{{ Storage::disk("admin")->url($turntable->ShareImagePath) }}', // 分享图标
                    success: function () {
                        // 用户点击了分享后执行的回调函数
                        shareinfo();
                    }
                });
                wx.onMenuShareAppMessage({
                    title: '{{ $turntable->ShareTitle }}', // 分享标题 分享给朋友
                    desc: '{{ $turntable->ShareContent }}', // 分享描述
                    link: '{{ admin_asset("/nineturntable?id=".$turntable->Id) }}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                    imgUrl: '{{ Storage::disk("admin")->url($turntable->ShareImagePath) }}', // 分享图标
                    type: '', // 分享类型,music、video或link，不填默认为link
                    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                    success: function () {
                        // 用户点击了分享后执行的回调函数
                        shareinfo();
                    }
                });
                wx.onMenuShareQQ({
                    title: '{{ $turntable->ShareTitle }}', // 分享标题 分享到QQ
                    desc: '{{ $turntable->ShareContent }}', // 分享描述
                    link: '{{ admin_asset("/nineturntable?id=".$turntable->Id) }}', // 分享链接
                    imgUrl: '{{ Storage::disk("admin")->url($turntable->ShareImagePath) }}', // 分享图标
                    success: function () {
                        // 用户确认分享后执行的回调函数
                        shareinfo();
                    },
                    cancel: function () {
                        // 用户取消分享后执行的回调函数
                    }
                });
                wx.onMenuShareWeibo({
                    title: '{{ $turntable->ShareTitle }}', // 分享标题 分享到腾讯微博
                    desc: '{{ $turntable->ShareContent }}', // 分享描述
                    link: '{{ admin_asset("/nineturntable?id=".$turntable->Id) }}', // 分享链接
                    imgUrl: '{{ Storage::disk("admin")->url($turntable->ShareImagePath) }}', // 分享图标
                    success: function () {
                        // 用户确认分享后执行的回调函数
                        shareinfo();
                    },
                    cancel: function () {
                        // 用户取消分享后执行的回调函数
                    }
                });
                wx.onMenuShareQZone({
                    title: '{{ $turntable->ShareTitle }}', // 分享标题 分享到QQ空间
                    desc: '{{ $turntable->ShareContent }}', // 分享描述
                    link: '{{ admin_asset("/nineturntable?id=".$turntable->Id) }}', // 分享链接
                    imgUrl: '{{ Storage::disk("admin")->url($turntable->ShareImagePath) }}', // 分享图标
                    success: function () {
                        // 用户确认分享后执行的回调函数
                        shareinfo();
                    },
                    cancel: function () {
                        // 用户取消分享后执行的回调函数
                    }
                });
            });

            wx.error(function (res) {
                // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
                //alert(res.errMsg);
            });



            function shareinfo() {
                var tong = null;
                $.ajax({
                    url: "/nineturntable/shareinfo",
                    async: false,
                    type: "POST",
                    dataType: "json",
                    data: { id: $('#tid').val() },
                    success: function (data) {
                        $("#change").html(data.Number);
                        alert(data.Message);
                    }
                });
                return tong;
            }
    </script>