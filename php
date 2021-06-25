> composer
composer config -g repo.packagist composer https://packagist.phpcomposer.com
composer config repo.packagist composer https://packagist.phpcomposer.com
composer config -g --unset repos.packagist

> install php in debian 10
sudo apt update
sudo apt upgrade -y [&& sudo reboot]

# Download and store PPA repository in a file on your Debian Server/Desktop. But first, download GPG key.
sudo apt -y install lsb-release apt-transport-https ca-certificates 
sudo wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/php.list

sudo apt -y install php7.4
apt install php7.4-{ xxx }
apt install php7.4-common php7.4-fpm php7.4-mysql php7.4-curl php7.4-gd php7.4-mbstring php7.4-xml php7.4-xmlrpc php7.4-zip php7.4-opcache

# install php8.0
sudo apt install -y lsb-release apt-transport-https ca-certificates wget
sudo wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/php.list

> session
发现 post 请求没有 session，判断是权限的问题，结果不是，应该是软件安装过程中 php 包的顺序不对引起的

> 设置默认版本
1. sudo update-alternatives --set php /usr/bin/php7.4
2. sudo update-alternatives --config php