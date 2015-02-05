<?php

require __DIR__.'/vendor/autoload.php';

$args = \CommandLine::parseArgs($_SERVER['argv']);

if (!isset($args['file'])
	|| !isset($args['key'])
	|| !isset($args['value'])
	) {
	echo "php cfg.php --file=<config-file> --key=<key> --value=<value> [--type=(string|number|boolean)]\n";
	echo "\n";
	echo "    --file      File name (YAML or JSON).\n";
	echo "    --key       Key to be changed. Use dot to identify namespace.\n";
	echo "    --value     New value.\n";
	echo "    --type      (default=string) Type of value. If invalid type is supplied, string is used.\n";
	echo "\n";
	echo "Example\n";
	echo "    php cfg.php --file=config.yaml --key=server.host --value=localhost\n";
	echo "    php cfg.php --file=config.yaml --key=server.port --value=80 --type=number\n";
	echo "\n";
	die();
}

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
$config->set($args['key'], $args['value']);
$config->save($args['file']);