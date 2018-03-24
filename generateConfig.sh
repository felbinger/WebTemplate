#!/bin/bash

# Generate the config.php for the WebTemplate (https://github.com/felbinger/WebTemplate)

FILENAME="config.php"
TYPES=("mysql" "pgsql" "odbc" "sqlite" "sqlite2")

# Get type
echo "Database Type"
i=0
while [[ $i < ${#TYPES[@]} ]]; do
    echo "["$((i+1))"] ${TYPES[i]}"
    i=$((i+1))
done
read -p "Number: " input
type=${TYPES[input-1]}

read -p "Hostname (Press enter for: localhost): " inputHostname
if [[ -z $inputHostname ]]; then
  hostname="localhost"
else
  hostname=$inputHostname
fi
read -p "Port (Press enter for: 3306): " inputPort
if [[ -z $inputPort ]]; then
  port="3306"
else
  port=$inputPort
fi
read -p "Charset (Press enter for: utf8): " inputCharset
if [[ -z $inputCharset ]]; then
  charset="utf8"
else
  charset=$inputCharset
fi
read -p "Username: " username
read -p "Password: " password
read -p "Database: " databasename


echo "<?php
  /*  Database Configuration  */
  \$database['type'] = \""$type"\";
  \$database['host'] = \""$hostname"\";
  \$database['port'] = \""$port"\";
  \$database['char'] = \""$charset"\";
  \$database['user'] = \""$username"\";
  \$database['pass'] = \""$password"\";
  \$database['name'] = \""$databasename"\";
?>" > $FILENAME
