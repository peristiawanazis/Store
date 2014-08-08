<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class welcome extends CI_Controller {
    public
            $page= array();
    function __construct() {
        parent::__construct();
        $this->getparam();
        $this->checksignstatus();
    }
    function index() {
        $this->load->view('store/welcome',$this->page);
    }
    function provider() {
        $this->load->view('store/provider',$this->page);
    }
    function getparam() {
        @session_start();
        $this->page['header']['application']['siteurl']= site_url();
        $this->page['header']['application']['baseurl']= base_url();
        $this->page['header']['application']['theme']= 'default';
        $this->page['iserror']= false;
        $this->page['error_mesg']= "";
        $this->page['islogin']= false;
        $this->page['sessions']= array();
        if (!isset($this->page['header']['application']['baseurl'])) //application configuration
            $this->page['header']['application']= array(
                    'baseurl'=> base_url(),
                    'siteurl'=> site_url(),
                    'theme'=> 'default'
            );//skin css -> default
        $this->urisegments= $this->uri->uri_to_assoc($this->config->item('uriparam_index'));
        if (count($this->urisegments)>0) {
            if ((isset($this->urisegments['ajax']))&&(@$this->urisegments['ajax']=='true')) {
                $this->urisegments['ajax']?$this->page['navigation']['ajax']= true:$this->page['navigation']['ajax']= false;
            }
            if (isset($this->urisegments['page']))
                is_numeric(@$this->urisegments['page'])?null:$this->urisegments['page']= 0;
            else
                $this->urisegments['page']= 0;
            if (isset($this->urisegments['widget']))
                (@$this->urisegments['widget']=='true')?$this->dgparams['pageconfig']['widget']= true:null;
            else
                $this->dgparams['pageconfig']['widget']= true;
            $this->page['navigation']['urisegments']= $this->urisegments;
        } else {
            $this->page['navigation']['urisegments']['page']= 0;
        }
        //URI PARAM
        //f_CONTENTCATEGORY
        //echo "<pre>";
        //print_r($this->page);
        //echo "</pre>";
    }
    function signin() {
        $trxparams['SPEEDYACCOUNT']= $this->input->post('f_SPEEDYACCOUNT');
        $trxparams['MDNNUMBER']= $this->input->post('f_PHONEHOME');
        $trxparams['CAPTCHA']= $this->input->post('f_CAPTCHA');
        //print_r($this->page);
        //print_r($_POST);
        if (@$_SESSION['security_code']!=$this->input->post('f_CAPTCHA')) {
            //echo $_SESSION['security_code'];
            $this->page['iserror']= true;
            $this->page['error_mesg']= "Kode Captcha yang Anda masukkan salah.";
        }
        //validation
        if (!$this->page['iserror']) {
            if (is_numeric($trxparams['SPEEDYACCOUNT']) && is_numeric($trxparams['MDNNUMBER'])) {
                $result= $this->mpaymentgateway->speedymdnvalidation($trxparams['SPEEDYACCOUNT'], $trxparams['MDNNUMBER']);
                if ($result==1) {
                    $rawsession= "SPEEDY||".$trxparams['SPEEDYACCOUNT']."||".$trxparams['MDNNUMBER']."||".date('Y-m-d H:i:s');
                    $this->page['sessions']= explode('||',$rawsession);
                    $rawsession= $this->encrypt->encode($rawsession);
                    $_SESSION['store_sesion_code']= $rawsession;
                    $this->page['islogin']= true;
                } else {
                    $this->page['iserror']= true;
                    $this->page['error_mesg']= "Otentifikasi <b>Nomor Speedy</b> dan <b>Nomor Telepon Rumah</b> salah,
                    pastikan Anda memasukkan dengan benar, masukkan nomor telepon Anda dengan
                    format telepon sebagai berikut <b>0218038213</b>.";
                }
                //print_r($trxparams);
            } else {
                $this->page['iserror']= true;
                $this->page['error_mesg']= "Periksa kembali data <b>Nomor Speedy</b> dan <b>Nomor Telepon</b> yang Anda masukkan, masukkan tanpa menggunakan karakter spasi atau alphabet lainnya.";
            }
        }
        //echo "<pre>";
        //print_r($this->page);
        //echo "</pre>";
        $this->load->view('store/include/div-speedy-signin-box',$this->page);
    }
    function signout() {
        session_destroy();
        $this->page['islogin']= false;
        $this->load->view('store/include/div-speedy-signin-box',$this->page);
    }
    function registration() {

    }
    function checksignstatus() {
        if (isset($_SESSION['store_sesion_code'])) {
            $rawsession= $this->encrypt->decode($_SESSION['store_sesion_code']);
            $this->page['sessions']= explode('||',$rawsession);
            if (count($this->page['sessions'])>=3)
                $this->page['islogin']= true;
            //print_r($this->page['sessions']);
        }
    }
}
?>