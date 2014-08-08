<?php
/**
 * @property CI_Loader $load
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Email $email
 * @property CI_DB_active_record $db
 * @property CI_DB_forge $dbforge
 */
$config['per_page']= 4;
$config['uriparam_index']= 3;
$config['img_url']= 3;
$config['remote_speedyauthentication']= "http://portal.telkomspeedy.com/remote_bras.php";
$config['remote_speedyvalidation']= "libraries/xmlsoap/OS3_Radius_Status.wsdl";
$config['remote_speedymdnvalidation']= "libraries/xmlsoap/validasiSpeedyMDN.wsdl";
$config['remote_speedyipvalidation']= "libraries/xmlsoap/GETSUBSBYIP.wsdl";
$config['remote_httpget_speedyipvalidation']= "http://10.11.30.21/getaccount/getIP.php";
$config['remote_telkomvouchervalidation']= "libraries/xmlsoap/flexi_validasiVoucher.wsdl";
$config['remote_telkomvouchertrxpayment']= "libraries/xmlsoap/flexi_provContent_TContent.wsdl";
$config['remote_flexicashtrxpayment']= "";
$config['remote_flexicashtrxpaymentstatus']= "";
$config['max_newrelease']= 4;
$config['wsdluser_checkip']= "usercontent";
$config['wsdlpassword_checkip']= "telkom135";
$config['wsdluser_checkmdn']= "usercontent";
$config['wsdlpassword_checkmdn']= "telkom135";
$config['wsdluser']= "OS3";
$config['wsdlpassword']= "telkomOS3"
?>