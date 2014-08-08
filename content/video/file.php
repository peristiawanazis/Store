<?php

header("Content-type: application/x-file-to-save");
//header("Content-type: video/x-avi");
header("Content-Disposition: attachment; filename=".basename($_REQUEST['file']));
//header("filename=".basename($_REQUEST['file']));
readfile($_REQUEST['file']);

?>