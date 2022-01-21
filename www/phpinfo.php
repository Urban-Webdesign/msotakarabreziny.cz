<?php

$date = date_create();
echo date_format($date, 'U = Y-m-d H:i:s') . "\n";

date_timestamp_set($date, 1642593716);
echo date_format($date, 'U = Y-m-d H:i:s') . "\n";
