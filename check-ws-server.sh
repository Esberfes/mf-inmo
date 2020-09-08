#!/bin/bash

URL="http://inmobiliariafranquicias.hl805.dinaserver.com/push/ping_server"
NOW=$(date)

echo $NOW - Checking connection...

CODE=$(curl -LI $URL -o . -w '%{http_code}\n' -s )

echo $NOW - Server response with code: $CODE

if [ $CODE != 200 ]; then
    kill $(lsof -t -i:6001)
    echo $NOW - "Executing command: nohup php www/inmobiliaria/artisan websockets:serve  &"
	nohup .bin/php www/inmobiliaria/artisan websockets:serve  &
else
    echo $NOW - "Server running"
fi

echo $NOW - End check \
