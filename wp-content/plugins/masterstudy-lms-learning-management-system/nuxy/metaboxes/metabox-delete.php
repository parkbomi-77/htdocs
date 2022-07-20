<?php
$deletecheck = $_POST['deletecheck'];
$num = count($deletecheck);

define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );

for($i=0; $i<$num; $i++){
    $wpdb->delete('wp_product_list', 
    array(
        'ID' => $deletecheck[$i]
    ));
}

$prevPage = $_SERVER['HTTP_REFERER'];
$location ='location:'.$prevPage.'#registrationbox';
header($location);

?>