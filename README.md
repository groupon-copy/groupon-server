## groupon_database.sql
- import into an empty mysql database
- <code>mysql -u root -p groupon_database < groupon_database.sql</code>

## public.zip
- extract folder and put into server connected internet
- make neccessary changes in the Config.php in /include folder
- the firebase key and database configurations
- if you are using own server (not leasing one) then use PHPMailer where neccessary

## GrouponAdministrator.zip
- extract archive and open in Android Studio 2.2
- this project targets Android 23 (before 24/ Android 7/Nougat)
- min Android API 4.4/ number 19

## GrouponPartOne
- only has the Groupon functionality. no yelp stuff/part two of the app


# INFRASTRUTURE SETUP

### Error message “Forbidden You don't have permission to access / on this server”
- go to this link
- http://stackoverflow.com/questions/10873295/error-message-forbidden-you-dont-have-permission-to-access-on-this-server
- quick answer, locate something like this in the http.conf and replace it with this
```
   <Directory />
    #Options FollowSymLinks
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride All
    Order deny,allow
    Allow from all
   </Directory>
```
- get rid of the /public/android/groupon_slim_api_version/v1/.htaccess file
- install apache server and php module
- for apache to use php
  - this is for apache server apr and apr-util dependency
  - <code>sudo apt-get install libapr1-dev libaprutil1-dev</code>
  - this is for apache server pcre dependency
  - <code>sudo apt-get install libpcre3-dev libbz2-dev</code>
  - this is for php install
  - <code>sudo apt-get install libxml2-dev</code>
