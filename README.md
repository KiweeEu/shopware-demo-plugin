# Kiwee Demo Shop Plugin

The plugin renders a bar on the top of the page with the text `This is a demo shop`.
The message can be changed via the administration panel at `Extensions > My Extensions > Kiwee Demo Plugin > (Configure)`.

## Installation

```shell
composer require kiwee/shopware-demo-plugin && \
bin/console plugin:install KiweeDemo --activate
```

## Running integration tests
```shell
composer exec phpunit -- -c vendor/kiwee/shopware-demo-plugin/phpunit.xml
```

## License 
This project is licensed under the MIT License.
Copyright 2024 [Kiwee](https://kiwee.eu) sp. z o.o. 
