<?php
function view($file,$data=[]){
    extract($data);
    include(ROOT.'views/'.$file.'.html');
}