<?php

namespace App\Admin\Controllers;

use App\Admin\Render\UserShowOnKey;
use App\Models\PushDeerKey;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PushDeerKeyController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'pushdeer.title.key';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        $grid = new Grid(new PushDeerKey());

        $grid->column('id', __('pushdeer.key.id'));
        $grid->column('user.name', __('pushdeer.user.name'))
            ->modal(__('pushdeer.title.user'), UserShowOnKey::class)
            ->filter('like');
        $grid->column('name', __('pushdeer.key.name'))
            ->filter('like');
        $grid->column('key', __('pushdeer.key.key'))
            ->limit(15)
            ->width(200)
            ->filter();
        $grid->column('created_at', __('pushdeer.key.created_at'))
            ->sortable()
            ->filter('range', 'date');
        $grid->column('updated_at', __('pushdeer.key.updated_at'))->hide();

        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();

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
        $show = new Show(PushDeerKey::findOrFail($id));

        $show->field('id', __('pushdeer.key.id'));
        $show->field('name', __('pushdeer.key.name'));
        $show->field('user.name', __('pushdeer.user.name'));
        $show->field('key', __('pushdeer.key.key'));
        $show->field('created_at', __('pushdeer.key.created_at'));
        $show->field('updated_at', __('pushdeer.key.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(): Form
    {
        $form = new Form(new PushDeerKey());

        $form->text('user.name', __('pushdeer.user.name'))->disable();
        $form->text('name', __('pushdeer.key.name'));
        $form->text('key', __('pushdeer.key.key'));

        return $form;
    }
}
