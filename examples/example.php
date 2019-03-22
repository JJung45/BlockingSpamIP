<?php

require_once __DIR__ . '/../vendor/autoload.php';

$blocking = new \Junghakyoung\BlockingKisaSpam\BlockingSpamIP(new \GuzzleHttp\Client());
$blocking->isOk('106.240.37.134');