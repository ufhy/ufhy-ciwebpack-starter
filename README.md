# Codeigniter starter kit
this is starter kit for codeigniter framework and using
* [Codeigniter installer](https://github.com/kenjis/codeigniter-composer-installer)
* [Module Ekstensions HMVC](https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc)
* [webpack 4](https://webpack.js.org)
* [Vue](https://vuejs.org)
* [Vuetify](https://vuetifyjs.com)

## Install
* import database mysql from file `db/uf_ciwebpack_starter.sql`
* create virtual host with `public` folder as `DocumentRoot`
* user: `administrator` and pass: `qwerty`

## NPM Command
* `npm run start` : before developing, make sure you have run the webpack dev server first
* `npm run build` : ready to deploy. The deploy files will be stored in the `public/dist` folder

## Helper added
application_helper.php
* indoDate($timestamp = '', $date_format = 'l, j F Y | H:i', $suffix = '') : format datetime with indonesia language
* terbilang($number, $suffix = 'rupiah') : Currency with indonesia language
* dbPrefix($table) : call table name with prefix
* bgExec($command) : execute command shell in the background

## Libraries added
* Asset and Templating system
* Debugger bar

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

### Update CodeIgniter
```
$ cd /path/to/codeigniter
$ composer update
```

You must update files manually if files in `application` folder or `index.php` change. Check [CodeIgniter User Guide](http://www.codeigniter.com/user_guide/installation/upgrading.html).

## Reference
* [Composer Installation](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
* [CodeIgniter](https://github.com/bcit-ci/CodeIgniter)