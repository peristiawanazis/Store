<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of speedymarket
 *
 * @author root
 */
class speedymarket extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->getparam();
    }

    function getparam() { //URI PARAM: speedyaccount, contentid. contentdesc,email,
        @session_start();
        $this->page['header']['application']['siteurl'] = site_url();
        $this->page['header']['application']['baseurl'] = base_url();
        $this->page['header']['application']['theme'] = 'default';
        $this->page['iserror'] = false;
        $this->page['error_mesg'] = "";
        $this->page['islogin'] = false;
        $this->page['sessions'] = array();
        if (!isset($this->page['header']['application']['baseurl'])) //application configuration
            $this->page['header']['application'] = array(
                'baseurl' => base_url(),
                'siteurl' => site_url(),
                'theme' => 'default'
            );
        $this->urisegments = $this->uri->uri_to_assoc($this->config->item('uriparam_index'));
        if (count($this->urisegments) > 0) {
            if (isset($this->urisegments['ajax'])) {
                if (@$this->urisegments['ajax'] == 'true') {
                    $this->urisegments['ajax'] ? $this->page['application']['ajax'] = true : $this->page['application']['ajax'] = false;
                }
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
        ///echo "<pre>"; print_r($this->page['application']['urisegments']); echo "</pre>";
    }

    function index() {
        
    }

    function speedycheckstatus() {
        if (isset($this->page['application']['urisegments']['speedyaccount'])) {
            $result = "0";
            if (is_numeric($this->page['application']['urisegments']['speedyaccount']))
                $result = $this->mpaymentgateway->speedyvalidation($this->page['application']['urisegments']['speedyaccount']); //validation speedy account
            if ($result == "1")
                echo "1";
            else
                echo "0";
        }
    }

    function speedytrxbuycontent() {
        if ((isset($this->page['application']['urisegments']['marketid'])) &&
                (isset($this->page['application']['urisegments']['ipaddress'])) &&
                (isset($this->page['application']['urisegments']['speedyaccount'])) &&
                (isset($this->page['application']['urisegments']['contentid'])) &&
                (isset($this->page['application']['urisegments']['emailaccount'])) &&
                (isset($this->page['application']['urisegments']['mdnnumber']))) {
            //print_r($_POST);  //print_r($this->urisegments);
            $trxparams['DELIVERYCHANNEL'] = $this->page['application']['urisegments']['speedyaccount']; //"SPDMARKET"
            $trxparams['CONTENTID'] = $this->page['application']['urisegments']['contentid'];
            $trxparams['MDNNUMBER'] = $this->page['application']['urisegments']['mdnnumber'];
            $trxparams['HOSTIP'] = $this->page['application']['urisegments']['ipaddress']; //$this->input->ip_address();
            $trxparams['EMAILACCOUNT'] = $this->page['application']['urisegments']['emailaccount'];
            $trxparams['SUBSCRIPTIONMONTH'] = 1;
            $trxparams['TOS'] = "Y";
            $trxparams['SUBSCRIPTIONSTATUS'] = 'NA';
            //00 OK, SUBSCRIPTION: 01 ERROR TELKOM, 02 ERROR CP, 03 ERROR POLICY INFRINGEMENT, 04 ERROR INTERNAL TELKOM
            //ACTIVATION: 11 ERROR TELKOM, 12 ERROR CP, 13 ERROR POLICY INFRINGEMENT, 14 ERROR INTERNAL TELKOM
            $trxparams['RESPONSECODE'] = '00';
            $trxparams['ACTIVATIONID'] = '';
            $trxparams['SUBSCRIPTIONID'] = '';
            //echo "<pre>"; print_r($trxparams);echo "</pre>";
            //check input validation
            if (is_numeric($trxparams['CONTENTID']) && is_numeric($trxparams['SPEEDYACCOUNT']) && is_numeric($trxparams['MDNNUMBER']) && is_numeric($trxparams['SUBSCRIPTIONMONTH'])) {
                if ($this->input->valid_ip($trxparams['HOSTIP']) && $this->input->valid_email($trxparams['EMAILACCOUNT'])) {
                    
                } else {
                    $this->page['iserror'] = true;
                    $this->page['error_mesg'] = "Data yang Anda masukkan tidak valid.";
                }
                //print_r($trxparams);
            } else {
                $this->page['iserror'] = true;
                $this->page['error_mesg'] = "Data yang Anda masukkan tidak valid.";
            }
            //get content information
            $contentparams = $this->mmasterdata->gettrxcontentparam($trxparams['CONTENTID']);
            if (!$contentparams) {
                $this->page['iserror'] = true;
                $this->page['error_mesg'] = "Data konten yang dipilih tidak tersedia.";
            }
            //check content policy subscription
            if (!$this->page['iserror']) {
                if ($contentparams['SUBSCRIPTIONTYPE'] == 'SBC') {
                    if (($trxparams['SUBSCRIPTIONMONTH'] >= $contentparams['MINSUBSCRIPTIONMONTH']) || ($trxparams['SUBSCRIPTIONMONTH'] == 0) || ($contentparams['MINSUBSCRIPTIONMONTH'] == 0)) { //check minimum subscription month
                        if ($contentparams['MAXORDERFREQPERMONTH'] > 0) {
                            $currentorderfreq = $this->mmasterdata->getcurrentmonthtrxorderfreq($trxparams['SPEEDYACCOUNT']);
                            if ($currentorderfreq >= $contentparams['MAXORDERFREQPERMONTH']) {
                                $trxparams['RESPONSECODE'] = '04';
                                $this->page['iserror'] = true;
                                $this->page['error_mesg'] = "Anda telah melewati jumlah maksimal transaksi berlangganan pada bulan berjalan.";
                            }
                        }
                    }
                } elseif ($contentparams['SUBSCRIPTIONTYPE'] == 'CBC') {
                    $trxparams['SUBSCRIPTIONMONTH'] = 1;
                } else
                    $trxparams['SUBSCRIPTIONMONTH'] = 1;
            }
            //echo "<pre>"; print_r($contentparams); echo "</pre>";
            //check validation credential
            if (!$this->page['iserror']) {
                $result = $this->mpaymentgateway->speedyvalidation($trxparams['SPEEDYACCOUNT']); //validation speedy account
                if ($result == 1) {
                    $result = $this->mpaymentgateway->speedymdnvalidation($trxparams['SPEEDYACCOUNT'], $trxparams['MDNNUMBER']); //validation speedy account and MDN
                    if ($result == 1) {
                        $result = $this->mpaymentgateway->speedyipvalidation($trxparams['SPEEDYACCOUNT'], $trxparams['HOSTIP']); //validation speedy account and MDN
                        if ($result != 1) {
                            $trxparams['RESPONSECODE'] = '03';
                            $this->page['iserror'] = true;
                            $this->page['error_mesg'] = "Pembelian tidak berhasil. Anda tidak melakukan transaksi pembelian dari jaringan Speedy Anda, pastikan Anda telah terhubung ke jaringan Speedy Anda.";
                        }
                    } else {

                        $trxparams['RESPONSECODE'] = '03';
                        $this->page['iserror'] = true;
                        $this->page['error_mesg'] = "Pembelian tidak berhasil. Validasi nomor Speedy dan nomor telepon rumah tidak valid, silahkan diperiksa kembali nomor Speedy dan nomor telepon rumah Anda.";
                    }
                } else {
                    $trxparams['RESPONSECODE'] = '03';
                    $this->page['iserror'] = true;
                    $this->page['error_mesg'] = "Pembelian tidak berhasil. Validasi nomor Speedy tidak valid, silahkan diperiksa kembali nomor Speedy Anda.";
                }
            }
            //insert transaction in database
            if (!$this->page['iserror']) {
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
                }
                $trxparams['SUBSCRIPTIONID'] = $this->mmasterdata->insertsubscriptiontrxspeedy(
                                $contentparams['CONTENTID'], $contentparams['SUBSCRIPTIONTYPE'], $trxparams['SUBSCRIPTIONSTATUS'], $trxparams['DELIVERYCHANNEL'], $trxparams['SPEEDYACCOUNT'], $trxparams['EMAILACCOUNT'], '', $trxparams['HOSTIP'], $trxparams['SUBSCRIPTIONMONTH'], $subsstartdate, $subsenddate, '');
                $trxparams['ACTIVATIONID'] = $this->mmasterdata->gettrxactivationid($trxparams['SUBSCRIPTIONID']);
            }
            //forward transaction to Content Provider
            if (!$this->page['iserror']) {
                //$result = $this->mpaymentgateway->forwardtransactiontocp($contentparams, $trxparams);
                $result = 1;
                if ($result == 1) {
                    //update transaction in database
                    $trxparams['SUBSCRIPTIONSTATUS'] = 'NA';
                    $trxparams['RESPONSECODE'] = '00';
                    $trxparams['ACTIVATIONDATE'] = "null";
                    if ($this->mmasterdata->updatesubscriptiontrxspeedy($trxparams) != 1) {
                        $this->page['iserror'] = true;
                        $this->page['error_mesg'] = "Pembelian tidak berhasil. Internal error di Telkom saat penyimpanan data.";
                    }
                } else {
                    //update transaction in database
                    $trxparams['SUBSCRIPTIONSTATUS'] = 'NA';
                    $trxparams['RESPONSECODE'] = '02';
                    $trxparams['ACTIVATIONDATE'] = "null";
                    if ($this->mmasterdata->updatesubscriptiontrxspeedy($trxparams) == 1) {
                        $this->page['iserror'] = true;
                        if (@$trxparams['USERIDCONTENT'] != null) {
                            $this->page['error_mesg'] = "Pembelian tidak berhasil. Silahkan cek user id untuk layana content Anda, hal ini dikarenakan user id tersebut telah digunakan oleh user lain.";
                        } else {
                            $this->page['error_mesg'] = "Pembelian tidak berhasil. Transaksi pembelian konten gagal dieksekusi di sisi mitra content provider.";
                        }
                    }
                }
            }
            //activate transaction directly
            if (!$this->page['iserror']) {
                $trxparams['SUBSCRIPTIONSTATUS'] = "S";
                $trxparams['RESPONSECODE'] = "00";
                $trxparams['ACTIVATIONDATE'] = "'" . date('Y-m-d H:i:s') . "'";
                if ($this->mmasterdata->activatesubscriptiontrxspeedy($this->page['trxparams']) >= 1) {
                    $this->page['error_mesg'] = "Succeed.";
                } else {
                    $this->page['iserror'] = true;
                    $this->page['error_mesg'] = "Aktifasi transaksi pembelian layanan error di Telkom.";
                }
            }
        } else {
            $this->page['iserror'] = true;
            $this->page['error_mesg'] = "Parameter tidak lengkap.";
        }
        if ($this->page['iserror']) {
            echo "0:" . $this->page['error_mesg'];
        } else {
            echo "1:Succeed";
        }
    }

}

?>
