# Cfg-Cmd
Configuration (YAML, JSON) command line utility

### Install Composer
- [Composer](https://getcomposer.org/)
```sh
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

### Installation
```sh
composer install
```

### Usage
```sh
./cfg --file=<config-file> --key=<key> --value=<value> [--type=(string|number|boolean)] [--silent]
./cfg --file=<config-file> --verify

    --file      File name (YAML or JSON).
    --key       Key to be changed. Use dot to identify namespace.
    --value     New value.
    --type      (default=string) Type of value. If invalid type is supplied, string is used.
    --silent    Do not show update reulst.
    --verify    Verify configuration file.
```

Example
```sh
    ./cfg --file=config.yaml --key=server.host --value=localhost
    ./cfg --file=config.yaml --key=server.port --value=80 --type=number
    ./cfg --file=config.yaml --key=server.host --value=localhost --silent
    ./cfg --file=config.yaml --verify
```

### License
MIT: <http://chonla.mit-license.org/>