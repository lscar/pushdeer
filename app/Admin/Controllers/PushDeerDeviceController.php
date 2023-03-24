<?php

namespace App\Admin\Controllers;

use App\Admin\Render\UserShowOnDevice;
use App\Models\PushDeerDevice;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PushDeerDeviceController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'pushdeer.title.device';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        $grid = new Grid(new PushDeerDevice());

        $grid->column('id', __('pushdeer.device.id'));
        $grid->column('user.name', __('pushdeer.user.name'))
            ->modal(__('pushdeer.title.user'), UserShowOnDevice::class)
            ->filter('like');
        $grid->column('device_id', __('pushdeer.device.device_id'))
            ->limit(15)
            ->width(200)
            ->filter();
        $grid->column('type', __('pushdeer.device.type'));
        $grid->column('is_clip', __('pushdeer.device.is_clip'))->bool();
        $grid->column('name', __('pushdeer.device.name'))
            ->filter('like');
        $grid->column('created_at', __('pushdeer.device.created_at'))
            ->sortable()
            ->filter('range', 'date');
        $grid->column('updated_at', __('pushdeer.device.updated_at'))->hide();

        $grid->filter(function (Grid\Filter $filter) {
            $filter->column(1 / 2, function (Grid\Filter $filter) {
                $filter->like('user.name', __('pushdeer.user.name'));
                $filter->like('name', __('pushdeer.device.name'));
            });
            $filter->column(1 / 2, function (Grid\Filter $filter) {
                $filter->between('created_at', __('pushdeer.device.created_at'))->date();
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
        $show = new Show(PushDeerDevice::findOrFail($id));

        $show->field('id', __('pushdeer.device.id'));
        $show->field('user.name', __('pushdeer.user.name'));
        $show->field('device_id', __('pushdeer.device.device_id'));
        $show->field('type', __('pushdeer.device.type'));
        $show->field('is_clip', __('pushdeer.device.is_clip'))->using(PushDeerDevice::getIsClipOptions());
        $show->field('name', __('pushdeer.device.name'));
        $show->field('created_at', __('pushdeer.device.created_at'));
        $show->field('updated_at', __('pushdeer.device.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(): Form
    {
        $form = new Form(new PushDeerDevice());

        $form->text('user.name', __('pushdeer.user.name'))->disable();
        $form->text('device_id', __('pushdeer.device.device_id'));
        $form->text('type', __('pushdeer.device.type'));
        $form->select('is_clip', __('pushdeer.device.is_clip'))->options(PushDeerDevice::getIsClipOptions());
        $form->text('name', __('pushdeer.device.name'));

        return $form;
    }
}
