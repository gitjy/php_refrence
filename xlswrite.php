<?php
function index2(){
    //指定导出类型
    header("Content-Type:application/vnd.ms-excel"); 
    header("Content-Disposition:attachment;filename=stu.xls");
    
    
    echo "id01\tzhangsan\tman\t20\t\n";
    echo "id01\tzhangsan\tman\t20\t\n";
    echo "id01\tzhangsan\tman\t20\t\n";
    echo "id01\tzhangsan\tman\t20\t\n";
    echo "id01\tzhangsan\tman\t20\t\n";
    echo "id01\tzhangsan\tman\t20\t\n";
    echo "id01\tzhangsan\tman\t20\t\n";
    exit();
}

function down(){
    //指定导出类型
    
    ob_start();
    
    echo "id01\tzhangsan\tman\t20\t\n";
    echo "id01\tzhangsan\tman\t20\t\n";
    echo "id01\tzhangsan\tman\t20\t\n";
    echo "id01\tzhangsan\tman\t20\t\n";
    echo "id01\tzhangsan\tman\t20\t\n";
    echo "id01\tzhangsan\tman\t20\t\n";
    echo "id01\tzhangsan\tman\t20\t\n";
    
    $data = ob_get_contents();
    ob_end_clean();
    file_put_contents('stu1.xls', $data);

}    

down();


//index2();

