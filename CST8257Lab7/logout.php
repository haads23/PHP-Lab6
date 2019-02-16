<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
 
    session_start();
?>

        <?php
        // put your code here
        session_destroy();
        header("Location: index.php");
        exit;
        
        ?>

<?php

?>
