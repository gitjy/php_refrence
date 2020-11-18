<?php
/*
//保存为文件
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="echo.txt"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');*/
echo "flush start\n";
if (ob_get_level() == 0) ob_start();

for ($i = 0; $i<2; $i++){
        echo "Line to show.$i\n";
        //echo str_pad('',4096)."\n"; //按行显示

        //ob_flush();
        flush();
        sleep(1);
}

echo "Done.";

ob_end_flush();

//方式2
/*ob_start();
echo "Done.\n";
sleep(1);
echo "Done.1\n";*/