<?php
class Thirdpartyapi extends CI_Controller {
    private
            $thirdpartyconfigs= array();
    function  __construct() {
        parent::__construct();
        $this->thirdpartyconfigs= array(
                'fulltrek.com'=> array(
                        'host'=> "",
                        'forwardingurl'=> ""
                ),
                'development'=> array (
                        'host'=> "",
                        'forwardingurl'=> ""
                )
        );
    }
    function index() {
        echo '';
    }

    //return 1 success
    function unsubscribe() {
        $this->speedyunsubscontent();
    }
    function speedyunsubscontent() {
        $contentresults= array();
        $subsresult= array();
        $contentparams= array();
        $trxparams= array();
        $result= 0;

        //GET PARAMETER
        $uriparams = $this->uri->uri_to_assoc(3);
        $subscriptionid= @ $uriparams['orderid'];
        if (!is_numeric($subscriptionid)) {
            $subscriptionid= 0;
        }
        //GET SUBSCRIPTON INFORMATION
        $subsresult= $this->mmasterdata->getsubscriptiontrxspeedy($subscriptionid);
        //echo "SUBS RESULT<pre>";
        //print_r($subsresult);
        //echo "</pre>";

        if (count($subsresult)==0) {
            $this->page['iserror']= true;
            $this->page['error_mesg']= "Data berlangganan content tidak ditemukan.";
            $this->page['iserror']= true;
        }
        if ($subsresult['SUBSCRIPTIONSTATUS']!='S') {
            $this->page['iserror']= true;
            $this->page['error_mesg']= "Data berlangganan content tidak ditemukan.";
            ;
        }
        if ($this->page['iserror']==false) {
            $contentresults= $this->mmasterdata->getsubscribedcontentdetail(array('contentid'=>$subsresult['CONTENTID']));
            if (count($contentresults)<1) {
                $this->page['iserror']= true;
                $this->page['error_mesg']= "Data konten tidak ditemukan.";
            }
            $contentresult= $contentresults[0];
        }
        //echo "CONTENT RESULT<pre>";
        //print_r($contentresult);
        //echo "</pre>";
        $trxparams['SUBSCRIPTIONSTATUS']= "NS";
        $trxparams['RESPONSECODE']= "00";
        $trxparams['DELIVERYCHANNEL']= 'OLSTORE-SPEEDY';
        $trxparams['UNSUBSHOSTIP']= $this->input->ip_address();
        $trxparams['UNSUBSDATE']= date('Y-m-d H:i:s');
        $trxparams['SUBSCRIPTIONID']= $subsresult['SUBSCRIPTIONID'];
        if ($this->page['iserror']==false) {
            $contentparams['CONTENTID']= $contentresult['CONTENTID'];
            $contentparams['FORWARDINGURL']= $contentresult['FORWARDINGURL'];
            $trxparams['RESPONSECODE']= '00';
            $trxparams['DELIVERYCHANNEL']= 'OLSTORE-SPEEDY';
            $trxparams['UNSUBSDATE']= date('Y-m-d H:i:s');
            $trxparams['SPEEDYACCOUNT']= $subsresult['SPEEDYACCOUNT'];
            $trxparams['EMAILACCOUNT']= $subsresult['EMAILACCOUNT'];
            $trxparams['SUBSCRIPTIONID']= $subsresult['SUBSCRIPTIONID'];
            $trxparams['ACTIVATIONID']= $subsresult['ACTIVATIONID']; //$activation_id;
            $trxparams['HOSTIP']= $this->input->ip_address();
            //echo "<pre>";
            //print_r($trxparams);
            //print_r($contentparams);
            //echo "</pre>";
            $result= $this->mpaymentgateway->forwardunsubscriptiontocp($contentparams,$trxparams,"unsubscribe");
            //echo "<br />RESULT: ".$result;
            if ($result==1) {  //UPDATE DATABASE
                //echo "<br />TRXPARAMS<br /><pre>";
                //print_r($trxparams);
                //echo "</pre>";
                $this->page['error_mesg']= "Berhenti berlangganan telah sukses, Anda tidak dikenakan tagihan berlangganan content pada tagihan Speedy bulan depan.";
                $this->mmasterdata->stopsubscriptiontrxspeedy($trxparams);
            } else { //UPDATE DATABASE AND SET RESPONSE CODE IS ERROR
                $trxparams['SUBSCRIPTIONSTATUS']= $subsresult['SUBSCRIPTIONSTATUS'];
                $trxparams['RESPONSECODE']= "22";
                $this->mmasterdata->updatesubscriptiontrxspeedy($trxparams);
                $this->page['iserror']= true;
                $this->page['error_mesg']= "Berhenti berlangganan gagal dilakukan karena error atau kesalahan di sisi Mitra, silahkan kontak ke call center Telkom (147) dan informasikan nama paket content dan informasi error di atas.";
            }

        }
        if ($this->page['iserror'])
            echo "0";
        else
            echo "1";
    }
    function speedycheckstatus () {
        $uriparams = $this->uri->uri_to_assoc(3);
        $result= "0";
        if (is_numeric($uriparams['speedyaccount']))
            $result= $this->mpaymentgateway->speedyvalidation($uriparams['speedyaccount']); //validation speedy account
        if ($result=="1")
            echo "1";
        else
            echo "0";
    }
    
    function speedygetaccountbyipaddress() {
        $uriparams = $this->uri->uri_to_assoc(3);
        $result= "0";
        
        if (isset($uriparams['hostip'])) {
            $result = $this->mpaymentgateway->speedygetaccountbyipaddress($uriparams['hostip']);
        }
        echo $result;
    }
}
