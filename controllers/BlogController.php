<?php
namespace controllers;

class BlogController{
    // 列表页
    public function index()
    {
        @$blog = new \models\Blog;
        $data = $blog->findAll();
        view('blog/index',[
            'data'=>$data
        ]);
    }

    // 显示添加的表单
    public function create()
    {
        view('blog/create');
    }

    // 处理添加表单
    public function insert()
    {
        $blog = new \models\Blog;
        $blog->fill($_POST);
        $blog->insert();
        header('Location:/');
    }

    // 显示修改的表单
    public function edit()
    {
        view('blog/edit');
    }

    // 修改表单的方法
    public function update()
    {

    }

    // 删除
    public function delete()
    {

    }
}