<?php

$dbname = "Pdcl0www_userdata";
$host = "mysql.dur.ac.uk";
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$opt = array(
PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
PDO::ATTR_EMULATE_PREPARES   => false,
);

$user_db = new PDO($dsn, 'nobody', '', $opt);

$stmt = $user_db->prepare('SELECT * FROM UserDetails WHERE username = ?');
$stmt->execute(array($_ENV["REMOTE_USER"]));
$result = $stmt->fetch();


exit(json_encode($result));
?>