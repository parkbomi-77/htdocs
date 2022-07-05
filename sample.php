

<?php

$Table_Name    = $wpdb->prefix.'wp_posts';
$sql_query     = $wpdb->prepare("SELECT * FROM $Table_Name");
$result        = $wpdb->query( $sql_query ); 
var_dump($result)
?>