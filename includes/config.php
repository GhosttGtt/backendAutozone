 <?php

   $db_user = 'root';
   $db_pass = '';
   $db_name = 'autozone';

   $db = new PDO('mysql:host=localhost;dbname=' . $db_name, $db_user, $db_pass);

   //define('BASE_URL', 'https://www.alexcg.de/autozone/');
   define('BASE_URL', 'http://localhost/autozone/backendAutozone/');
   // set attributes
   $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
   $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
   $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   define('APP_NAME', 'AutoZone');
   define('JWT_SECRET_KEY', 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855');

   ?>