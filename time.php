<?php
echo date_default_timezone_get();
date_default_timezone_set('Asia/Shanghai');
echo '<br/>set default timezone:',date_default_timezone_get();
echo "<br/>c " . date('c');
echo "<br/>ymd " . date('ymd');
echo "<br/>date('t')计算这个月有多少天 t " . date('t');
echo "<br/>星期几 N " . date('N');
echo "<br/>月份n " . date('n');
echo "<br/>" . date('Y-m-d H:i:s');
echo "<br/>-1 day: " . date('c', strtotime('-1 day'));
echo "<br/>-1 days: " . date('c', strtotime('-1 days'));
echo "<br/>+1 day: " . date('c', strtotime('+1 day'));
echo "<br/><br/>+2 days today = today +2 days";
echo "<br/>+2 days today: " . date('c', strtotime('+2 days today'));
echo "<br/>today +2 days " . date('c', strtotime('today +2 days'));
echo "<br/>Y-M-01: " . date('Y-M-01');
echo "<br/>Y-m-01: " . date('c', strtotime(date('Y-M-01')));
echo "<br/>noon: " . date('c', strtotime('noon'));
echo "<br/>tomorrow: " . date('c', strtotime('tomorrow'));
echo "<br/>yestday: " . date('c', strtotime('yesterday'));

echo "<br/><br/>当前天开始";
echo "<br/> 00:00 = today = 0.0";
echo "<br/>today: " . date('c', strtotime('today'));
echo "<br/>midnight: " . date('c', strtotime('midnight'));
echo "<br/>00:00: " . date('c', strtotime('00:00'));
echo "<br/>0.0: " . date('c', strtotime('0.0'));
echo "<br/>某天2021-11-05的开始: " . date('c', strtotime('2021-11-05 midnight'));
echo "<br/>23:59:59: " . date('c', strtotime('23:59:59'));
echo "<br/>15:40:59: " . date('c', strtotime('15:40:59'));
echo "<br/>today -2 days: " . date('c', strtotime('today -2 days'));
echo "<br/>-1 day 00:00: " . date('c', strtotime('-1 day 00:00'));
echo "<br/>-3 day noon: " . date('c', strtotime('-3 day noon'));
echo "<br/>20200320 " . date('c', strtotime('20200320'));
echo "<br/>20200320 +1 day" . date('c', strtotime('20200320 +1 day'));
echo "<br/>midnight -3 day noon: " . date('c', strtotime('midnight -3 day noon'));

echo "<br/><br/>";
echo "<br/>Ymd H:i:s   " . date('c', strtotime(date('Ymd H:i:s')));
echo "<br/>date('Y-m-d H:i:s') 00:00 无效" . date('c', strtotime(date('Y-m-d H:i:s') . ' 00:00'));
echo "<br/>date('Y-m-d H:i:s') midnight" . date('c', strtotime(date('Y-m-d H:i:s') . ' midnight'));



echo "<br/>2018-12-03: " . date('c', strtotime('2018-12-03'));

echo "<br/><br/>月份";
echo "<br/>first day of this month: " . date('c', strtotime('first day of this month'));
echo "<br/>first day of this month  00:00:00  : " . date('c', strtotime('first day of this month 00:00:00'));
echo "<br/>first day of this month midnight: " . date('c', strtotime('first day of this month midnight'));
echo "<br/>last day of this month: " . date('c', strtotime('last day of this month midnight'));
echo "<br/>上个月 last month: " . date('c', strtotime('last month'));
echo "<br/>first day of last month: " . date('c', strtotime('first day of last month midnight'));
echo "<br/>last day of last month: " . date('c', strtotime('last day of last month midnight'));

echo "<br/><br/>reltext space 'week'";
echo "monday 和monday this week 不是同一天";
echo "<br/>monday: " . date('c', strtotime('monday'));
echo "<br/>tuesday: " . date('c', strtotime('tuesday'));
echo "<br/>this week: " . date('c', strtotime('this week'));
echo "<br/>Monday  this week: " . date('c', strtotime(' monday  this week'));
echo "<br/>sunday this week: " . date('c', strtotime('sunday this week'));
echo "<br/>today this week: " . date('c', strtotime('today this week'));
echo "<br/>today -1 week: " . date('c', strtotime('today -1 week'));
echo "<br/>previous week: " . date('c', strtotime('previous week'));
echo "<br/>last week: " . date('c', strtotime('last week'));
echo "<br/>today previous week: " . date('c', strtotime('today previous week'));
echo "<br/>today last week: " . date('c', strtotime('today last week'));
echo "<br/>Monday previous week: " . date('c', strtotime('Monday previous week'));
echo "<br/>Monday last week: " . date('c', strtotime('Monday last week'));
echo "<br/>last Sat 上一个周六: " . date('c', strtotime('last Sat'));
echo "<br/>last Sat of this month 这个月最后一个周六" . date('c', strtotime('last Sat of this month'));
echo "<br/>last Sat  this month 实际是 last Sat " . date('c', strtotime('last Sat  this month'));
echo "<br/>noon -1 week " . date('c', strtotime('noon -1 week'));
echo "<br/>weekdays " . date('c', strtotime('weekdays'));
echo "<br/>-1 weekdays " . date('c', strtotime('-1 weekdays'));
echo "<br/>-2 weekdays " . date('c', strtotime('-2 weekdays'));
echo "<br/>-7 weekdays " . date('c', strtotime('-7 weekdays'));



echo "<br/><br/>日期差";




