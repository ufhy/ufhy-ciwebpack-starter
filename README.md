# Codeigniter starter kit
this is starter kit for codeigniter framework and using
* [Codeigniter installer](https://github.com/kenjis/codeigniter-composer-installer)
* [Module Ekstensions HMVC](https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc)
* [webpack 4](https://webpack.js.org)
* [Vue](https://vuejs.org)

## Helper added
application_helper.php
* indoDate($timestamp = '', $date_format = 'l, j F Y | H:i', $suffix = '')
* terbilang($number, $suffix = 'rupiah')
* dbPrefix($table)
* bgExec($command)

bootstrap_helper.php
* formInvalidFeedback($field)
* formInvalid($field)

## Libraries added
* Asset and Templating
* Debuger bar

## Folder Structure
```
webapp/
├── composer.json
├── package.json
├── application/
│   ├── ...
│   ├── modules
│   ├── themes
├── public/
│   ├── assets
│   ├── dist
│   ├── .htaccess
│   └── index.php
└── vendor/
    └── codeigniter/
        └── framework/
            └── system/
```

## Requirements

* PHP 5.3.7 or later
* `composer` command (See [Composer Installation](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx))
* [Nodejs](https://nodejs.org/en/)
* Git

#### Install Third Party Libraries
[Codeigniter Matches CLI](https://github.com/avenirer/codeigniter-matches-cli):
```
$ php bin/install.php matches-cli master
```

[CodeIgniter HMVC Modules](https://github.com/jenssegers/codeigniter-hmvc-modules):
```
$ php bin/install.php hmvc-modules master
```

[Modular Extensions - HMVC](https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc):
```
$ php bin/install.php modular-extensions-hmvc codeigniter-3.x
```

[Ion Auth](https://github.com/benedmunds/CodeIgniter-Ion-Auth):
```
$ php bin/install.php ion-auth 2
```

[CodeIgniter3 Filename Checker](https://github.com/kenjis/codeigniter3-filename-checker):
```
$ php bin/install.php filename-checker master
```

[CodeIgniter Rest Server](https://github.com/chriskacerguis/codeigniter-restserver):
```
$ php bin/install.php restserver 2.7.2
```
[CodeIgniter Developer Toolbar](https://github.com/JCSama/CodeIgniter-develbar):
```
$ php bin/install.php codeigniter-develbar master
```

### Run PHP built-in server (PHP 5.4 or later)
```
$ cd /path/to/codeigniter
$ bin/server.sh
```

### Update CodeIgniter
```
$ cd /path/to/codeigniter
$ composer update
```

You must update files manually if files in `application` folder or `index.php` change. Check [CodeIgniter User Guide](http://www.codeigniter.com/user_guide/installation/upgrading.html).

## Reference
* [Composer Installation](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
* [CodeIgniter](https://github.com/bcit-ci/CodeIgniter)
* [Translations for CodeIgniter System](https://github.com/bcit-ci/codeigniter3-translations)