<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class mpaymentgateway extends CI_Model {

    function _construct() {
        parent::_construct();
        $this->ci = & get_instance();
    }

    /**
     * return value:
     *  1 : speedy status dari OS3 OK/VALID/ENABLE
     *  0 : speedy status dari OS3 DISABLE
     * -1 : network problem when connect to remote server
     */
    function speedyvalidation($speedyaccount) {
        $status = 0;
        $soapclient = new SoapClient(APPPATH . $this->config->item('remote_speedyvalidation'), array('login' => $this->config->item('wsdluser'), 'password' => $this->config->item('wsdlpassword')));
        $result = $soapclient->OS3_Radius_Status($speedyaccount);
        //echo "<pre>";
        //print_r($result);
        //echo "</pre>";
        if (count($result) > 0) {
            $status = (strtoupper(@$result['OS3Status']) == 'ENABLED') || (strtoupper(@$result['OS3Status']) == 'ENABLE') ? 1 : 0;
            if ($status == 1) {
                $status = (strtoupper(@$result['RadiusStatus']) == 'ENABLED') || (strtoupper(@$result['RadiusStatus']) == 'ENABLE') || (strtoupper(@$result['RadiusStatus']) == '') ? 1 : 0;
            } else {
                $status = 0;
            }
        } else {
            $status = -1; //ERROR it was -1
        }
        log_message('log', '--           SPEEDY VALIDATION');
        log_message('log', 'userspeedy      : ' . $speedyaccount);
        log_message('log', 'os3-status      : ' . @$result['OS3Status']);
        log_message('log', 'radius-status   : ' . @$result['RadiusStatus']);
        log_message('log', 'from host       : ' . $this->input->ip_address());
        log_message('log', 'result          : ' . $status);
        log_message('log', '=========================');

        return $status;
    }

    /**
     * return value:
     * 1 : speedy account & mdn is valid
     * 0 : speedy account & mdn is NOT valid
     */
    function speedyipvalidation($speedyaccount, $ipaddress) {
        $status = 0;
        $speedyaccountowner = "";
	 
	 /* No Speedy 122604202121   dan MDN 02134834494 */
        if ($speedyaccount == '122604202121' && $ipaddress == '127.0.0.1') {
            log_message('log', '--           IPADDRESS-SPEEDY VALIDATION');
            log_message('log', 'userspeedy      : ' . $speedyaccount);
            log_message('log', 'status code     : DUMMY TEST');
            log_message('log', 'from host       : ' . $ipaddress);
            log_message('log', 'ip address owner: DUMMY TEST');
            log_message('log', 'result          : 1');
            log_message('log', '=========================');
            return 1;
        } else {
            $referer = 'http://billing.telkomspeedy.com';
            //$get['ip'] = "110.137.45.150";
            $get['ip'] = $ipaddress;
            $urlauthenticationserver = $this->config->item('remote_httpget_speedyipvalidation') . "?ip=" . $get['ip'];
            $this->load->helper('curl');
            $ret = get_curl_get($urlauthenticationserver, $referer, 60);
            if ($ret['info'] == 200) {
                if (strlen($ret['output']) > 12) {
                    $speedyaccountowner = substr($ret['output'], 0, 12);
                    if ($speedyaccount == $speedyaccountowner)
                        $status = 1;
                    //echo $ret['output']; //111218105254@telkom.net|enable|Speedy Online|110.137.45.150|2011-06-19 23:27:23
                }
            }
            log_message('log', '--           IPADDRESS-SPEEDY VALIDATION');
            log_message('log', 'userspeedy      : ' . $speedyaccount);
            log_message('log', 'status code     : ' . @$ret['output']);
            log_message('log', 'from host       : ' . $ipaddress);
            log_message('log', 'ip address owner: ' . $speedyaccountowner);
            log_message('log', 'result          : ' . $status);
            log_message('log', '=========================');
        }
        return $status;
    }

    function speedygetaccountbyipaddress($ipaddress) {
        $status = 0;
        $speedyaccountowner = "0";
        $referer = 'http://billing.telkomspeedy.com';
        //$get['ip'] = "110.137.45.150";
        $get['ip'] = $ipaddress;
        $urlauthenticationserver = $this->config->item('remote_httpget_speedyipvalidation') . "?ip=" . $get['ip'];
        //echo $urlauthenticationserver; exit;
        $this->load->helper('curl');
        $ret = get_curl_get($urlauthenticationserver, $referer, 60);
        if ($ret['info'] == 200) {
            if (strlen($ret['output']) > 12) {
                $speedyaccountowner = substr($ret['output'], 0, 12);
            }
            //echo $ret['output']; //111218105254@telkom.net|enable|Speedy Online|110.137.45.150|2011-06-19 23:27:23
            //exit;
        }
        log_message('log', '--           IPADDRESS-SPEEDY 3RD PARTY API');
        log_message('log', 'status code     : ' . @$ret['output']);
        log_message('log', 'from host       : ' . $ipaddress);
        log_message('log', 'ip address owner: ' . $speedyaccountowner);
        log_message('log', '=========================');
        return $speedyaccountowner;
    }

    /**
     * return value:
     * 1 : speedy account & mdn is valid
     * 0 : speedy account & mdn is NOT valid
     */
    function speedymdnvalidation($speedyaccount, $mdnnumber) {
        $status = 0;
        $soapclient = new SoapClient(APPPATH . $this->config->item('remote_speedymdnvalidation'), array('login' => $this->config->item('wsdluser_checkmdn'), 'password' => $this->config->item('wsdlpassword_checkmdn')));
        //print_r($soapclient);
        $result = $soapclient->validasiSpeedyMDN($mdnnumber, $speedyaccount);
       // $result = $soapclient->validasiSpeedyMDN("02134834494","122604202121");
        //echo "<pre>";
        //print_r($result);
        //echo "</pre>";
        if (count($result) > 0) {
            $status = (strtoupper(@$result['statusCode']) == 'T') ? 1 : 0;
        } else {
            $status = 0; //ERROR it was -1
        }

        log_message('log', '--           MDN-SPEEDY VALIDATION');
        log_message('log', 'userspeedy      : ' . $speedyaccount);
        log_message('log', 'status code     : ' . @$result['statusCode']);
        log_message('log', 'from host       : ' . $this->input->ip_address());
        log_message('log', 'result          : ' . $status);
        log_message('log', '=========================');
        return $status;
    }

    /**
     * return value:
     *  1 : speedy account / password is OK/VALID
     *  0 : speedy account / password is not valid
     * -1 : network problem to connect to remote host or radius
     * -2 : speedy account has no password
     * -9 : configuration is not valid
     */
    function speedyauthentication($speedyaccount, $speedypassword) {
        $referer = 'http://billing.telkomspeedy.com';
        $urlauthenticationserver = "http://telkomspeedy.com";
        $post['username_lama'] = $speedyaccount;
        $post['password_lama'] = $speedypassword;
        $this->ci->load->helper('curl');
        $ret = get_curl($urlauthenticationserver, $referer, $post, 60);
        if ($ret['info'] == 200) {
            if (strlen($ret['output']) > 2 OR $ret['output'] === '') {
                return '-9';
            }
            return $ret['output'];
        }
        return '0';
    }

    /**
     * return value:
     *  1 : telkom voucher status OK/VALID/ENABLE
     *  0 : telkom voucher status NOK/INVALID
     */
    function telkomvouchervalidation($voucherserial, $vouchercode) {
        $user = "OS3";
        $pass = "telkomOS3";
        $soap_config['wsdl'] = APPPATH . $this->ci->config->item('remote__telkomvouchervalidation');
        $this->ci->load->library('nusoapclient', $soap_config);
        $this->ci->nusoapclient->soapclient->setCredentials($user, $pass);
        $proxy = $this->ci->nusoapclient->soapclient->getProxy();
        $result = $proxy->flexi_validasiVoucher($voucherserial, $vouchercode);
        print_r($result);
        if (count($$result) > 0) {
            return 1;
        } else {
            return -1;
        }
        return 0;
    }

    /**
     * return value:
     *  1 : telkom voucher transaction is OK/VALID/ENABLE
     *  0 : telkom voucher transaction is NOK
     */
    function telkomvouchertrxpayment($trxid, $trxdate, $trxtype, $voucherserial, $vouchercode) {
        $user = "OS3";
        $pass = "telkomOS3";
        $soap_config['wsdl'] = APPPATH . $this->config->item('remote_telkomvouchertrxpayment');
        $this->ci->load->library('nusoapclient', $soap_config);
        $this->ci->nusoapclient->soapclient->setCredentials($user, $pass);
        $proxy = $this->ci->nusoapclient->soapclient->getProxy();
        $result = $proxy->OS3_Radius_Status($voucherserial, $vouchercode);
        print_r($result);
        if (count($result) > 0) {
            return 1;
        } else {
            return -1;
        }
        return 0;
    }

    function forwardtransactiontocp($contentparams= null, $trxparams= null, $trxtype="subscribe") {
        /*
          //TRX PARAMS
          ['CONTENTID']
          ['SPEEDYACCOUNT']
          ['MDNNUMBER']
          ['HOSTIP']
          ['EMAILACCOUNT']
          ['SUBSCRIPTIONMONTH']
          ['SUBSCRIPTIONSTATUS']
          ['RESPONSECODE']
          ['ACTIVATIONID']
          ['SUBSCRIPTIONID']
          ['EMAILACCOUNT']
          ['SUBSCRIPTIONMONTH']
          ['USERIDCONTENT']
          ['PASSWDCONTENT']
          ['PARAM1']
          ['PARAM2']

          //CONTENT PARAMS
          [CONTENTID] => 664
          [NAME] => SpeedyEye Emas
          [SHORTDESCRIPT] => 4 Kamera, 1 GB Storage
          [LONGDESCRIPT] => 4 Kamera, 1 GB Storage
          [GROUPNAME] => SpeedyEye
          [CONTENTCATEGORY] => 4
          [CONTENTPROVIDER] => 7
          [CREATECONTENTACCOUNT] => 0
          [SUBSCRIPTIONTYPE] => CBC
          [CIRCLERATINGPERIOD] => 0
          [FORWARDINGURL] => http://speedyeye.telkomspeedy.com/dtplaceorder.php
          [REDIRECTURL] => http://speedyeye.telkomspeedy.com/dtactive.php?
          [ISDISCOUNT] => 0
          [MAXORDERFREQPERMONTH] => 0
          [MAXORDERCHARGEPERMONTH] => 0
          [MINSUBSCRIPTIONMONTH] => 0
          [PARAM1] =>
          [PARAM2] =>
         */

        $result = 0;
        $this->load->helper('curl');
        $url = $contentparams['FORWARDINGURL'] ? $contentparams['FORWARDINGURL'] : site_url() . "/validation/";
        //$url= false ? $contentparams['FORWARDINGURL'] : site_url()."validation/";
        //echo $url;
        $referer = 'ecs-referer';
        $post['dummy_return_value'] = 1;
        //DOCS: API subscription
        $post['type'] = $trxtype; //'subscribe';
        $post['id_content'] = $contentparams['CONTENTID']; //$data_content->fields['ID'];
        $post['langganan'] = $trxparams['SUBSCRIPTIONMONTH']; //$this->input->post('langganan');
        $post['userspeedy'] = $trxparams['SPEEDYACCOUNT']; //base64_decode ($this->input->post('userspeedy'));
        $post['email'] = $trxparams['EMAILACCOUNT']; //base64_decode ($this->input->post('email'));
        $post['id_pemesanan'] = $trxparams['SUBSCRIPTIONID']; //$result;
        $post['activation_id'] = $trxparams['ACTIVATIONID']; //$activation_id;
        $post['activation_link'] = site_url('/speedytrxactivation/index/' . $trxparams['ACTIVATIONID']);
        $post['username'] = '';
        $post['password'] = '';
        if ($contentparams['CREATECONTENTACCOUNT']) {
            $post['username'] = $trxparams['USERIDCONTENT'];
            $post['password'] = $trxparams['PASSWDCONTENT'];
        }
        //DOCS: end of API subscription
        $ret = get_curl($url, $referer, $post, 30);
        //print_r($ret);
        //debugging
        //*
        log_message('log', '--           SUBSCRIPTION');
        log_message('log', 'type            : ' . $post['type']);
        log_message('log', 'id_content      : ' . $post['id_content']);
        log_message('log', 'langganan       : ' . $post['langganan']);
        log_message('log', 'userspeedy      : ' . $post['userspeedy']);
        log_message('log', 'email           : ' . $post['email']);
        log_message('log', 'id_pemesanan    : ' . $post['id_pemesanan']);
        log_message('log', 'activation_id   : ' . $post['activation_id']);
        log_message('log', 'activation_link : ' . $post['activation_link']);
        log_message('log', 'username        : ' . $post['username']);
        log_message('log', 'password        : ');
        log_message('log', 'from host       : ' . $this->input->ip_address());
        log_message('log', 'url             : ' . $url);
        log_message('log', 'result          : ' . $ret['output']);
        log_message('log', '=========================');
        //*/
        //end of debugging
        $result = "0"; //0 error 1 sukses
        if ($ret['info'] == 200) {
            $result = $ret['output'];
            //echo "<br/>PLACE ORDER TO CP: ".$ret['output'];
        }
        return $result;
    }

    function forwardunsubscriptiontocp($contentparams= null, $trxparams= null, $trxtype="unsubscribe") {
        $result = 0;
        $this->load->helper('curl');
        $url = $contentparams['FORWARDINGURL'] ? $contentparams['FORWARDINGURL'] : site_url() . "validation/";
        //$url= false ? $contentparams['FORWARDINGURL'] : site_url()."validation/";
        //echo $url;
        $referer = 'ecs-referer';
        $post['dummy_return_value'] = 1;
        //DOCS: API subscription
        $post['type'] = 'unsubscribe';
        $post['id_content'] = $contentparams['CONTENTID'];
        $post['userspeedy'] = $trxparams['SPEEDYACCOUNT'];
        $post['email'] = $trxparams['EMAILACCOUNT'];
        $post['id_pemesanan'] = $trxparams['SUBSCRIPTIONID'];
        $post['activation_id'] = $trxparams['ACTIVATIONID']; //$activation_id;
        //DOCS: end of API subscription
        $ret = get_curl($url, $referer, $post, 30);
        //print_r($ret);
        //debugging
        //*
        log_message('log', '--           UNSUBSCRIPTION');
        log_message('log', 'type            : ' . $post['type']);
        log_message('log', 'id_content      : ' . $post['id_content']);
        log_message('log', 'id_pemesanan    : ' . $post['id_pemesanan']);
        log_message('log', 'activationid    : ' . $post['activation_id']);
        log_message('log', 'userspeedy      : ' . $post['userspeedy']);
        log_message('log', 'email           : ' . $post['email']);
        log_message('log', 'from host       : ' . $this->input->ip_address());
        log_message('log', 'url             : ' . $url);
        log_message('log', 'result          : ' . $ret['output']);
        log_message('log', '===========================');
        //end of debugging
        $result = "0"; //0 error 1 sukses
        if ($ret['info'] == 200) {
            $result = $ret['output'];
            //echo "<br/>PLACE ORDER TO CP: ".$ret['output'];
        }
        return $result;
    }

    function forwardactivationtocp($contentparams= null, $trxparams= null, $trxtype="aktivasi") {
        $result = "0"; //0 error 1 sukses 2 sudah pernah diaktifkan
        $this->load->helper('curl');
        $url = $contentparams['FORWARDINGURL'] ? $contentparams['FORWARDINGURL'] : site_url() . "validation/";
        $referer = 'ecs-referer';
        //DOCS: API activation
        $post['type'] = $trxtype;
        $post['id_content'] = $contentparams['CONTENTID'];
        $post['userspeedy'] = $trxparams['SPEEDYACCOUNT'];
        $post['activationkey'] = $trxparams['ACTIVATIONID'];
        $post['id_pemesanan'] = $trxparams['SUBSCRIPTIONID'];
        $ret = get_curl($url, $referer, $post, 60);

        //*
        log_message('log', '--           ACTIVATION');
        log_message('log', 'type            : ' . $post['type']);
        log_message('log', 'id_content      : ' . $post['id_content']);
        log_message('log', 'userspeedy      : ' . $post['userspeedy']);
        log_message('log', 'id_pemesanan    : ' . $post['id_pemesanan']);
        log_message('log', 'activationkey   : ' . $post['activationkey']);
        log_message('log', 'from host      : ' . $this->input->ip_address());
        log_message('log', 'url             : ' . $url);
        log_message('log', 'result          : ' . $ret['output']);
        log_message('log', '===========================');
        //*/
        $result = "0"; //0 error 1 sukses
        if ($ret['info'] == 200) {
            $result = $ret['output'];
            //echo "<br/>PLACE ORDER TO CP: ".$ret['output'];
        }
        return $result;
    }

}

?>
