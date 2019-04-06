<?php

// 数据库设置
define("SERVERNAME", "localhost");
define("USERNAME", "");
define("PASSWORD", "");
define("DATABASE", "");

// 活动时间设置 修改为具体时间
define("START_TIME", mktime(h, m, s, month, day, year));
define("END_TIME", mktime(h, m, s, month, day, year));

// 取信周期
define("PERIOD", "半年");  // 一年期改为“一年”

// 线上音频文件夹路径 最后带/
define("ONLINEPATH", "");
// 二维码音频文件夹路径 最后带/
define("QRPATH", "");
