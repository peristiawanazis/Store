<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=text/html; charset=utf-8" />
        <?=$header['meta']?>
        <title><?=$header['title']?></title>
        <link rel="stylesheet" type="text/css" media="screen, projection" href="<?=$header['application']['baseurl']?>skin/default/css/speedystore.css" />

    </head>
    <body>
        <p>&nbsp;</p>
        <?
        if ($iserror) {
            echo '<div class="msgbox medium" style="color:#fff;padding: 10px;background: #d31; font-size: 1.5em;">'.$error_mesg.'</div>';
        } else {
            echo '<div class="msgbox medium" style="color:#fff;padding: 10px;background: #D31; font-size: 1.5em;">'.$message.'</div>';
        }
        ?>
    </body>
</html>
