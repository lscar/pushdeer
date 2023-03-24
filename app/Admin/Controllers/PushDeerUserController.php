<?php

namespace App\Admin\Controllers;

use App\Models\PushDeerUser;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PushDeerUserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'pushdeer.title.user';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        $grid = new Grid(new PushDeerUser());

        $grid->column('id', __('pushdeer.user.id'));
        $grid->column('name', __('pushdeer.user.name'))->filter('like');
        $grid->column('email', __('pushdeer.user.email'))->limit(15)->width(200);
        $grid->column('apple_id', __('pushdeer.user.apple_id'))->limit(5)->width(100);
        $grid->column('wechat_id', __('pushdeer.user.wechat_id'))->limit(5)->width(100);
        $grid->column('simple_token', __('pushdeer.user.simple_token'))->limit(5)->width(100);
        $grid->column('level', __('pushdeer.user.level'))->bool()->filter(PushDeerUser::getLevelOptions());
        $grid->column('created_at', __('pushdeer.user.created_at'))->sortable()->filter('range','date');
        $grid->column('updated_at', __('pushdeer.user.updated_at'))->hide();

        $grid->filter(function (Grid\Filter $filter) {
            $filter->column(1/2, function (Grid\Filter $filter) {
                $filter->like('name', __('pushdeer.user.name'));
                $filter->like('email', __('pushdeer.user.email'));
            });
            $filter->column(1/2, function (Grid\Filter $filter) {
                $filter->equal('level', __('pushdeer.user.level'))->select(PushDeerUser::getLevelOptions());
                $filter->between('created_at', __('pushdeer.user.created_at'))->date();
            });
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param int $id
     * @return Show
     */
    protected function detail(int $id): Show
    {
        $show = new Show(PushDeerUser::findOrFail($id));

        $show->field('id', __('pushdeer.user.id'));
        $show->field('name', __('pushdeer.user.name'));
        $show->field('email', __('pushdeer.user.email'));
        $show->field('apple_id', __('pushdeer.user.apple_id'));
        $show->field('wechat_id', __('pushdeer.user.wechat_id'));
        $show->field('level', __('pushdeer.user.level'))->using(PushDeerUser::getLevelOptions());
        $show->field('simple_token', __('pushdeer.user.simple_token'));
        $show->field('created_at', __('pushdeer.user.created_at'));
        $show->field('updated_at', __('pushdeer.user.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(): Form
    {
        $form = new Form(new PushDeerUser());

        $form->text('name', __('pushdeer.user.name'));
        $form->email('email', __('pushdeer.user.email'));
        $form->text('apple_id', __('pushdeer.user.apple_id'));
        $form->text('wechat_id', __('pushdeer.user.wechat_id'));
        $form->select('level', __('pushdeer.user.level'))->options(PushDeerUser::getLevelOptions());
        $form->text('simple_token', __('pushdeer.user.simple_token'));

        return $form;
    }
}
