<?php

namespace App\Admin\Controllers;

use App\Models\PrizeLog;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Admin\Extensions\Tools\PrizeGivePost;
use Illuminate\Http\Request;

class PrizeLogController extends Controller
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
        return Admin::grid(PrizeLog::class, function (Grid $grid) {

            $states = [
                'on'  => ['value' => 1, 'text' => '已兑换', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '未兑换', 'color' => 'danger'],
            ];
            $grid->TurntableUserId('用户编号');
            $grid->turntableUser()->NickName('微信昵称');
            $grid->turntableUser()->UId('用户游戏UID');
            $grid->PrizeName('奖品名称');
            $grid->CreateTime('中奖时间')->sortable();
            $grid->PrizeCode('兑换码');
            $grid->ExpiresTime('过期时间');
            $grid->GiveTime('兑换时间')->sortable();
            $grid->IsGive('是否已兑换')->switch($states);
            $grid->IPAddress('IP地址');
            $grid->IPAddressName('地区');
            $grid->disableActions();
            $grid->disableCreation();
            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->add('标记为未兑换', new PrizeGivePost(0));
                    $batch->add('标记为已兑换', new PrizeGivePost(1));
                });
            });
            $grid->filter(function($filter){

                // 去掉默认的id过滤器
                $filter->disableIdFilter();
                $filter->equal('TurntableUserId', '用户编号');
                $filter->like('PrizeName', '奖品名称');
                // 设置datetime类型
                $filter->between('CreateTime', '中奖时间')->datetime();
                $filter->equal('PrizeCode', '兑换码');
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(PrizeLog::class, function (Form $form) {
            $states = [
                'on'  => ['value' => 1, 'text' => '已兑换', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '未兑换', 'color' => 'danger'],
            ];
            $form->switch('IsGive','')->states($states);
        });
    }

    public function prizegive(Request $request)
    {
        foreach (PrizeLog::find($request->get('ids')) as $prizeLog) {
            $prizeLog->IsGive = $request->get('action');
            $prizeLog->save();
        }
    }
}
