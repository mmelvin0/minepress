<?php

ob_start();
defined('DS') || define('DS', DIRECTORY_SEPARATOR);
header('Content-Type: application/json');

$host = '127.0.0.1';
$result = array('up' => false);
$fp = fopen(sys_get_temp_dir() . DS . 'minepress.cache', 'ab+');
$locked = false;
if ($fp && flock($fp, LOCK_SH)) {
	$locked = true;
	$stat = fstat($fp);
	if (isset($stat['mtime']) && $stat['mtime'] + 30 > time()) {
		fseek($fp, 0);
		$result = unserialize(stream_get_contents($fp));
	}
}
if (
	!isset($result['max-players']) ||
	!isset($result['motd']) ||
	!isset($result['players'])
) {
	require_once __DIR__ . DS . 'MinecraftQuery_Simple.php';
	if (strpos($host, ':') === false) {
		$data = QueryMinecraft($host);
	} else {
		list($host, $port) = explode(':', $host, 2);
		$data = QueryMinecraft($host, $port);
	}
	$result['max-players'] = isset($data['MaxPlayers']) ? $data['MaxPlayers'] : 0;
	$result['motd'] = isset($data['HostName']) ? $data['HostName'] : '';
	$result['players'] = isset($data['Players']) ? $data['Players'] : 0;
	$result['up'] = !!$data;
	if ($result['up'] && $fp && flock($fp, LOCK_EX)) {
		$locked = true;
		fseek($fp, 0);
		ftruncate($fp, 0);
		fwrite($fp, serialize($result));
	}
}

if ($fp) {
	if ($locked) {
		flock($fp, LOCK_UN);
	}
	fclose($fp);
}

if (preg_match(
	'/^.*up\s+(?P<uptime>[^,]+),.*load average:\s*(?P<load>.*)$/',
	trim(shell_exec('uptime')),
	$match
)) {
	$result['load'] = $match['load'];
	$result['uptime'] = $match['uptime'];
}

if (preg_match(
	'/(?P<total>\d+)\s+(?P<used>\d+)\s+(?P<free>\d+)\s+(?P<shared>\d+)\s+(?P<buffers>\d+)\s+(?P<cached>\d+)/',
	trim(shell_exec('free | head -n2 | tail -n1')),
	$match
)) {
	extract(array_map(function ($x) {
		return (float)$x / 1024;
	}, $match));
	$result['ram'] = sprintf(
		'%.0f MB (%.0f%%)',
		$used - $shared - $buffers - $cached,
		($used - $shared - $buffers - $cached) / $total * 100.0
	);
}

ob_end_clean();
print json_encode($result);
