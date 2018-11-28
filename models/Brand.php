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

    // 添加之后执行的钩子函数
    public function _after_write()
    {   
        // 构造数据（上传七牛云）
        $data = [
            'logo' => $this->data['logo'],
            'id' => $this->data['id'] ? $this->data['id']:$_GET['id'],
            'table' => 'brand',
            'column' => 'logo',
        ];

        $client = new \Predis\Client([
            'scheme' => 'tcp',
            'host'   => 'localhost',
            'port'   => 6379,
        ]);

        // 转成字符串保存到队列中
        $client->lpush('jxshop:niqui', serialize($data));

    }
}