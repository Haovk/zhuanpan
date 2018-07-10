<?php

namespace App\Admin\Controllers;

use App\Models\Turntable;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Table;
use Log;

class TurntableController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Turntable::class, function (Grid $grid) {
            $states = [
                'on'  => ['value' => 1, 'text' => '启用', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '停用', 'color' => 'danger'],
            ];
            $states2 = [
                'on'  => ['value' => 1, 'text' => '固定次数', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '周期次数', 'color' => 'danger'],
            ];
            $grid->Id('ID')->sortable();
            $grid->Name('转盘名称');
            $grid->column('奖品列表')->expand(function () {
                //$profile = array_pluck($this->prizes->toArray(), ['PrizeName', 'PrizeUserNumber']);
                //Log::info(json_encode($array));
                return new Table(['编号','奖品名称','奖品概率','已中奖人数','中奖人数上限'],
                $this->prizes->makeHidden(['Turntable_Id','ImageUrlPath','ShowImageUrlPath','IsLimitPrizeUserNumber','IsExChange','ExpiresDay'])->toArray());

            }, '奖品列表');
            $grid->LotteryType('抽奖方式')->switch($states2);
            $grid->IsShowPrizeName('是否显示中奖名单')->switch($states);
            $grid->IsShare('是否允许分享')->switch($states);
            $grid->转盘地址('转盘地址')->urlWrapper(function(){
                return rtrim(config('app.url'),'/').'/nineturntable?id='.$this->Id;
            });
            $grid->StartTime('开始时间');
            $grid->EndTime('结束时间');
            $grid->CreateTime('创建时间');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Turntable::class, function (Form $form) {
            
            $form->tab('转盘说明', function ($form) {
                $form->text('Name', '转盘名称')->rules('required');
                $form->datetimeRange('StartTime', 'EndTime', '活动时间范围')->rules('required');
                $form->image('BackImagePath', '背景图片')->help('要求750*1200的PNG图片');
                $form->color('BackColor', '背景色')->default('#ccc')->help('转盘页面最底部背景色');
                $form->editor('FullInfo', '活动介绍');
                $form->editor('RuleInfo', '规则说明');
                $form->editor('PrizeInfo', '兑奖说明');
            })->tab('转盘设置',function($form){
                $states2 = [
                    'on'  => ['value' => 1, 'text' => '固定次数', 'color' => 'success'],
                    'off' => ['value' => 0, 'text' => '周期次数', 'color' => 'danger'],
                ];
                $states = [
                    'on'  => ['value' => 1, 'text' => '启用', 'color' => 'success'],
                    'off' => ['value' => 0, 'text' => '停用', 'color' => 'danger'],
                ];
                $form->switch('LotteryType', '抽奖类型')->states($states2)->help('固定次数:每个账号抽奖次数 周期次数:账号每天抽奖次数');
                $form->number('Number', '抽奖次数')->help('固定次数:每个账号抽奖次数 周期次数:账号每天抽奖次数');
                $form->switch('IsShowPrizeName', '是否显示中奖名单')->states($states)->help('是否显示用户的中奖滚动信息');
                $form->switch('IsPlacePrizeNumber', '是否限制中奖次数')->states($states)->help('是否限制每个用户在当前转盘的总中奖次数');  
                $form->number('PrizeNumber', '中奖次数')->help('每个用户在当前转盘的永久总中奖次数');      
                $form->switch('IsPlaceUserNumber', '是否限制参与人数')->states($states)->help('是否限制人数参与当前转盘');         
                $form->number('UserNumber', '参与人数')->help('当前转盘可以参加的人数'); 
                $form->file('BackMusicPath', '背景音乐')->help('抽奖页面背景音乐要求MP3格式');
            })->tab('分享设置',function($form){
                $states = [
                    'on'  => ['value' => 1, 'text' => '启用', 'color' => 'success'],
                    'off' => ['value' => 0, 'text' => '停用', 'color' => 'danger'],
                ];
                $form->switch('IsShare', '是否允许分享')->states($states);
                $form->text('ShareTitle', '分享标题');
                $form->text('ShareContent', '分享说明');
                $form->number('ShareNumber', '分享后可获得转盘次数')->default(1)->help('每次分享可获得新的转盘次数'); 
                $form->number('UserShareNumber', '用户特殊分享次数')->default(1)->help('每日可额外获得转盘次数的分享次数'); 
                $form->image('ShareImagePath', '分享图标')->help('要求100*100的PNG图片');
            })->tab('奖品设置', function ($form) {
                $form->hasMany('prizes', '保证奖品个数为8个(空也是奖品),中奖概率合为100%', function (Form\NestedForm $form) {                    
                    $states = [
                        'on'  => ['value' => 1, 'text' => '启用', 'color' => 'success'],
                        'off' => ['value' => 0, 'text' => '停用', 'color' => 'danger'],
                    ];
                    $form->text('PrizeName', '奖品名称');
                    $form->rate('PrizeRate', '中奖率');
                    $form->switch('IsExChange', '是否可以兑奖')->states($states)->help('目前不可以兑奖代表谢谢惠顾'); 
                    $form->number('ExpiresDay','过期天数')->default(1);
                    $form->switch('IsLimitPrizeUserNumber', '是否限制中奖人数')->states($states);
                    $form->number('PrizeUserNumber','已中奖人数');
                    $form->number('PrizeUserNumberLimit','中奖人数上限');
                    $form->image('ImageUrlPath', '奖品图片')->help('要求191*140的PNG图片');
                    $form->image('ShowImageUrlPath', '中奖奖品图片')->help('要求490*290的PNG图片');
                });
            });
            
        });
    }
}
