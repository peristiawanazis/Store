<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Store.Telkomspeedy.com</title>
        <meta name="description" content="#" />
        <meta name="keywords" content="#" />
        <meta name="author" content="niweive.com" />

        <!-- SITE STYLE -->
        <link rel="stylesheet" type="text/css" media="screen, projection" href="<?=$header['application']['baseurl']?>skin/default/css/speedystore.css" />
    </head>
    <body style="background: #fff;">
        <?
        $this->load->view('store/include/div-about-'.$pagetype);
        ?>
    </body>
</html>