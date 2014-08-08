<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class migrate extends CI_Controller {

    public
    $page = array();

    function __construct() {
        parent::__construct();
        $this->getparam();
    }

    function index() {
        $this->speedycontentmigrate();
    }

    function getparam() {
        @session_start();
        $this->page['application']['siteurl'] = site_url();
        $this->page['application']['baseurl'] = base_url();
        $this->page['header']['application']['siteurl'] = site_url();
        $this->page['header']['application']['baseurl'] = base_url();
        $this->page['header']['application']['theme'] = 'default';
        $this->page['iserror'] = false;
        $this->page['error_mesg'] = "";
        $this->page['islogin'] = false;
        $this->page['sessions'] = array();
        $this->page['trxparams'] = array();
        $this->page['contentparams'] = array();
        if (!isset($this->page['header']['application']['baseurl'])) //application configuration
            $this->page['header']['application'] = array(
                'baseurl' => base_url(),
                'siteurl' => site_url(),
                'theme' => 'default'
            ); //skin css -> default
        $this->urisegments = $this->uri->uri_to_assoc($this->config->item('uriparam_index'));
        if (count($this->urisegments) > 0) {
            if (isset($this->urisegments['ajax'])) {
                $this->urisegments['ajax'] ? $this->page['application']['ajax'] = true : $this->page['application']['ajax'] = false;
            }
            if (isset($this->urisegments['page']))
                is_numeric($this->urisegments['page']) ? null : $this->urisegments['page'] = 0;
            else
                $this->urisegments['page'] = 0;
            if (isset($this->urisegments['widget']))
                ($this->urisegments['widget'] == 'true') ? $this->dgparams['pageconfig']['widget'] = true : null;
            $this->page['application']['urisegments'] = $this->urisegments;
        } else {
            $this->page['application']['urisegments']['page'] = 0;
        }
//URI PARAM
//f_CONTENTCATEGORY
//echo "<pre>";
//print_r($this->page);
//echo "</pre>";
    }

    function speedycontentmigrate() {
//get parameter

        $trxparams['REFSUBSCRIPTIONTYPE'] = "";
        $trxparams['CONTENTID'] = $this->input->post('id_content'); //content id baru
        $trxparams['REFCONTENTID'] = $this->input->post('prev_id_content'); //content id lama
        $trxparams['REFSUBSCRIPTION'] = $this->input->post('prev_id_pemesanan'); //id pemesanan lama
        $trxparams['TRXTYPE'] = $this->input->post('type'); //upgrade atau downgrade
        $trxparams['SPEEDYACCOUNT'] = $this->input->post('userspeedy'); //nomor speedy
        $trxparams['HOSTIP'] = $this->input->ip_address(); //ip address asal transaksi
        $trxparams['EMAILACCOUNT'] = $this->input->post('email'); //email pelanggan
        $trxparams['SUBSCRIPTIONMONTH'] = @$this->input->post('f_SUBSCRIPTIONMONTH'); //lama berlangganan
        $trxparams['TOS'] = @$this->input->post('f_TOS'); //setuju dengan TOS
        $trxparams['SUBSCRIPTIONSTATUS'] = 'NA'; //status berlangganan di set tidak aktif

        if (isset($this->urisegments['id_content']))
            $trxparams['CONTENTID'] = $this->urisegments['id_content']; //content id baru
        if (isset($this->urisegments['prev_id_content']))
            $trxparams['REFCONTENTID'] = $this->urisegments['prev_id_content']; //content id lama
        if (isset($this->urisegments['prev_id_pemesanan']))
            $trxparams['REFSUBSCRIPTION'] = $this->urisegments['prev_id_pemesanan']; //id pemesanan lama
        if (isset($this->urisegments['type']))
            $trxparams['TRXTYPE'] = $this->urisegments['type']; //upgrade atau downgrade
        if (isset($this->urisegments['userspeedy']))
            $trxparams['SPEEDYACCOUNT'] = $this->urisegments['userspeedy']; //nomor speedy
        if (isset($this->urisegments['email']))
            $trxparams['EMAILACCOUNT'] = $this->urisegments['email']; //email pelanggan

        if ($trxparams['SUBSCRIPTIONMONTH'] == '')
            $trxparams['SUBSCRIPTIONMONTH'] = 0;
        if ($trxparams['TRXTYPE'] == "upgrade")
            $trxparams['REFSUBSCRIPTIONTYPE'] = 'UG';
        else if ($trxparams['TRXTYPE'] == "downgrade")
            $trxparams['REFSUBSCRIPTIONTYPE'] = 'DG';
        $trxparams['RESPONSECODE'] = '00'; //00 OK, SUBSCRIPTION: 01 ERROR TELKOM, 02 ERROR CP, 03 ERROR POLICY INFRINGEMENT, 04 ERROR INTERNAL TELKOM
//ACTIVATION: 11 ERROR TELKOM, 12 ERROR CP, 13 ERROR POLICY INFRINGEMENT, 14 ERROR INTERNAL TELKOM
        $trxparams['ACTIVATIONID'] = '';
        $trxparams['SUBSCRIPTIONID'] = '';
        $trxparams['USERIDCONTENT'] = $this->input->post('f_USERIDCONTENT');
        $trxparams['PASSWDCONTENT'] = $this->input->post('f_PASSWDCONTENT');
        $trxparams['PARAM1'] = $this->input->post('f_PARAM1');
        $trxparams['PARAM2'] = $this->input->post('f_PARAM2');

//get previous subscription
//CONTENT CONTENTID,POSTPAIDSPEEDYACCOUNT SPEEDYACCOUNT,MDNACCOUNT MDNNUMBER,HOSTIP,EMAILACCOUNT,
//DURATION SUBSCRIPTIONMONTH,SUBSCRIPTIONSTATUS,RESPONSECODE,ACTIVATIONKEY ACTIVATIONID,SUBSCRIPTIONID,EMAILACCOUNT

        log_message('log', '--           MIGRATION SERVICE');
        log_message('log', 'userspeedy      : ' . $trxparams['SPEEDYACCOUNT']);
        log_message('log', 'email           : ' . $trxparams['EMAILACCOUNT']);
        log_message('log', 'hostip          : ' . $trxparams['HOSTIP']);
        log_message('log', 'trxtype         : ' . $trxparams['TRXTYPE']);
        log_message('log', 'contentid       : ' . $trxparams['CONTENTID']);
        log_message('log', 'prevcontentid   : ' . $trxparams['REFCONTENTID']);
        log_message('log', 'prevsubscription: ' . $trxparams['REFSUBSCRIPTION']);
        log_message('log', '=========================');
        if (is_numeric($trxparams['REFSUBSCRIPTION'])) {
            $reftrxparams = $this->mmasterdata->getsubscriptiontrxspeedy($trxparams['REFSUBSCRIPTION']);
            //echo "<pre>";
            //print_r($trxparams);
            //echo "</pre>";
            //echo "<pre>";
            //print_r($reftrxparams);
            //echo "</pre>";
            //exit;
            if (count($reftrxparams) > 0) {

                if (($reftrxparams['SUBSCRIPTIONSTATUS'] == "S") &&
                        (($trxparams['REFSUBSCRIPTIONTYPE'] == "UG") || ($trxparams['REFSUBSCRIPTIONTYPE'] == "DG")) &&
                        (@$reftrxparams['CONTENTID'] == $trxparams['REFCONTENTID']) &&
                        (@$reftrxparams['SPEEDYACCOUNT'] == $trxparams['SPEEDYACCOUNT'])) {
                    log_message('log', '--           PREVSUBS MIGRATION SERVICE');
                    log_message('log', 'refuserspeedy   : ' . $reftrxparams['SPEEDYACCOUNT']);
                    log_message('log', 'refsubsstatus   : ' . $reftrxparams['SUBSCRIPTIONSTATUS']);
                    log_message('log', 'refsubstype     : ' . $trxparams['REFSUBSCRIPTIONTYPE']);
                    log_message('log', 'refcontentid    : ' . $reftrxparams['CONTENTID']);
                    //log_message('log', '=========================');

                    //insert new upgrade/downgrade subscription
                    //check input validation
                    if (is_numeric($trxparams['CONTENTID']) &&
                            is_numeric($trxparams['SPEEDYACCOUNT']) &&
                            is_numeric($trxparams['SUBSCRIPTIONMONTH'])) {

                        if ($this->input->valid_ip($trxparams['HOSTIP']) && $this->input->valid_email($trxparams['EMAILACCOUNT'])) {
                            
                        } else {
                            $this->page['iserror'] = true;
                            $this->page['error_mesg'] = "Data yang Anda masukkan tidak valid, silahkan cek data email yang dimasukkan.";
                        }
                        //print_r($trxparams);
                    } else {
                        $this->page['iserror'] = true;
                        $this->page['error_mesg'] = "Periksa kembali data <b>Nomor Speedy</b> yang Anda masukkan, masukkan tanpa menggunakan karakter spasi atau alphabet lainnya.";
                    }
                    //get content information
                    $contentparams = $this->mmasterdata->gettrxcontentparam($trxparams['CONTENTID']);
                    if (!$contentparams) {
                        $this->page['iserror'] = true;
                        $this->page['error_mesg'] = "Data konten yang dipilih tidak tersedia.";
                    }
                    //check validation credential
                    if (!$this->page['iserror']) {
                        $result = $this->mpaymentgateway->speedyvalidation($trxparams['SPEEDYACCOUNT']); //validation speedy account
                        if ($result == 1) {
                            
                        } else {
                            $trxparams['RESPONSECODE'] = '03';
                            $this->page['iserror'] = true;
                            $this->page['error_mesg'] = "Pembelian tidak berhasil.<br />Validasi nomor Speedy tidak valid, silahkan diperiksa kembali nomor Speedy Anda.";
                        }
                    }
                    //insert transaction in database
                    if (!$this->page['iserror']) {
                        //insert transaction in db;
                        $subsstartdate = "'" . date('Y-m-d H:i:s') . "'";
                        $subsenddate = 'null';
                        if ($trxparams['SUBSCRIPTIONMONTH'] != 0) {
                            //secho $subsstartdate= date('Y-m-d H:i:s')."<br />"; //yyyy-mm-dd hh:mm:ss
                            //echo substr($subsstartdate,0,4)."<br />";
                            $year = intval(substr($subsstartdate, 1, 5)); //echo substr($subsstartdate,5,2)."<br />";
                            //echo $year."<br />";
                            $month = intval(substr($subsstartdate, 6, 2));
                            $month+= ( $trxparams['SUBSCRIPTIONMONTH'] - 1);
                            if ($month > 12) {
                                $month = $month - 12;
                                $year++;
                            }
                            if ($month < 10) {
                                $strmonth = "0" . $month;
                            } else {
                                $strmonth = $month;
                            }
                            $date = $this->getcurrentmaxdate();
                            if ($date < 10)
                                $strdate = "0" . $date;
                            else
                                $strdate= $date;
                            $subsenddate = "'" . $year . "-" . $strmonth . "-" . $strdate . " 23:59:59'";
                            //echo $subsenddate;
                        }
                        $trxparams['SUBSCRIPTIONID'] = $this->mmasterdata->insertsubscriptiontrxspeedy(
                                        $contentparams['CONTENTID'], $contentparams['SUBSCRIPTIONTYPE'], $trxparams['SUBSCRIPTIONSTATUS'], 'OLSTORE-SPEEDY', $trxparams['SPEEDYACCOUNT'], $trxparams['EMAILACCOUNT'], '', $trxparams['HOSTIP'], $trxparams['SUBSCRIPTIONMONTH'], $subsstartdate, $subsenddate, '', $trxparams['REFSUBSCRIPTION'], $trxparams['REFSUBSCRIPTIONTYPE']
                        );
                        $trxparams['ACTIVATIONID'] = $this->mmasterdata->gettrxactivationid($trxparams['SUBSCRIPTIONID']);
                    }
                    //forward transaction to Content Provider
                    if (!$this->page['iserror']) {
                        $result = $this->mpaymentgateway->forwardtransactiontocp($contentparams, $trxparams);
                        if ($result == 1) {
                            //update transaction in database
                            $trxparams['SUBSCRIPTIONSTATUS'] = 'NA';
                            $trxparams['RESPONSECODE'] = '00';
                            $trxparams['ACTIVATIONDATE'] = "null";
                            if ($this->mmasterdata->updatesubscriptiontrxspeedy($trxparams) != 1) {
                                $this->page['iserror'] = true;
                                $this->page['error_mesg'] = "Pembelian tidak berhasil.<br />Internal error di Telkom saat penyimpanan data.";
                            }
                        } else {
                            //update transaction in database
                            $trxparams['SUBSCRIPTIONSTATUS'] = 'NA';
                            $trxparams['RESPONSECODE'] = '02';
                            $trxparams['ACTIVATIONDATE'] = "null";
                            if ($this->mmasterdata->updatesubscriptiontrxspeedy($trxparams) == 1) {
                                $this->page['iserror'] = true;
                                if (@$trxparams['USERIDCONTENT'] != null) {
                                    $this->page['error_mesg'] = "Pembelian tidak berhasil.<br />Transaksi pembelian konten " . $contentparams['NAME'] . " gagal dieksekusi di sisi mitra content provider.
                                    Silahkan cek user id untuk layana content Anda, hal ini dikarenakan user id tersebut telah digunakan oleh user lain.";
                                } else {
                                    $this->page['error_mesg'] = "Pembelian tidak berhasil.<br />Transaksi pembelian konten " . $contentparams['NAME'] . " gagal dieksekusi di sisi mitra content provider.";
                                }
                            }
                        }
                    }
                } else {  //there is no active subscription
                    $this->page['iserror'] = true;
                    $this->page['error_mesg'] = "Status berlangganan tidak aktif.";
                }
            } else {
                $this->page['iserror'] = true;
                $this->page['error_mesg'] = "Data transaksi sebelumnya tidak ditemukan.";
            }
        } else {
            $this->page['iserror'] = true;
            $this->page['error_mesg'] = "Data parameter input error.";
			
        }
        if ($this->page['iserror']){
            echo "0";// . $this->page['error_mesg'];
			log_message('log', 'result    : 0 karena ' . $this->page['error_mesg']);
        }
		else{
            echo "1";
			log_message('log', 'result    : 1');
		}
		log_message('log', '=========================');
    }

}

?>
