<?php
namespace models;

class Model
{
    protected $_db;
    protected $table;//表名
    protected $data;//表单中的数据

    public function __construct()
    {
        $this->_db = \libs\Db::make();
    }

    public function insert()
    {   
        $keys=[];
        $values=[];
        $token=[];
        foreach($this->data as $k =>$v){
            $keys[] = $k;
            $values[] =$v;
            $token[] ='?';
        }

        $keys = implode(',',$keys);
        $token = implode(',',$token);
        $sql = "INSERT INTO {$this->table}($keys) VALUES($token) ";

        $stmt = $this->_db->prepare($sql);
        return $stmt->execute($values);
    }

    public function update()
    {

    }

    public function delete()
    {

    }

    public function findAll()
    {
        $sql = "SELECT * FROM {$this->table} ";

        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll( \PDO::FETCH_ASSOC );

    }
    public function findOne()
    {
        
    }
    public function fill($data)
    {
        foreach($data as $k => $v){
            if(!in_array($k,$this->fillable)){
                unset($data[$k]);
            }
        }
        $this->data = $data;
    }

}