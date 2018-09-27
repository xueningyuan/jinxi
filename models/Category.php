<?php
namespace models;

class Category extends Model
{
    // 设置这个模型对应的表
    protected $table = 'category';
    // 设置允许接收的字段
    protected $fillable = ['cat_name','parent_id','path'];

    // 取出一个分类的只分类
    // 参数：上级分类的ID
    public function getCat($parent_id = 0){
        return $this->findAll([
            'where' => "parent_id=$parent_id"
        ]);
    }
}