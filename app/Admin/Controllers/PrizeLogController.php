<?php

namespace App\Admin\Controllers;

use App\Models\PrizeLog;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

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

            $grid->TurntableUserId('用户编号');
            $grid->PrizeName('奖品名称');
            $grid->CreateTime('中奖时间')->sortable();
            $grid->PrizeCode('兑换码');
            $grid->ExpiresTime('过期时间');
            $grid->GiveTime('兑换时间')->sortable();
            $grid->IsGive('是否已兑换');
            $grid->IPAddress('IP地址');
            $grid->IPAddressName('地区');
            $grid->disableActions();
            $grid->disableCreation();
            $grid->tools->disableBatchActions();
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

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
