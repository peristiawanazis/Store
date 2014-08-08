<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class about extends CI_Controller {
    public
        $page= array();
    function __construct() {
        parent::__construct();
        $this->getparam();
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
        $this->page['pagetype']= "";
        if (!isset($this->page['header']['application']['baseurl'])) //application configuration
            $this->page['header']['application']= array(
                    'baseurl'=> base_url(),
                    'siteurl'=> site_url(),
                    'theme'=> 'default'
            );//skin css -> default
    }
    function index() {
    }
    function tos() {
        $this->page['pagetype']= "tos";
        $this->load->view('store/about',$this->page);
    }
    function disclaimer() {
        $this->page['pagetype']= "disclaimer";
        $this->load->view('store/about',$this->page);
    }
    function faq() {
        $this->page['pagetype']= "faq";
        $this->load->view('store/about',$this->page);
    }
}
?>