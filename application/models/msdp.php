<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * PRE CONFIGURE
 * HOSTNAME 10.0.40.103		sitosb1
 */
require 'WsseAuthheader.php';
require 'WsseSessheader.php';
require 'WsseXparamheader.php';

class msdp extends CI_Model {

    function __construct() {
        parent::__construct();
        $params = array();
        $this->_userid = $this->config->item('wsdlsdpuser');
        $this->_password = $this->config->item('wsdlsdppassword');
        $this->_sessionid = "app:6107134037402338164"; //app:3369532597166007412";
    }

    /**
     * return value:
     *  1 : registration valid
     *  0 : registration error
     * -1 : account already exist
     */
    function ssc_registration($params) {
        $soapClient = new SoapClient("http://10.0.40.103:7001/SDP_CMS_SSC_Core/ProxyService/PS_RegisterUser_v_1.0?WSDL");
        //echo "<pre>"; print_r($soapClient); echo "</pre>";
        $soapparams = array(
            'name' => $params['name'] //"chills.distro@gmail.com"
            , 'fullname' => $params['fullname'] //"Sidiq Wahyudi"
            , 'dateOfBirth' => $params['dateofbirth'] //"01/07/1977"
            , 'emailAddress' => $params['emailaddress'] //"chills.distro@gmail.com"
        );
        try {
            $results = $soapClient->__call("RegisterUser", array($soapparams));
        } catch (SoapFault $fault) {
            //echo "<pre>"; print_r($fault); echo "</pre>";
            return 0;
        }
        //echo "<pre>";  @print_r($results); echo "</pre>"; 
        if ($results->statusCode == '1111') {
            return 1;
        } else if ($results->statusCode == '2111') {
            return -1;
        } else {
            return 0;
        }
        /*
         * stdClass Object ( [statusCode] => 2111 [statusDescription] => email has already exist )
         * stdClass Object ( [statusCode] => 1111 [statusDescription] => Success )
         */
    }

    function ssc_changepassword($params) {
        $soapClient = new SoapClient("http://10.0.40.103:7001/SDP_CMS_SSC_Core/ProxyService/PS_ChangePassword_v_1.0?WSDL");
        //echo "<pre>"; print_r($soapClient); echo "</pre>";
        $soapparams = array(
            'emailAddress' => $params['emailaddress'] //"sidiq.wahyudi@gmail.com"
            , 'oldPassword' => $this->encrypt_password($params['oldpassword']) //$this->encrypt_password("SIDIQW1509")
            , 'newPassword' => $this->encrypt_password($params['newpassword']) //$this->encrypt_password("password")
        );
        try {
            $results = $soapClient->__call("ChangePassword", array($soapparams));
        } catch (SoapFault $fault) {
            //echo "<pre>"; print_r($fault); echo "</pre>";
            return 0;
        }
        //echo "<pre>"; @print_r($results); echo "</pre>";
        if ($results->statusCode == '1111') {
            return 1;
        } else {
            return 0;
        }
    }

    function ssc_resetpassword($params) {
        $soapClient = new SoapClient("http://10.0.40.103:7001/SDP_CMS_SSC_Core/ProxyService/PS_ForgotPassword_v_1.0?WSDL");
        //echo "<pre>"; print_r($soapClient); echo "</pre>";
        $soapparams = array(
            'emailAddress' => $params['emailaddress'] //"sidiq.wahyudi@gmail.com"
        );
        try {
            $results = $soapClient->__call("ForgotPassword", array($soapparams));
        } catch (SoapFault $fault) {
            //echo "<pre>"; print_r($fault);echo "</pre>";
            return 0;
        }
        //echo "<pre>"; print_r($results); echo "</pre>";
        return 1;
    }

    function ssc_signin($params) {
        $soapClient = new SoapClient("http://10.0.40.103:7001/SDP_CMS_SSC_Core/ProxyService/PS_LoginUser_v_1.0?WSDL");
        //echo "<pre>"; print_r($soapClient); echo "</pre>";
        $soapparams = array(
            'emailAddress' => $params['emailaddress'] //"sidiq.wahyudi@gmail.com"
            , 'password' => $this->encrypt_password($params['password']) //$this->encrypt_password("password")
        );
        try {
            $results = $soapClient->__call("LoginUser", array($soapparams));
        } catch (SoapFault $fault) {
            //echo "<pre>"; print_r($fault); echo "</pre>";
            return 0;
        }
        //echo "<pre>"; @print_r($results); echo "</pre>";
        if ($results->statusCode == '2411') {
            return 1;
        } else {
            return 0;
        }
    }

    function ssc_updateuserinfo($params) {
        $soapClient = new SoapClient("http://10.0.40.103:7001/SDP_CMS_SSC_Core/ProxyService/PS_UpdateUser_v_1.0?WSDL");
        //echo "<pre>"; print_r($soapClient); echo "</pre>";
        $soapparams = array(
            'name' => $params['name'] //"sidiq.wahyudi@gmail.com"
            , 'dateOfBirth' => $params['dateofbirth'] //"01/07/1977"
            , 'emailAddress' => $params['emailaddress'] //"sidiq.wahyudi@gmail.com"
        );
        try {
            $results = $soapClient->__call("UpdateUser", array($soapparams));
        } catch (SoapFault $fault) {
            echo "<pre>";
            print_r($fault);
            echo "</pre>";
            return 0;
        }
        echo "<pre>";
        @print_r($results);
        echo "<pre>";
        return 1;
    }

    function ssc_retrieveuserinfo($params) {
        $soapClient = new SoapClient("http://10.0.40.103:7001/SDP_CMS_SSC_Core/ProxyService/PS_RetrieveUser_v_1.0?WSDL");
        //echo "<pre>"; print_r($soapClient); echo "</pre>";
        $soapparams = array(
            'emailAddress' => $params['emailaddress'] //"sidiq.wahyudi@gmail.com"
        );
        try {
            $results = $soapClient->__call("RetrieveUser", array($soapparams));
        } catch (SoapFault $fault) {
            //echo "<pre>"; print_r($fault); echo "</pre>";
            return 0;
        }
        echo "<pre>";
        @print_r($results);
        echo "<pre>";
        return 1;
    }

    function encrypt_password($password) {
        $id = 9;
        $salt = substr(md5($id), 0, 2);
        $encrpassw = md5($salt . $password) . ':' . $salt;
        return $encrpassw;
    }

    function ssc_getaccountinfo($params) {
        $soapClient = new SoapClient("http://10.0.40.103:7001/SDP_CMS_SSC_Core/ProxyService/PS_GetBillingAccountInfo_v_1.0?WSDL");
        //echo "<pre>"; print_r($soapClient); echo "</pre>";
        $soapparams = array(
            'emailAddress' => $params['emailaddress'] //"uatcms3@gmail.com"
        );
        try {
            $results = $soapClient->__call("GetBillingAccountInfo", array($soapparams));
        } catch (SoapFault $fault) {
            //echo "<pre>"; print_r($fault); echo "</pre>";
            return null;
        }
        //echo "<pre>"; @print_r($results);echo "</pre>";
        if ($results->statusCode == '1111') {
            $accounts = array();
            $accounts['types'] = $results->type;
            $accounts['productnumbers'] = $results->productNumber;
            $accounts['productattributes'] = $results->productAttribute;
            return $accounts;
        } else {
            return null;
        }
        /*
          stdClass Object
          (
          [statusCode] => 1111
          [statusDescription] => success
          [type] => Array
          (
          [0] => flexi
          [1] => speedy
          [2] => speedy
          [3] => flexi
          [4] => speedy
          [5] => flexi
          [6] => flexi
          [7] => flexi
          [8] => flexi
          [9] => flexi
          [10] => flexi
          [11] => flexi
          [12] => speedy
          [13] => speedy
          [14] => flexi
          )

          [productNumber] => Array
          (
          [0] => 02182596718
          [1] => 9007214295
          [2] => 122604210649
          [3] => 02170302671
          [4] => 4856600006
          [5] => 02170312460
          [6] => 02137761707
          [7] => 02170701800
          [8] => 02137366822
          [9] => 02128032542
          [10] => 02137977870
          [11] => 02134265070
          [12] => 162501203061
          [13] => 122120203628
          [14] => 02127465534
          )

          [productAttribute] => Array
          (
          [0] => postpaid
          [1] => prepaid
          [2] => postpaid
          [3] => postpaid
          [4] => prepaid
          [5] => postpaid
          [6] => prepaid
          [7] => postpaid
          [8] => prepaid
          [9] => postpaid
          [10] => prepaid
          [11] => prepaid
          [12] => postpaid
          [13] => postpaid
          [14] => postpaid
          )

          [accountStatus] => Array
          (
          [0] => active
          [1] => active
          [2] => active
          [3] => active
          [4] => active
          [5] => active
          [6] => active
          [7] => inactive
          [8] => active
          [9] => active
          [10] => active
          [11] => active
          [12] => active
          [13] => active
          [14] => active
          )

          )
         */
    }

    function getsession() {
        $wsse_header = new WsseAuthHeader($this->_userid, $this->_password);
        $soapClient = new SoapClient("http://10.0.40.101:8001/session_manager/SessionManager?WSDL", array('trace' => TRUE));
        $soapClient->__setSoapHeaders(array($wsse_header));
        //echo "<pre>"; print_r($soapClient); echo "</pre>";
        try {
            //$results = $soapClient->__call("ChangePassword", array($soapparams));
            $results = $soapClient->getSession();
            $this->_sessionid = $results->getSessionReturn;
        } catch (SoapFault $fault) {
            //echo "<pre>FAULT :\n"; print_r($fault);echo "</pre>";
        }
        //echo "<pre>RESULT :\n"; @print_r($results); print "REQUEST :\n" . htmlspecialchars($soapClient->__getLastRequest()) . "\n"; print "RESPONS :\n" . htmlspecialchars($soapClient->__getLastResponse()) . "\n"; echo "</pre>";
        return $this->_sessionid;
    }

    /*
     * INPUT
     * $params['addresses']
     * $params['sendername']
     * $params['charging-description']
     * $params['charging-amount']
     * $params['charging-code']
     * $params['message']
     */

    function sendsms($params) {
        $soapClient = new SoapClient("http://10.0.40.101:8001/parlayx21/sms/SendSms?WSDL", array('trace' => TRUE));
        $wsse_header = new WsseAuthHeader($this->_userid, $this->_password);
        $sess_header = new WsseSessHeader($this->_sessionid);
        $soapClient->__setSoapHeaders(array($wsse_header, $sess_header));
        //echo "<pre>"; print_r($soapClient); echo "</pre>";
        $result = 1;
        $soapparams = array(
            'addresses' => $params['addresses'] //"tel:02129224444"
            , 'senderName' => $params['sendername'] //"1441"
            , 'charging' => array(
                'description' => $params['charging-description'] //"Tagihan 5000"
                , 'currency' => "IDR"
                , 'amount' => $params['charging-amount'] //5000
                , 'code' => $params['charging-code'] //"SDPCHG005"
            )
            , 'message' => $params['message'] //"Anda dikenakan tagihan Rp. 5000,- untuk testing konten, webstore TSDC, --SDQ-- #AwasLuKas :-) "
        );
        try {
            $results = $soapClient->__call("sendSms", array($soapparams));
        } catch (SoapFault $fault) {
            echo "<pre>FAULT :\n";
            print_r($fault);
            echo "</pre>";
            $result = 0;
        }
        echo "<pre>RESULT :\n";
        @print_r($results);
        print "REQUEST :\n" . htmlspecialchars($soapClient->__getLastRequest()) . "\n";
        print "RESPONS :\n" . htmlspecialchars($soapClient->__getLastResponse()) . "\n";
        echo "</pre>";
        return $result;
    }

    /*
     * INPUT
     * $params['enduseridentifier']
     * $params['charging-description']
     * $params['charging-currency']
     * $params['charging-amount']
     * $params['charging-code']
     * $params['referencecode']
     */

    function chargeaccount($params) {
        $soapClient = new SoapClient("http://10.0.40.101:8001/parlayx30/payment/AmountCharging?WSDL", array('trace' => TRUE));
        $wsse_header = new WsseAuthHeader($this->_userid, $this->_password);
        $sess_header = new WsseSessHeader($this->_sessionid);
        $xparam_header = new WsseXparamHeader();
        $soapClient->__setSoapHeaders(array($wsse_header, $sess_header, $xparam_header));
        //echo "<pre>"; print_r($soapClient); echo "</pre>";
        $soapparams = array(
            'endUserIdentifier' => $params['enduseridentifier'] //"tel:02127211141"
            , 'charge' => array(
                'description' => $params['charging-description'] //"Tagihan 500"
                , 'currency' => "IDR"
                , 'amount' => $params['charging-amount'] //500
                , 'code' => $params['charging-code'] //"SDPCHG005"
            )
            , 'referenceCode' => $params['referencecode'] //"testing-SDQ"
        );
        try {
            $results = $soapClient->__call("chargeAmount", array($soapparams));
        } catch (SoapFault $fault) {
            //echo "<pre>FAULT :\n"; print_r($fault);echo "</pre>";
            return 0;
        }
        echo "<pre>RESULT :\n";
        print_r($results);
        print "REQUEST :\n" . htmlspecialchars($soapClient->__getLastRequest()) . "\n";
        print "RESPONS :\n" . htmlspecialchars($soapClient->__getLastResponse()) . "\n";
        echo "</pre>";
        return 1;
    }

}

?>