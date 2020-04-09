<?php

// 中间件的理解
$reply  =  function ($id) { // 假设是控制器方法
    echo '最终都是执行到这一步！';
    if($id == 1){
        return 'Admin';
    }else{
        return 'Other users';
    }
};

function middleware1($param,callable $next){
    return function () use($param,$next){
        if($param == 1){
            return '您有查询管理员的权限';
        }
        return $next($param); // 执行最终函数
    };
   
}
function middleware2($param,callable $next){
    return function() use ($param,$next){
        if($param == 2){
            return '您没有查询管理员的权限';
        }
        return $next($param);
    };
    
}


$select = 21;
echo middleware1($select,$reply)().PHP_EOL;// 只执行一个中间件
echo middleware2($select,middleware1($select,$reply))();