# Accounting System

A Laravel + VueJs project ([Laravel mix](https://laravel.com/docs/8.x/mix))

## Getting started & Installation

This guide favors Ubuntu 20.4. For Windows user you can configure and enable [WSL2](https://docs.microsoft.com/en-us/windows/wsl/install-win10 "Install WSL2 Guide by Microsoft") to run Ubuntu or use XAMPP.

**WARNING: Please avoid using Windows 10 for development as the NPM behaviour becomes erratic, use WSL instead or other Linux based OS**

### Prerequisites

1. Git - latest
```
~$ sudo apt update
~$ sudo apt install git
```
2. PHP version ~8.2 & Composer ~2.x
```
~$ sudo apt update
~$ sudo apt dist-upgrade
~$ sudo apt install composer
~$ composer self-update
~$ sudo apt-get update 
~$ sudo apt install php-xml 
~$ sudo apt-get install php-sqlite3
~$ sudo apt-get install php-curl php-mbstring php-pear php-dev
~$ sudo apt-get install php-mysql
~$ sudo apt-get install php-sqlite
```
3. MySQL 8
    - Download & install MySQL 8 [here](https://dev.mysql.com/downloads/installer/ MySQL Installer) (note: at the time of writing the link is v8 -- if it has changed please update this MD)
    - Or **optionally** use Docker
```
~$ docker run --name mysql -p 3306:3306 -e MYSQL_ROOT_PASSWORD=my-secret-pw -d mysql:8.0.22
```
4. NodeJs > 10.x.x (version installed is v16.15.1)
```
~$ curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.3/install.sh
~$ curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.3/install.sh | bash
~$ source ~/.bashrc
~$ nvm list-remote
~$ nvm install v14.15.4
```
5. `.env` File
    - Get this from your designated onboarding partner
    - Save it anywhere (example `~/home/.env`) then later copy & paste it in the project directory

### Running the project

Open two terminals, on the first terminal do:

```
~$ git clone https://git@github.com:glennbaquero/accounting.git
~$ cd accounting_webapp
~/accounting_webapp$ scp ~/home/.env .
~/accounting_webapp$ composer install
~/accounting_webapp$ php artisan key:generate
~/accounting_webapp$ npm install
~/accounting_webapp$ php artisan app:setup
~/accounting_webapp$ npm run watch
```

On the second terminal:

```
~/accounting_webapp$ php artisan serve
```

### Footnote

Have fun team!
