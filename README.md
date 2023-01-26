# NOTICE! This project has moved here: https://github.com/Kristianstad/origo/pkgs/container/origo

# sam-origo
Origo from Github master, image based on huggla/sam-lighttpd2. Listens on port 8080 internally. Files and directories in the Origo config directory are added to the Origo web directory at startup.

## Environment variables
### Runtime variables with default value
* VAR_LINUX_USER="www-user" (User running VAR_FINAL_COMMAND)
* VAR_ORIGO_CONFIG_DIR="/etc/origo" (Directory containing configuration files for Origo)
* VAR_CONFIG_DIR="/etc/lighttpd2" (Directory containing configuration files for Lighttpd2)
* VAR_FINAL_COMMAND="lighttpd2 -c '\$VAR_CONFIG_DIR/angel.conf'" (Command run by VAR_LINUX_USER)

## Capabilities
Can drop all but SETPCAP, SETGID and SETUID.
