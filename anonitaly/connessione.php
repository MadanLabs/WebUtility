<?php

mysql_select_db('m56832d1', mysql_connect('217.64.207.50','m56832d1','ironaxe1')) or die(mysql_error());
mysql_query("CREATE TABLE IF NOT EXISTS ai_news(id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, titolo VARCHAR(50) NOT NULL, testo VARCHAR(1000) NOT NULL)") or die(mysql_error());

?>