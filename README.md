# Cfg-Cmd
Configuration (YAML, JSON) command line utility

### Usage
```sh
php cfg.php --file=<config-file> --key=<key> --value=<value> [--type=(string|number|boolean)]

    --file      File name (YAML or JSON).
    --key       Key to be changed. Use dot to identify namespace.
    --value     New value.
    --type      (default=string) Type of value. If invalid type is supplied, string is used.
```

Example
```sh
    php cfg.php --file=config.yaml --key=server.host --value=localhost
    php cfg.php --file=config.yaml --key=server.port --value=80 --type=number
```

### License
MIT: <http://chonla.mit-license.org/>