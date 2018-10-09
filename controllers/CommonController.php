<?php
namespace controllers;

use models\Common;

class CommonController extends BaseController{
    // 列表页
    public function index()
    {
        $model = new Common;
        $data = $model->findAll();
        view('common/index', $data);
    }

    // 显示添加的表单
    public function create()
    {
        view('common/create');
    }

    // 处理添加表单
    public function insert()
    {
        $model = new Common;
        $model->fill($_POST);
        $model->insert();
        redirect('/common/index');
    }

    // 显示修改的表单
    public function edit()
    {
        $model = new Common;
        $data=$model->findOne($_GET['id']);
        view('common/edit', [
            'data' => $data,    
        ]);
    }

    // 修改表单的方法
    public function update()
    {
        $model = new Common;
        $model->fill($_POST);
        $model->update($_GET['id']);
        redirect('/common/index');
    }

    // 删除
    public function delete()
    {
        $model = new Common;
        $model->delete($_GET['id']);
        redirect('/common/index');
    }
}