<?php

namespace App\Admin\Controllers;

use App\Admin\Render\UserShowOnMessage;
use App\Models\PushDeerMessage;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PushDeerMessageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'pushdeer.title.message';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        $grid = new Grid(new PushDeerMessage());

        $grid->column('id', __('pushdeer.message.id'));
        $grid->column('user.name', __('pushdeer.user.name'))
            ->modal(__('pushdeer.title.user'), UserShowOnMessage::class)
            ->filter('like');
        $grid->column('text', __('pushdeer.message.text'))->limit(15)->width(200);
        $grid->column('desp', __('pushdeer.message.desp'))->limit(15)->width(200);
        $grid->column('type', __('pushdeer.message.type'));
        $grid->column('readkey', __('pushdeer.message.readkey'))->hide();
        $grid->column('url', __('pushdeer.message.url'))->limit(15)->width(200)->link();
        $grid->column('pushkey_name', __('pushdeer.message.pushkey_name'))->limit(15)->width(200);
        $grid->column('created_at', __('pushdeer.message.created_at'));
        $grid->column('updated_at', __('pushdeer.message.updated_at'))->hide();

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
        $show = new Show(PushDeerMessage::findOrFail($id));

        $show->field('id', __('pushdeer.message.id'));
        $show->field('user.name', __('pushdeer.user.name'));
        $show->field('text', __('pushdeer.message.text'));
        $show->field('desp', __('pushdeer.message.desp'));
        $show->field('type', __('pushdeer.message.type'));
        $show->field('readkey', __('pushdeer.message.readkey'));
        $show->field('url', __('pushdeer.message.url'))->link();
        $show->field('pushkey_name', __('pushdeer.message.pushkey_name'));
        $show->field('created_at', __('pushdeer.message.created_at'));
        $show->field('updated_at', __('pushdeer.message.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(): Form
    {
        $form = new Form(new PushDeerMessage());

        $form->text('user.name', __('pushdeer.user.name'))->disable();
        $form->textarea('text', __('pushdeer.message.text'));
        $form->textarea('desp', __('pushdeer.message.desp'));
        $form->text('type', __('pushdeer.message.type'))->default('markdown');
        $form->text('readkey', __('pushdeer.message.readkey'));
        $form->url('url', __('pushdeer.message.url'));
        $form->text('pushkey_name', __('pushdeer.message.pushkey_name'));

        return $form;
    }
}
