<?php

namespace App\Http\Controllers\Turntable;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat\Factory;
use App\Models\Turntable;
use App\Models\TurntableUser;
use App\Models\Prize;
use App\Models\PrizeLog;
use Log;
use AliasMethodFacade;
use GeoLookupFacade;
use Carbon\Carbon;

class NineTurntableController extends Controller
{
    public function auth(Request $request)
    {
        if (isset($request->code)) {
            $app = app('wechat.official_account');
            //回调后获取user时也要设置$request对象
            $user = $app->oauth->user();
            session()->put('wechat.oauth_user.default',$user);
            return redirect()->route('thome',['id'=>$request->id]);
        }
        //$url=trim(config('app.url'),'/').'/nineturntable'; 
        $url=urlencode($request->getUri());
        $authurl=config('wechat.unifiedauthurl');
        //Log::info(trim($authurl,'/').'/wxauth/auth?redirecturi='.$url);
        return redirect(trim($authurl,'/').'/wxauth/auth?redirecturi='.$url);
        
    }
    public function index(Request $request)
    {
        $user = session('wechat.oauth_user.default');
        if (!isset($user)&&!$user) {
            return redirect()->route('wxauth', ['id'=>$request->id]);
        }
        //$user=session('wechat.oauth_user.default');
        $turntable=Turntable::where([['Id','=',$request->id],['StartTime','<=',Carbon::now()],['EndTime','>=',Carbon::now()]])
        ->first();
        if (!$turntable) {
            return view('turntable.turntabletongzhi', ['msg'=>'转盘已过期或没有该转盘']);
        }
        $tuser=$turntable->turntableUsers->where('OpenId', $user->id)->first();
        //Log::info(json_encode($tuser));
        if (!$tuser) {
            if ($turntable->IsPlaceUserNumber==1&&$turntable->turntableUsers->count()>=$turntable->UserNumber) {
                //是否限制参与人数且参与人数已经达到设定值
                return view('turntable.turntabletongzhi', ['msg'=>'该转盘用户已达到上限']);
            }
            $newTUser=new TurntableUser([
                'OpenId'=>$user->id,
                'NickName'=>$user->nickname,
                'PrizeNumber'=>$turntable->Number,
                'ShareNumber'=>$turntable->UserShareNumber
            ]);
            $tuser=$newTUser;
            $turntable->turntableUsers()->save($newTUser);
        }
        if ($turntable->IsShare==1) {
            //Log::info(view('turntable.nineturntable', ['app'=>$app,'turntable'=>$turntable,'tuser'=>$tuser])->getContent());
            return view('turntable.nineturntable', ['app'=>$app,'turntable'=>$turntable,'tuser'=>$tuser]);
        }
        return view('turntable.nineturntable', ['turntable'=>$turntable,'tuser'=>$tuser]);
    }
    public function shareinfo(Request $request)
    {
        $user = session('wechat.oauth_user.default');
        $turntable=Turntable::find($request->id);
        $tuser=$turntable->turntableUsers->where('OpenId', $user->id)->first();
        if ($turntable->IsShare==1&&$tuser->ShareNumber>0) {
            //该转盘允许分享
            //该用户剩余分享次数
            $tuser->ShareNumber--;
            $tuser->PrizeNumber+=$turntable->ShareNumber;//分享成功该用户累加转盘设置的分享获得抽奖值
        }
        $tuser->ShareNumberSum++;//历史分享次数
        $tuser->save();
        $msgArr=['Status'=>20000,'Number'=>$tuser->PrizeNumber,'Message'=>'分享成功'];
        return json_encode($msgArr);
    }
    public function bindUser(Request $request)
    {
        $user = session('wechat.oauth_user.default');
        $msgArr=['Status'=>20001,'Message'=>'绑定错误'];
        $turntable=Turntable::find($request->id);
        $tuser=$turntable->turntableUsers->where('OpenId', $user->id)->first();
        if($tuser){
            $tuser->UId=$request->userid;
            $tuser->save();
            $msgArr=['Status'=>20000,'UID'=>$tuser->UId,'Message'=>'成功'];
        }
        return json_encode($msgArr);
    }
    public function getitem(Request $request)
    {
        $user = session('wechat.oauth_user.default');
        if (!$user) {
            return redirect()->route('wxauth', ['id'=>$request->id]);
        }
        $msgArr=['Status'=>20001,'Item'=>-1,'Message'=>'未知错误'];
        try {
            $turntable=Turntable::where('StartTime', '<=', Carbon::now())
        ->where('EndTime', '>=', Carbon::now())
        ->where('Id', $request->id)
        ->firstOrFail();//获取转盘信息
        $tuser=$turntable->turntableUsers->where('OpenId', $user->id)->first();//获取当前用户信息
        if ($tuser&&$tuser->PrizeNumber>0) {//判断当前用户是否存在抽奖次数
            //奖品编号数组
            $prizeRatesById=$turntable->prizes->map(function ($prize) {
                return $prize->Id;
            })->toArray();
            //中奖率数组
            $prizeRates=$turntable->prizes->map(function ($prize) {
                return sprintf("%01.4f", $prize->PrizeRate*0.01);
            })->toArray();
            Log::info(json_encode($prizeRates));
            Log::info(array_sum($prizeRates)==1.0);
            $item=AliasMethodFacade::next_rand($prizeRates);
            //$item=$alias->next_rand();
            $tuser->PrizeNumber--;//剩余抽奖次数
            $tuser->PrizeNumberSum++;//历史抽奖次数
            $tuser->save();
            
            //判断是否限制中奖次数且中奖次数是否达到设定值
            if ($turntable->IsPlacePrizeNumber==1&&$tuser->prizeLogs->count()>=$turntable->PrizeNumber) {
                //符合条件一律指定为未中奖
                $nprize=$turntable->prizes->where('IsExChange', 0)->first();
                if ($nprize) {
                    $item=array_search($nprize->Id, $prizeRatesById);
                }
            }
            
            $prize = $turntable->prizes->find($prizeRatesById[$item]);
            //判断单个奖品的中奖人数是否限制且是否达到设定值
            if ($prize->IsLimitPrizeUserNumber==1&&$prize->PrizeUserNumber>=$prize->PrizeUserNumberLimit) {
                //符合条件一律指定为未中奖
                $nprize=$turntable->prizes->where('IsExChange', 0)->first();
                if ($nprize) {
                    $item=array_search($nprize->Id, $prizeRatesById);
                }
                //更换奖品为谢谢惠顾
                $prize = $turntable->prizes->find($prizeRatesById[$item]);
            }
            $prize->PrizeUserNumber++;//累加奖品已中奖人数
            $prize->save();
            if ($prize->IsExChange==1) {//该奖品是否可以进行兑换（不是谢谢惠顾）
                //Log::info($prize->toJson());
                $str_time = $this->dec62($this->msectime());
                // 8位随机字符串
                $code = $this->rand_char().$str_time;
                $prizeLog = new PrizeLog;
                $prizeLog->Prize_Id=$prize->Id;
                $prizeLog->PrizeName=$prize->PrizeName;
                $prizeLog->PrizeCode=$code;
                $prizeLog->ExpiresTime=Carbon::now()->addDays($prize->ExpiresDay);
                $prizeLog->IPAddress=$request->getClientIp();
                $prizeLog->IPAddressName=GeoLookupFacade::LookupCityName($request->getClientIp());
                $tuser->prizeLogs()->save($prizeLog);
            }
            $msgArr=['Status'=>20000,'Item'=>$item,'Number'=>$tuser->PrizeNumber,'Message'=>'成功'];
        } else {
            if ($turntable->IsShare==1) {
                $msgArr=['Status'=>20002,'Item'=>-1,'Message'=>'转盘次数不足,每日分享链接可获得额外的抽奖次数'];
            } else {
                $msgArr=['Status'=>20002,'Item'=>-1,'Message'=>'转盘次数不足'];
            }
        }
            return json_encode($msgArr);
        } catch (Exception $exc) {
            Log::warning('抽奖时出错'.$exc->getMessage());
        }
    }
    public function getTickets(Request $request)
    {
        $user = session('wechat.oauth_user.default');
        $turntable=Turntable::find($request->id);//获取转盘信息
        $tuser=$turntable->turntableUsers->where('OpenId', $user->id)->first();//获取当前用户信息
        $prizeLogs=$tuser->prizeLogs->where('IsGive',0)->all();
        return json_encode($prizeLogs);
    }
    public function getAllTickets(Request $request)
    {
        $prizeLogs=PrizeLog::leftJoin('Turntable_User','Prize_Log.TurntableUserId','=','Turntable_User.Id')
        ->where('Turntable_User.Turntable_Id',$request->id)
        ->select('Prize_Log.PrizeName', 'Turntable_User.NickName')
        ->orderBy('Prize_Log.Id','desc')
        ->get(10);
        $prizeLogs=$prizeLogs->map(function ($item, $key) {
            $item->NickName=str_limit($item->NickName,2,'').'***';
            return $item;
        });
        return json_encode($prizeLogs);
    }
    // 当前的毫秒时间戳
    public static function msectime()
    {
        $arr = explode(' ', microtime());
        $tmp1 = $arr[0];
        $tmp2 = $arr[1];
        return (float)sprintf('%.0f', (floatval($tmp1) + floatval($tmp2)) * 1000);
    }
    // 10进制转62进制
    public static function dec62($dec)
    {
        $base = 62;
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $ret = '';
        for ($t = floor(log10($dec) / log10($base)); $t >= 0; $t--) {
            $a = floor($dec / pow($base, $t));
            $ret .= substr($chars, $a, 1);
            $dec -= $a * pow($base, $t);
        }
        return $ret;
    }
    // 随机字符
    public static function rand_char()
    {
        $base = 62;
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return $chars[mt_rand(1, $base) - 1];
    }
}
