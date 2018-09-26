<?php
namespace controllers;

class CodeController
{
    public function make(){
        $tableName = $_GET['name'];

        $cname = ucfirst($tableName).'Controller';
        $mname = ucfirst($tableName);
        // 加载模板
        ob_start();
        include(ROOT.'templates/controller.php');
        $str = ob_get_clean();
        file_put_contents(ROOT.'controllers/'.$cname.'.php',"<?php\r\n".$str);

        // 生成模型
        ob_start();
        include(ROOT.'templates/model.php');
        $str = ob_get_clean();
        file_put_contents(ROOT.'models/'.$mname.'.php',"<?php\r\n".$str);

        // 生成静态页面
        @mkdir(ROOT . 'views/'.$tableName, 0777);

         // 取出这个表中所有的字段信息
         $sql = "SHOW FULL FIELDS FROM $tableName";
         $db = \libs\Db::make();
         // 预处理
         $stmt = $db->prepare($sql);
         // 执行 SQL
         $stmt->execute();
         // 取出数据
         $fields = $stmt->fetchAll( \PDO::FETCH_ASSOC );
         
        
        // create.html
        ob_start();
        include(ROOT . 'templates/create.html');
        $str = ob_get_clean();
        file_put_contents(ROOT.'views/'.$tableName.'/create.html', $str);
        // edit.html
        ob_start();
        include(ROOT . 'templates/edit.html');
        $str = ob_get_clean();
        file_put_contents(ROOT.'views/'.$tableName.'/edit.html', $str);
        // index.html
        ob_start();
        include(ROOT . 'templates/index.html');
        $str = ob_get_clean();
        file_put_contents(ROOT.'views/'.$tableName.'/index.html', $str);


    }
}