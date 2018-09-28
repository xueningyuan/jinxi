<?php
namespace models;

class Goods extends Model
{
    // 设置这个模型对应的表
    protected $table = 'goods';
    // 设置允许接收的字段
    protected $fillable = ['goods_name','logo','is_on_sale','description','cat1_id','cat2_id','cat3_id','brand_id'];

    public function _before_write(){
        $this->_delete_logo();

        $upload = \libs\Uploader::make();
        $logo = '/uploads/'.$upload->upload('logo','goods');

        $this->data['logo'] = $logo;
    }
    public function _after_write(){
        // echo "<pre>";
        // var_dump( $_FILES );
        // var_dump( $_POST );

        // exit;
        $stmt = $this->_db->prepare("INSERT INTO goods_attribute
        (attr_name,attr_value,goods_id) VALUES(?,?,?) ");
        foreach($_POST['attr_name'] as $k=>$v){
            $stmt->execute([
                $v,
                $_POST['attr_value'][$k],
                $this->data['id'],
            ]);
        }

        // 商品图片
        $uploader = \libs\Uploader::make();
        $stmt = $this->_db->prepare("INSERT INTO goods_image
        (goods_id,path) VALUES(?,?) ");

        foreach($_FILES['image']['name'] as $k => $v)
        {
            $_tmp['name'] = $v;
            $_tmp['type'] = $_FILES['image']['type'][$k];
            $_tmp['tmp_name'] = $_FILES['image']['tmp_name'][$k];
            $_tmp['error'] = $_FILES['image']['error'][$k];
            $_tmp['size'] = $_FILES['image']['size'][$k];

            $_FILES['tmp']=$_tmp;

            $path = '/uploads/'.$uploader->upload('tmp','goods');
            $stmt->execute([
                $this->data['id'],
                $path,
            ]);

        }


        // sku
        $stmt = $this->_db->prepare("INSERT INTO goods_sku
        (goods_id,sku_name,stock,price) VALUES(?,?,?,?) ");

        foreach($_POST['sku_name'] as $k => $v)
        {
            $stmt->execute([
                $this->data['id'],
                $v,
                $_POST['stock'][$k],
                $_POST['price'][$k],
            ]);
        }


    }







    public function _before_delete(){
        $this->_delete_logo();
    }

    public function _delete_logo(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $ol = $this->findOne($id);
            @unlink(ROOT.'public'.$ol['logo']);

            $stmt = $this->_db->prepare("SELECT * FROM goods_image where goods_id=?");
            $stmt->execute([
                $id
            ]);
            $data = $stmt->fetchAll( \PDO::FETCH_ASSOC );
            foreach($data as $k=>$v){
                @unlink(ROOT.'public'.$data[$k]['path']);
            }
    
            $stmt = $this->_db->prepare("DELETE FROM goods_image WHERE goods_id=?");
            $stmt->execute([$id]);
            $stmt = $this->_db->prepare("DELETE FROM goods_attribute WHERE goods_id=?");
            $stmt->execute([$id]);
            $stmt = $this->_db->prepare("DELETE FROM goods_sku WHERE goods_id=?");
            $stmt->execute([$id]);
        }
    }



}