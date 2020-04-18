#! /usr/bin/php
<?php
const RESOLV_PATH = '/etc/resolv.conf';
const PULSE_CONFIG_PATH = '../etc/pulse/default.pa';

if ($argc < 2) {
    die('参数不足');
}

if (!file_exists(RESOLV_PATH)) {
    die('请在/etc/wsl.conf中开启自动生成resolv.conf的配置(generateResolvConf = true)');
}

# 获取wsl访问Windows的IP
$content = file_get_contents(RESOLV_PATH);
preg_match('/nameserver\s(\d+\.\d+\.\d+\.\d+)/', $content, $ip);



# 更新环境变量
$rc_path = $argv[1];
if (file_exists($rc_path)) $profile_content = file_get_contents($rc_path);
else $profile_content = '';

if (preg_match('/#?export\sPULSE_SERVER=tcp:\d+\.\d+\.\d+\.\d+/', $profile_content)) {
    $profile_content = preg_replace('/#?export\sPULSE_SERVER=tcp:\d+\.\d+\.\d+\.\d+/', 'export PULSE_SERVER=tcp:' . $ip[1], $profile_content);
} else {
    $profile_content .= PHP_EOL . 'export PULSE_SERVER=tcp:' . $ip[1];
}
file_put_contents($rc_path, $profile_content);



# 更新pulseaudio服务器配置
if (!file_exists(PULSE_CONFIG_PATH)) {
    die('请将auto放到pulseaudio的根目录下');
}
$server_config = file_get_contents(PULSE_CONFIG_PATH);
$server_config = preg_replace('/#?load-module\smodule-native-protocol-tcp\slisten=\d+\.\d+\.\d+\.\d+\sauth-anonymous=1/', "load-module module-native-protocol-tcp listen={$ip[1]} auth-anonymous=1", $server_config);
file_put_contents(PULSE_CONFIG_PATH, $server_config);


echo "成功";



