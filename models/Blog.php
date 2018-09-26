<?php
namespace models;

class Blog extends Model
{
    protected $table = blog;
    // 设置允许接受的字段
    protected $fillable = ['title','content','is_show'];
}
