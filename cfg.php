<?php

require __DIR__.'/vendor/autoload.php';

define('VERSION', '0.2');

$args = \CommandLine::parseArgs($_SERVER['argv']);

if ((!isset($args['file'])
	|| !isset($args['key'])
	|| !isset($args['value'])
	) && (!isset($args['file']) || !isset($args['verify']))) {
	echo "Configuration Command Line Kit (version ".constant('VERSION').")\n";
	echo "\n";
	echo "php cfg.php --file=<config-file> --key=<key> --value=<value> [--type=(string|number|boolean)] [--silent]\n";
	echo "php cfg.php --file=<config-file> --verify\n";
	echo "\n";
	echo "    --file      File name (YAML or JSON).\n";
	echo "    --key       Key to be changed. Use dot to identify namespace.\n";
	echo "    --value     New value.\n";
	echo "    --type      (default=string) Type of value. If invalid type is supplied, string is used.\n";
	echo "    --silent    Do not show update result.\n";
	echo "    --verify    Verify the given YAML or JSON file.\n";
	echo "\n";
	echo "Example\n";
	echo "    php cfg.php --file=config.yaml --key=server.host --value=localhost\n";
	echo "    php cfg.php --file=config.yaml --key=server.port --value=80 --type=number\n";
	echo "    php cfg.php --file=config.yaml --key=server.port --value=80 --type=number  --silent\n";
	echo "    php cfg.php --file=config.yaml --verify\n";
	echo "\n";
	die();
}

// Just verify
if (isset($args['verify'])) {
	try {
		$verify = \Chonla\Cfg\Config::load($args['file']);
		echo "Configuration file is successfully verified.\n";
	} catch(\Exception $e) {
		die($e['message']);
	}
	exit(0);
}

// Update file
if (!file_exists($args['file'])) {
	touch ($args['file']);
}
$config = \Chonla\Cfg\Config::load($args['file']);
if (isset($args['type'])) {
	switch ($args['type']) {
		case 'number':
			if (is_numeric($args['value'])) {
				if (strpos($args['value'], '.') !== FALSE) {
					$args['value'] = doubleval($args['value']);
				} else {
					$args['value'] = intval($args['value']);
				}
			}
			break;
		case 'boolean':
			if (in_array(strtolower($args['value']), ['true', 1, 'yes', 'y'])) {
				$args['value'] = true;
			} elseif (in_array(strtolower($args['value']), ['false', 0, 'no', 'n'])) {
				$args['value'] = false;
			}
			break;
		default:

	}
}

// Backup
copy($args['file'], '.'.$args['file'].'.tmp');

// Do
$config->set($args['key'], $args['value']);
$config->save($args['file']);

// Verbose
if (!isset($args['silent'])) {
	if ($config->get($args['key']) == $args['value']) {
		echo "Key " . $args['key'] . " is successfully updated to " . $args['value'] . ".\n";
	} else {
		echo "Key " . $args['key'] . " is unable to be updated.\n";
	}

	// Verify
	try {
		$verify = \Chonla\Cfg\Config::load($args['file']);
		echo "Configuration file is successfully verified.\n";
		unlink('.'.$args['file'].'.tmp');
	} catch(\Exception $e) {
		die($e['message']);
	}
}
