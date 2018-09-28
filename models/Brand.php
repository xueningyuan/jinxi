<?php
namespace models;

class Brand extends Model
{
    // 设置这个模型对应的表
    protected $table = 'brand';
    // 设置允许接收的字段
    protected $fillable = ['brand_name','logo'];

    public function _before_write(){
        $this->_delete_logo();

        $upload = \libs\Uploader::make();
        $logo = '/uploads/'.$upload->upload('logo','brand');

        $this->data['logo'] = $logo;
    }
    public function _before_delete(){
        $this->_delete_logo();
    }

    public function _delete_logo(){
        if(isset($_GET['id'])){
            $ol = $this->findOne($_GET['id']);
            @unlink(ROOT.'public'.$ol['logo']);
        }
    }
}