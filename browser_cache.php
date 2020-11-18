<?php
header('Cache-Control:max-age=86400');
//header('Last-Modified:' . gmdate('D, d M Y H:i:s') . ' GMT');

header('Expires:' . gmdate('D, d M Y H:i:s', time()+86400) . ' GMT');
echo '我不想刷新，也没有办法!';