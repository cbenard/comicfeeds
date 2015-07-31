<?php

require_once("common.php");

header("Content-Type: application/rss+xml");
echo file_get_contents("gs://#default#/feed_dilbert_default");