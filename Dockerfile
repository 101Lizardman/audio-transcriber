FROM ubuntu

RUN apt-get update && apt-get install -y \
	curl \
	php \
	libapache2-mod-php
	
RUN curl -s https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

