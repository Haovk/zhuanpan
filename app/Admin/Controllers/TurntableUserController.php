<?php

namespace App\Admin\Controllers;

use App\Models\TurntableUser;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class TurntableUserController extends Controller
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
        return Admin::grid(TurntableUser::class, function (Grid $grid) {

            $grid->Id('用户编号');
            $grid->Turntable_Id('转盘编号');
            $grid->turntable()->Name('转盘名称');
            $grid->OpenId('OpenId');
            $grid->NickName('微信昵称');
            $grid->UId('用户游戏UID');
            $grid->CreateTime('中奖时间')->sortable();
            $grid->PrizeNumber('剩余抽奖次数');
            $grid->PrizeNumberSum('历史抽奖次数');
            $grid->ShareNumber('剩余分享次数');
            $grid->ShareNumberSum('历史分享次数');
            $grid->disableActions();
            $grid->disableCreation();
            $grid->tools->disableBatchActions();
            $grid->filter(function($filter){

                // 去掉默认的id过滤器
                $filter->disableIdFilter();
                $filter->equal('Id', '用户编号');
                $filter->equal('Turntable_Id', '转盘编号');
                $filter->like('NickName', '微信昵称');
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
        return Admin::form(TurntableUser::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
