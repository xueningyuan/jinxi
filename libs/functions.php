<?php
function view($file,$data=[]){
    extract($data);
    include(ROOT.'views/'.$file.'.html');
}

function redirect($url)
{
    header('Location:'.$url);
    exit;
}