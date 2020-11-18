<?PHP
$users = [
  ['id' => 1, 'service_id' => 11],
  ['id' => 2, 'service_id' => 11],
  ['id' => 3, 'service_id' => 11],
  ['id' => 4, 'service_id' => 11],
  ['id' => 5, 'service_id' => 11],
  ['id' => 6, 'service_id' => 11],
  ['id' => 7, 'service_id' => 11],
  ['id' => 8, 'service_id' => 11],
  ['id' => 9, 'service_id' => 11],
  ['id' => 10, 'service_id' => 13],
  ['id' => 11, 'service_id' => 27],
  ['id' => 12, 'service_id' => 8],
  ['id' => 13, 'service_id' => 14],
  ['id' => 14, 'service_id' => 14],
  ['id' => 15, 'service_id' => 14],
  ['id' => 16, 'service_id' => 11],
  ['id' => 17, 'service_id' => 65],
  ['id' => 18, 'service_id' => 11],
  ['id' => 19, 'service_id' => 11],
  ['id' => 20, 'service_id' => 11],
  ['id' => 21, 'service_id' => 11],
  ['id' => 22, 'service_id' => 11],
  ['id' => 23, 'service_id' => 11],
  ['id' => 24, 'service_id' => 18],
  ['id' => 25, 'service_id' => 11],
  ['id' => 26, 'service_id' => 11],
  ['id' => 27, 'service_id' => 11],
  ['id' => 28, 'service_id' => 11],
  ['id' => 29, 'service_id' => 11],
  ['id' => 30, 'service_id' => 11],
  ['id' => 31, 'service_id' => 11],
  ['id' => 32, 'service_id' => 11],

  ];
$keepCate = [];
$cnt = count($users);
for ($k =0;$k<$cnt;$k++) {
    if (isset($keepCate[$users[$k]['service_id']])) {
        if (2 == $keepCate[$users[$k]['service_id']]) {
            $i = 1;
            do {
                if (isset($users[$k+$i])) {
                    if ($users[$k+$i]['service_id'] != $users[$k]['service_id']) {
                        list($users[$k], $users[$k+$i]) = array($users[$k+$i], $users[$k]);
                        $keepCate = [];
                        break;
                    }
                    $i++;
                } else {
                    $keepCate = [];
                    break;
                }
            } while(true);
        } else {
            $keepCate[$users[$k]['service_id']]++;
        }
    } else {
        $keepCate = [];
        $keepCate[$users[$k]['service_id']]=1;
    }
}
print_r($users);
