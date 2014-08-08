<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class speedytrxactivation extends CI_Controller {

    private
    $page = array();

    function __construct() {
        parent::__construct();
        $this->getparam();
    }

    function getparam() {
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
        /* $this->urisegments= $this->uri->uri_to_assoc($this->config->item('uriparam_index'));
          if (count($this->urisegments)>0) {
          if (@$this->urisegments['ajax']=='true') {
          $this->urisegments['ajax']?$this->page['application']['ajax']= true:$this->page['application']['ajax']= false;
          }
          is_numeric(@$this->urisegments['page'])?null:$this->urisegments['page']= 0;
          (@$this->urisegments['widget']=='true')?$this->dgparams['pageconfig']['widget']= true:null;
          $this->page['application']['urisegments']= $this->urisegments;
          } else {
          $this->page['application']['urisegments']['page']= 0;
          } */
        //URI PARAM
        //f_CONTENTCATEGORY
        //echo "<pre>";
        //print_r($this->page);
        //echo "</pre>";
    }

    function index() {
        //echo "<pre>"; print_r($this->page); echo  "</pre>";
        $this->page['header']['title'] = 'Aktivasi Speedy Content & Aplikasi';
        $activationid = $this->uri->segment(3);
        $activationurl = $this->page['header']['application']['siteurl'];
        if (!$activationid) {
            $this->page['iserror'] = true;
            $this->page['error_mesg'] = "Data input aktivasi yang diterima tidak valid.";
        }
        if (!$this->page['iserror']) {
            $subscriptionid = intval(substr($activationid, 12));
            $this->page['trxparams'] = $this->mmasterdata->getsubscriptiontrxspeedy($subscriptionid);
            if ($this->page['trxparams']) {
                if ($this->page['trxparams']['ACTIVATIONID'] == $activationid && $this->page['trxparams']['SUBSCRIPTIONSTATUS'] == "NA") {
                    $contentparams = $this->mmasterdata->gettrxcontentparam($this->page['trxparams']['CONTENTID']);
                    //echo "<pre>"; print_r($contentparams); echo "</pre>";
                    if ($this->page['trxparams']['TELKOMACCOUNTID'] != "") {
                        if ($this->page['trxparams']['PREPAIDSPEEDYACCOUNT'] != "") {
                            $this->page['trxparams']['PAYMENTACCOUNT'] = $this->page['trxparams']['PREPAIDSPEEDYACCOUNT'];
                            $this->page['trxparams']['PAYMENTACCOUNT_PRODUCT'] = "SPEEDY";
                            $this->page['trxparams']['PAYMENTACCOUNT_ATTRIBUTE'] = "PREPAID";
                        } elseif ($this->page['trxparams']['POSTPAIDSPEEDYACCOUNT'] != "") {
                            $this->page['trxparams']['PAYMENTACCOUNT'] = $this->page['trxparams']['POSTPAIDSPEEDYACCOUNT'];
                            $this->page['trxparams']['PAYMENTACCOUNT_PRODUCT'] = "SPEEDY";
                            $this->page['trxparams']['PAYMENTACCOUNT_ATTRIBUTE'] = "POSTPAID";
                        } elseif ($this->page['trxparams']['PREPAIDFLEXIACCOUNT'] != "") {
                            $this->page['trxparams']['PAYMENTACCOUNT'] = $this->page['trxparams']['PREPAIDFLEXIACCOUNT'];
                            $this->page['trxparams']['PAYMENTACCOUNT_PRODUCT'] = "FLEXI";
                            $this->page['trxparams']['PAYMENTACCOUNT_ATTRIBUTE'] = "PREPAID";
                        } elseif ($this->page['trxparams']['POSTPAIDFLEXIACCOUNT'] != "") {
                            $this->page['trxparams']['PAYMENTACCOUNT'] = $this->page['trxparams']['POSTPAIDFLEXIACCOUNT'];
                            $this->page['trxparams']['PAYMENTACCOUNT_PRODUCT'] = "FLEXI";
                            $this->page['trxparams']['PAYMENTACCOUNT_ATTRIBUTE'] = "POSTPAID";
                        }
                        $this->page['trxparams']['SPEEDYACCOUNT'] = $this->page['trxparams']['PAYMENTACCOUNT'];
                    }
                    $resultcp = $this->mpaymentgateway->forwardactivationtocp($contentparams, $this->page['trxparams']);
                    //echo $resultcp; echo "<pre>"; echo '$resultcp' . $resultcp."\n"; print_r($this->page['trxparams']);print_r($contentparams); echo "</pre>";
                    //$resultcp = 1;
                    $this->page['trxparams']['SUBSCRIPTIONSTATUS'] = "S";
                    $this->page['trxparams']['RESPONSECODE'] = "00";
                    $this->page['trxparams']['ACTIVATIONDATE'] = "'" . date('Y-m-d H:i:s') . "'";
                    if ($resultcp == 1) {
                        //SDP PAYMENT METHOD
                        if ($this->page['trxparams']['TELKOMACCOUNTID'] != "") {
                            if (is_numeric($this->page['trxparams']['REFSUBSCRIPTION'])) { //SPEEDY HOME MONITORING UPGRADE
                                $refsubtrxparams = $this->mmasterdata->getsubscriptiontrxspeedy($this->page['trxparams']['REFSUBSCRIPTION']);
                                if ($refsubtrxparams) {
                                    if ($this->page['trxparams']['REFSUBSCRIPTIONTYPE'] == 'UG') { //UG Upgrade
                                        //deactivate previous/last reference subscription  and activate current transaction
                                        $refsubtrxparams['SUBSCRIPTIONSTATUS'] = "NSUG";
                                        $refsubtrxparams['RESPONSECODE'] = "00";
                                        $refsubtrxparams['DELIVERYCHANNEL'] = 'OLSTORE-SPEEDY';
                                        $refsubtrxparams['UNSUBSHOSTIP'] = $this->input->ip_address();
                                        $refsubtrxparams['UNSUBSDATE'] = date('Y-m-d H:i:s');
                                        $this->mmasterdata->stopsubscriptiontrxspeedy($refsubtrxparams);
                                        $this->mmasterdata->activatesubscriptiontrxspeedy($this->page['trxparams']);
                                    } else { //DG Downgrade
                                        //deactivate current subscription, and automaticaly charge on next month 
                                        $this->page['trxparams']['SUBSCRIPTIONSTATUS'] = "NSDG";
                                        $this->page['trxparams']['RESPONSECODE'] = "00";
                                        $this->page['trxparams']['DELIVERYCHANNEL'] = 'OLSTORE-SPEEDY';
                                        $this->page['trxparams']['UNSUBSHOSTIP'] = $this->input->ip_address();
                                        $this->page['trxparams']['UNSUBSDATE'] = date('Y-m-d H:i:s');
                                        $this->mmasterdata->stopsubscriptiontrxspeedy($this->page['trxparams']); //deactivate current transaction
                                    }
                                }
                            }
                            //CHARGE CUSTOMER
                            $params['enduseridentifier'] = "tel:" . $this->page['trxparams']['PAYMENTACCOUNT']; //tel:02127211141";
                            $params['charging-description'] = "Tagihan 500";
                            $params['charging-amount'] = 500;
                            $params['charging-code'] = "SDPCHG005";
                            $params['referencecode'] = $subscriptionid;
                            $resultcharging = $this->msdp->chargeaccount($params);
                            //charge SHASA
                            //$params['enduseridentifier'] = "tel:02127211141";
                            //$params['charging-description'] = "Tagihan 500";
                            //$params['charging-amount'] = 500;
                            //$params['charging-code'] = "SDPCHG005";
                            //$params['referencecode'] = "Rp. 500,- testing-SDQ";
                            //$resultcharging= $this->msdp->chargeaccount($params);
                            //SEND NOTIFICATION
                            $params['sendername'] = "1441";
                            $params['charging-description'] = "NOTIFIKASI AKTIVASI";
                            $params['charging-amount'] = 0;
                            $params['charging-code'] = "SDPCHG000";
                            if (@$resultcharging == 1) {
                                //send information to customer
                                $params['message'] = "SUKSES: Aktifasi konten layanan " . $contentparams['NAME'] . " berhasil, Webstore Speedy";
                                if ($this->page['trxparams']['PAYMENTACCOUNT_PRODUCT'] == "FLEXI") {
                                    $params['addresses'] = "tel:" . $this->page['trxparams']['PAYMENTACCOUNT'];
                                    $this->msdp->sendsms($params);
                                }
                                $params['addresses'] = "tel:081310008347"; //"tel:".$this->page['trxparams']['PAYMENTACCOUNT']
                                $this->msdp->sendsms($params);
                            } else {
                                $params['message'] = "ERROR: Aktifasi konten layanan " . $contentparams['NAME'] . " berhasil, Webstore Speedy";
                                if ($this->page['trxparams']['PAYMENTACCOUNT_PRODUCT'] == "FLEXI") {
                                    $params['addresses'] = "tel:" . $this->page['trxparams']['PAYMENTACCOUNT'];
                                    $this->msdp->sendsms($params);
                                }
                                $params['addresses'] = "tel:081310008347"; //"tel:".$this->page['trxparams']['PAYMENTACCOUNT']
                                $this->msdp->sendsms($params);
                            }
                            $this->mmasterdata->activatesubscriptiontrxspeedy($this->page['trxparams']);

                            //POSTPAID SPEEDY - LEGACY
                        } else {
                            if (is_numeric($this->page['trxparams']['REFSUBSCRIPTION'])) { //SPEEDY HOME MONITORING UPGRADE
                                $refsubtrxparams = $this->mmasterdata->getsubscriptiontrxspeedy($this->page['trxparams']['REFSUBSCRIPTION']);
                                if ($refsubtrxparams) {
                                    if ($this->page['trxparams']['REFSUBSCRIPTIONTYPE'] == 'UG') { //UG Upgrade
                                        //deactivate previous/last reference subscription  and activate current transaction
                                        $refsubtrxparams['SUBSCRIPTIONSTATUS'] = "NSUG";
                                        $refsubtrxparams['RESPONSECODE'] = "00";
                                        $refsubtrxparams['DELIVERYCHANNEL'] = 'OLSTORE-SPEEDY';
                                        $refsubtrxparams['UNSUBSHOSTIP'] = $this->input->ip_address();
                                        $refsubtrxparams['UNSUBSDATE'] = date('Y-m-d H:i:s');
                                        $this->mmasterdata->stopsubscriptiontrxspeedy($refsubtrxparams);
                                        $this->mmasterdata->activatesubscriptiontrxspeedy($this->page['trxparams']);
                                    } else { //DG Downgrade
                                        //deactivate current subscription, and automaticaly charge on next month 
                                        $this->page['trxparams']['SUBSCRIPTIONSTATUS'] = "NSDG";
                                        $this->page['trxparams']['RESPONSECODE'] = "00";
                                        $this->page['trxparams']['DELIVERYCHANNEL'] = 'OLSTORE-SPEEDY';
                                        $this->page['trxparams']['UNSUBSHOSTIP'] = $this->input->ip_address();
                                        $this->page['trxparams']['UNSUBSDATE'] = date('Y-m-d H:i:s');
                                        $this->mmasterdata->stopsubscriptiontrxspeedy($this->page['trxparams']); //deactivate current transaction
                                    }
                                } else {
                                    $this->mmasterdata->activatesubscriptiontrxspeedy($this->page['trxparams']);
                                }
                            } else {
                                $this->mmasterdata->activatesubscriptiontrxspeedy($this->page['trxparams']);
                            }
                        }
                    } else {
                        $this->page['trxparams']['SUBSCRIPTIONSTATUS'] = "NA";
                        $this->page['trxparams']['RESPONSECODE'] = "12";
                        $this->mmasterdata->activatesubscriptiontrxspeedy($this->page['trxparams']);
                    }
                    //INFORMATION & MESSAGE
                    if ($resultcp == '1') { //activation to CP is SUCCEED
                        $activationurl = ($contentparams['REDIRECTURL'] ? $contentparams['REDIRECTURL'] . 'activationkey=' . $this->page['trxparams']['ACTIVATIONID'] : site_url('welcome'));
                        //$this->page['meta'] = '<meta http-equiv="refresh" content="5; url='.$activationurl.'">';
                        $this->page['message'] = 'Aktivasi langganan konten ' . $contentparams['NAME'] . ' berhasil!<br/><br/>';
                        $this->page['message'] .= 'Anda akan diarahkan ke alamat Content Provider konten ini dalam waktu 5 detik.<br/><br/>';
                        $this->page['message'] .= 'Klik <a href="' . $activationurl . '">disini</a> jika Anda tidak ingin menunggu.<br/>';
                    } else if ($resultcp == '2') {
                        $activationurl = ($contentparams['REDIRECTURL'] ? $contentparams['REDIRECTURL'] . 'activationkey=' . $this->page['trxparams']['ACTIVATIONID'] : site_url('welcome'));
                        //$this->page['meta'] = '<meta http-equiv="refresh" content="5; url='.$activation_url.'">';
                        $this->page['message'] = 'Aktivasi langganan konten ' . $contentparams['NAME'] . ' sudah pernah diaktivasi!<br/><br/>';
                        $this->page['message'] .= 'Anda akan diarahkan ke alamat Content Provider konten ini dalam waktu 5 detik.<br/><br/>';
                        $this->page['message'] .= 'Klik <a href="' . $activationurl . '">disini</a> jika Anda tidak ingin menunggu.<br/>';
                    } else {
                        $this->page['message'] = 'Permintaan untuk mengaktifkan konten ' . $contentparams['NAME'] . ' tidak berhasil diproses, dikarenakan ada permasalahan di sisi mitra Content Provider.<br/>';
                        $this->page['message'] .= 'Cobalah beberapa saat lagi.';
                    }
                } else {
                    $this->page['iserror'] = true;
                    $this->page['error_mesg'] = 'Konten sudah pernah diaktifkan !<br />Silahkan cek email Anda, atau cek di folder Spam bilamana belum ditemukan.';
                }
            } else {
                $this->page['iserror'] = true;
                $this->page['error_mesg'] = 'Data pemesanan tidak ditemukan.';
            }
        }
        $this->page['header']['meta'] = '<meta http-equiv="refresh" content="5; url=' . $activationurl . '">';
        //echo "<pre>";
        //print_r($this->page);
        //echo "</pre>";
        $this->load->view('store/include/div-speedy-trx-activation', $this->page);
    }

}

?>