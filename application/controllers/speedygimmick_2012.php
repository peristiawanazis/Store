<?php

class speedygimmick_2012 extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->getparam();
    }

    function getparam() {
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

    function getcurrentmaxdate() {
        $currdate = "'" . date('Y-m-d H:i:s') . "'";
        $year = substr($currdate, 0, 5);
        $month = intval(substr($currdate, 6, 2));
        $maxdate = array(1 => 31, 2 => 27, 3 => 31, 4 => 30, 5 => 31, 6 => 30, 7 => 31, 8 => 31, 9 => 30, 10 => 31, 11 => 30, 12 => 31);
        if ($year % 4 == 0) {
            if ($month == 2) {
                return 28;
            } else {
                return $maxdate[$month];
            }
        }
    }

    function speedytrxbuycontent() {
        //print_r($_POST);
        //print_r($this->urisegments);
        $trxparams['SPEEDYACCOUNT'] = $this->input->post('f_SPEEDYACCOUNT');
        $trxparams['CONTENTID'] = $this->input->post('f_CONTENTID');
        $trxparams['MDNNUMBER'] = $this->input->post('f_PHONEHOME');
        $trxparams['EMAILACCOUNT'] = $this->input->post('f_EMAIL');
        $trxparams['HOSTIP'] = $this->input->ip_address();
        $trxparams['SUBSCRIPTIONMONTH'] = $this->input->post('f_SUBSCRIPTIONMONTH');
        $trxparams['TOS'] = $this->input->post('f_TOS');
        if ($trxparams['SUBSCRIPTIONMONTH'] == '')
            $trxparams['SUBSCRIPTIONMONTH'] = 0;
        $trxparams['SUBSCRIPTIONSTATUS'] = 'NA';
        $trxparams['RESPONSECODE'] = '00'; //00 OK, SUBSCRIPTION: 01 ERROR TELKOM, 02 ERROR CP, 03 ERROR POLICY INFRINGEMENT, 04 ERROR INTERNAL TELKOM
        //ACTIVATION: 11 ERROR TELKOM, 12 ERROR CP, 13 ERROR POLICY INFRINGEMENT, 14 ERROR INTERNAL TELKOM
        $trxparams['ACTIVATIONID'] = '';
        $trxparams['SUBSCRIPTIONID'] = '';
        $trxparams['USERIDCONTENT'] = $this->input->post('f_USERIDCONTENT');
        $trxparams['PASSWDCONTENT'] = $this->input->post('f_PASSWDCONTENT');
        $trxparams['PARAM1'] = $this->input->post('f_PARAM1');
        $trxparams['PARAM2'] = $this->input->post('f_PARAM2');
        //echo "<pre>"; print_r($trxparams);echo "</pre>";
        //check input validation
        if (is_numeric($trxparams['CONTENTID']) && is_numeric($trxparams['SPEEDYACCOUNT']) && is_numeric($trxparams['MDNNUMBER']) && is_numeric($trxparams['SUBSCRIPTIONMONTH'])) {
            if ($this->input->valid_ip($trxparams['HOSTIP']) && $this->input->valid_email($trxparams['EMAILACCOUNT'])) {
                
            } else {
                $this->page['iserror'] = true;
                $this->page['error_mesg'] = "0 | Data yang Anda masukkan tidak valid, silahkan cek data email yang dimasukkan.";
            }
            //print_r($trxparams);
        } else {
            $this->page['iserror'] = true;
            $this->page['error_mesg'] = "0 | Periksa kembali data <b>Nomor Speedy</b> dan <b>Nomor Telepon</b> yang Anda masukkan, masukkan tanpa menggunakan karakter spasi atau alphabet lainnya.";
            echo $this->page['error_mesg'];
            return;
        }
        //get content information
        $contentparams = $this->mmasterdata->gettrxcontentparam_ex($trxparams['CONTENTID']);
        if (!$contentparams) {
            $this->page['iserror'] = true;
            $this->page['error_mesg'] = "0 | Data konten yang dipilih tidak tersedia.";
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
                            $this->page['error_mesg'] = "0 | Anda telah melewati jumlah maksimal transaksi berlangganan pada bulan berjalan.";
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
                    //$result = $this->mpaymentgateway->speedyipvalidation($trxparams['SPEEDYACCOUNT'], $trxparams['HOSTIP']); //validation speedy account and MDN
                    $result = 1;
                    if ($result != 1) {
                        $trxparams['RESPONSECODE'] = '03';
                        $this->page['iserror'] = true;
                        $this->page['error_mesg'] = "0 | <span class=\"errortitle\">Pembelian tidak berhasil</span>.<br />Anda tidak melakukan transaksi pembelian dari jaringan Speedy Anda, pastikan Anda telah terhubung ke jaringan Speedy Anda.";
                    }
                } else {

                    $trxparams['RESPONSECODE'] = '03';
                    $this->page['iserror'] = true;
                    $this->page['error_mesg'] = "0 | <span class=\"errortitle\">Pembelian tidak berhasil</span>.<br />Validasi nomor Speedy dan nomor telepon rumah tidak valid, silahkan diperiksa kembali nomor Speedy dan nomor telepon rumah Anda.";
                }
            } else {
                $trxparams['RESPONSECODE'] = '03';
                $this->page['iserror'] = true;
                $this->page['error_mesg'] = "0 | <span class=\"errortitle\">Pembelian tidak berhasil</span>.<br />Validasi nomor Speedy tidak valid, silahkan diperiksa kembali nomor Speedy Anda.";
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
                            $contentparams['CONTENTID'], $contentparams['SUBSCRIPTIONTYPE'], $trxparams['SUBSCRIPTIONSTATUS'], 'OLSTORE-SPEEDY', $trxparams['SPEEDYACCOUNT'], $trxparams['EMAILACCOUNT'], '', $trxparams['HOSTIP'], $trxparams['SUBSCRIPTIONMONTH'], $subsstartdate, $subsenddate, '');
            $trxparams['ACTIVATIONID'] = $this->mmasterdata->gettrxactivationid($trxparams['SUBSCRIPTIONID']);
        }
        //forward transaction to Content Provider
        if (!$this->page['iserror']) {
            $result = $this->mpaymentgateway->forwardtransactiontocp($contentparams, $trxparams);
            $result = 1;
            if ($result == 1) {
                //update transaction in database
                $trxparams['SUBSCRIPTIONSTATUS'] = 'NA';
                $trxparams['RESPONSECODE'] = '00';
                $trxparams['ACTIVATIONDATE'] = "null";
                if ($this->mmasterdata->updatesubscriptiontrxspeedy($trxparams) != 1) {
                    $this->page['iserror'] = true;
                    $this->page['error_mesg'] = "0 | <span class=\"errortitle\">Pembelian tidak berhasil</span>.<br />Internal error di Telkom saat penyimpanan data.";
                }
            } else {
                //update transaction in database
                $trxparams['SUBSCRIPTIONSTATUS'] = 'NA';
                $trxparams['RESPONSECODE'] = '02';
                $trxparams['ACTIVATIONDATE'] = "null";
                if ($this->mmasterdata->updatesubscriptiontrxspeedy($trxparams) == 1) {
                    $this->page['iserror'] = true;
                    if (@$trxparams['USERIDCONTENT'] != null) {
                        $this->page['error_mesg'] = "0 | <span class=\"errortitle\">Pembelian tidak berhasil</span>.<br />Transaksi pembelian konten " . $contentparams['NAME'] . " gagal dieksekusi di sisi mitra content provider.
                            Silahkan cek user id untuk layana content Anda, hal ini dikarenakan user id tersebut telah digunakan oleh user lain.";
                    } else {
                        $this->page['error_mesg'] = "0 | <span class=\"errortitle\">Pembelian tidak berhasil</span>.<br />Transaksi pembelian konten " . $contentparams['NAME'] . " gagal dieksekusi di sisi mitra content provider.";
                    }
                }
            }
        }
        $this->page['trxparams'] = $trxparams;
        if (isset($contentparams))
            $this->page['contentparams'] = $contentparams;
        if (!$this->page['iserror']) {
            $this->page['error_mesg'] = "1 | Transaksi pembelian konten " . $contentparams['NAME'] . " <b>telah berhasil</b>,
                silahkan aktivasi pembelian dengan klik url yang ada di email Anda (" . $trxparams['EMAILACCOUNT'] . ") agar layanan konten dapat digunakan.";
        }
        echo $this->page['error_mesg'];
    }

    function speedygetcontentdetail() {
        if (!isset($this->page['application']['urisegments']['f_CONTENTID']))
            exit;
        $contents = $this->mmasterdata->getcontentdetail_ex(array('contentid' => $this->page['application']['urisegments']['f_CONTENTID']));
        $content = array();
        $rowseq = 0;
        //echo "<pre>"; print_r($contents); echo "</pre>";
        $content = null;
        foreach ($contents as $row) {
            if ($rowseq == 0) {
                $content['contentid'] = $row['CONTENTID'];
                $content['name'] = $row['NAME'];
                $content['contentimage'] = $row['CONTENTIMAGE'];
                $content['longdescript'] = $row['LONGDESCRIPT'];
                $content['category'] = $row['CONTENTCATEGORY'];
                $content['provider'] = $row['CONTENTPROVIDER'];
                $content['circlerating'] = $row['CIRCLERATINGPERIOD'];
                $content['subscriptiontype'] = $row['SUBSCRIPTIONTYPE'];
                $content['isautoextend'] = $row['ISAUTOEXTEND'];
                $content['minsubscriptionmonth'] = $row['MINSUBSCRIPTIONMONTH'];
                $content['createcontentaccount'] = $row['CREATECONTENTACCOUNT'];
                $content['param1'] = $row['PARAM1'];
                $content['param2'] = $row['PARAM2'];
                if (is_numeric($row['CONTENTDISCOUNTID'])) {
                    $content['isdiscount'] = "Y";
                }
                /*
                  if ($row['ISDISCOUNT'] == 1)
                  $content['isdiscount'] = "Y";
                  else
                  $content['isdiscount'] = "N";
                 */
                if ($row['SUBSCRIPTIONTYPE'] == 'SBC')
                    $content['substype'] = "Bulanan";
                else
                    $content['substype'] = "Sekali Bayar";
            }
            $content['price'][$row['RATINGSEQ']] = number_format($row['RATINGVALUE'], 0, '.', ',');
            $content['discount'][$row['RATINGSEQ']]['discountvalue'] = $row['DISCOUNTVALUE'];
            $content['discount'][$row['RATINGSEQ']]['discountmetric'] = $row['DISCOUNTMETRIC'];
            $content['discount'][$row['RATINGSEQ']]['discountdescription'] = $row['DISCOUNTDESCRIPTION'];
            $content['discount'][$row['RATINGSEQ']]['datestart'] = $row['DATESTART'];
            $content['discount'][$row['RATINGSEQ']]['dateend'] = $row['DATEEND'];
            $content['discount'][$row['RATINGSEQ']]['discountcoveragetype'] = $row['DISCOUNTCOVERAGETYPE'];
            $content['discount'][$row['RATINGSEQ']]['discountcoverageparam'] = $row['DISCOUNTCOVERAGEPARAM'];
            $content['discount'][$row['RATINGSEQ']]['discountperiodtSype'] = $row['DISCOUNTPERIODTYPE'];
            $content['discount'][$row['RATINGSEQ']]['discountperiodparam'] = $row['DISCOUNTPERIODPARAM'];
            $content['discount'][$row['RATINGSEQ']]['discountpaymentmethodtype'] = $row['DISCOUNTPAYMENTMETHODTYPE'];
            $content['discount'][$row['RATINGSEQ']]['discountpaymentmethodparam'] = $row['DISCOUNTPAYMENTMETHODPARAM'];
            $content['discount'][$row['RATINGSEQ']]['discountdeliverychanneltype'] = $row['DISCOUNTDELIVERYCHANNELTYPE'];
            $content['discount'][$row['RATINGSEQ']]['discountdeliverychannelparam'] = $row['DISCOUNTDELIVERYCHANNELPARAM'];
            if ($content['discount'][$row['RATINGSEQ']]['discountdescription'] == "")
                $content['discount'][$row['RATINGSEQ']]['discountdescription'] = "-";
            $rowseq++;
            $content['priceseqtotal'] = $rowseq;
        }
        $this->page['content'] = $content;
        $this->load->view('store/include/div-speedy-content-detail-box-gimmick', $this->page);
    }

}

?>
