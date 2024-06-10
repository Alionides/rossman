<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\About;

class AboutController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'About';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new About());

        $grid->column('id', __('Id'));
        $grid->column('statistic', __('Statistic'));
        $grid->column('slider', __('Slider'));
        $grid->column('vision', __('Vision'));
        $grid->column('active', __('Active'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(About::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('statistic', __('Statistic'));
        $show->field('slider', __('Slider'));
        $show->field('vision', __('Vision'));
        $show->field('active', __('Active'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new About());

//        $form->text('statistic', __('Statistic'));
//        $form->table('statistic', function ($table) {
//            $table->text('statistic_count');
//            $table->text('statistic_title_az');
//            $table->text('statistic_title_en');
//            $table->text('statistic_title_ru');
//            $table->text('statistic_icon');
//        });

        $form->embeds('statistic', function ($form) {

            $form->text('key1')->rules('required');
            $form->text('key2')->rules('required');
        });

        $form->text('slider', __('Slider'));
        $form->text('vision', __('Vision'));
        $form->switch('active', __('Active'))->default(1);

        return $form;
    }
}
