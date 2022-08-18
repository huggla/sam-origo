# Set in stage2:
# ---------------------------------------------------------
# set -ef +am
# isFirstRun
# VAR_*
# All functions in /start/functions

if [ "$isFirstRun" == "true" ]
then
   if [ ! -s "/etc/php81/php-fpm.d/www.conf" ]
   then
      createWwwConf
   fi
   if [ ! -s "/etc/php81/conf.d/50-setting.ini" ]
   then
      setPhpIni
   fi
   if [ ! -s "/etc/ldap/ldap.conf" ]
   then
      createLdapConf
   fi
fi
