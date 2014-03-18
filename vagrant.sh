#!/bin/bash

apt-get update

# Setup MySQL

	DEBIAN_FRONTEND=noninteractive apt-get -y install mysql-server

	sed -i.bak "s/^bind-address.*$/bind-address = \*/g" /etc/mysql/my.cnf

	service mysql restart

	# Create DB
	mysqladmin create fsm

	# Create application user.
	mysql -uroot -e "GRANT ALL PRIVILEGES ON fsm.* TO 'fsm'@'%' IDENTIFIED BY 'fsm'"

	mysql -uroot fsm < /vagrant/schema.sql

	# Change Root password.
	mysqladmin -uroot password ys45eY2fJq6a7uq

# install php

	apt-get -y install php5
	apt-get -y install php5-mysqlnd
	apt-get -y install php5-intl
	apt-get -y install php5-curl
