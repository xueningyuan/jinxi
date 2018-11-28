<?php
require('./vendor/autoload.php');

use Qiniu\Storage\UploadManager;
use Qiniu\Auth;

$pdo = new \PDO('mysql:host=127.0.0.1;dbname=jxshop', 'root','123');

$client = new \Predis\Client([
    'scheme' => 'tcp',
    'host'   => 'localhost',
    'port'   => 6379,
]);

// 设置 socket 永不超时
ini_set('default_socket_timeout', -1); 

// 上传七牛云
$accessKey = 'vOClEWmnmWQvWpRd7cH1cL4RIOdHGSO8oxOQD8BP';
$secretKey = 'mDY1HBRZoiihPqNX1mVwalCZQRX-JWNLclp4h7Cu';
$domain = 'http://piul9a6lo.bkt.clouddn.com';
// 配置参数
$bucketName = 'api-shop';   // 创建的 bucket(新建的存储空间的名字)

$auth = new Auth($accessKey, $secretKey);
// 登录到七牛云（登录获取令牌）
// 第一参数：存储空间的名称
// 第二参数：策略
// 第三参数：令牌的有效期
$expire = 86400 * 365 * 10; // 令牌过期时间10年
$token = $auth->uploadToken($bucketName, null, $expire);

$upManager = new UploadManager();

// 循环监听一个列表
while(true)
{   
    // 从列表的右侧取出数据（如果列表中没有数据就一直阻塞）
    $rawdata = $client->brpop('jxshop:niqui', 0);
    echo "kai\r\n";
    // 处理数据
    $data = unserialize($rawdata[1]); // 转成数组
    // 获取文件名
    $name = ltrim(strrchr($data['logo'], '/'), '/');
    // 上传的文件
    $file = './public'.$data['logo'];
    list($ret, $error) = $upManager->putFile($token, $name, $file);
    // 判断是否成功
    if ($error !== null) {
        // 再将数据放到列表中（lpush：从左侧放）
        $client->lpush('jxshop:niqui', $rawdata[1]); 
        var_dump( $error );           
    } else {
        // 更新数据库
        $new = $domain.'/'.$ret['key'];
        $sql = "UPDATE ".$data['table']." SET ".$data['column']."='$new' WHERE id=".$data['id'];
        $pdo->exec($sql);
        // 删除本地文件
        @unlink($file);
        echo "ok\r\n";
    }
}
