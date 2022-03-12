<?php

$date = date_create();
echo date_format($date, 'U = Y-m-d H:i:s') . "\n";

date_timestamp_set($date, 1473596454);
echo date_format($date, 'U = Y-m-d H:i:s') . "\n";

echo '<hr>';

$date2 = strtotime('-1 year', $date);
echo $date2;