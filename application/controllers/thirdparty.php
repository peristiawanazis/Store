<?php

class Thirdparty extends CI_Controller {

    public
    $page = array();

    function __construct() {
        parent::__construct();
        $this->getparam();
    }

    function getparam() {
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
            ); //skin css -> default
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
        //URI PARAM
        //f_CONTENTCATEGORY
        //echo "<pre>";
        //print_r($this->page);
        //echo "</pre>";
    }

    function index() {
        echo "";
    }

    /*
     * name: form_pesan
     * Menampilkan halaman form pemesanan konten speedy
     * @param
     * @return
     */

    function formspeedy() {
        //Get POST or URI PARAM variable
        //MANDATORY: USERID, TRXID, CONTENTID, REFERRAL
        //OPTIONAL: TRXQTY, TRXAMT
        //
        //print_r($this->urisegments);
        $client_ip = $this->input->ip_address();
        $contentid = $this->urisegments['contentid'];
        $username = substr(@$this->urisegments['username'], 0, 50);
        $referral = substr(@$this->urisegments['referral'], 0, 30);
        $email = substr(@$this->urisegments['email'], 0, 30);
        //DEBUG CONTENT ID VALVE GAME
        //get content data
        $this->page['referral'] = $referral;
        $this->page['email'] = $email;
        $this->page['username'] = $username;

        $contents = $this->mmasterdata->getcontentdetail(array('contentid' => $contentid));
        $content = array();
        $rowseq = 0;
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
                $content['minsubscriptionmonth'] = $row['MINSUBSCRIPTIONMONTH'];
                $content['createcontentaccount'] = $row['CREATECONTENTACCOUNT'];
                $content['param1'] = $row['PARAM1'];
                $content['param2'] = $row['PARAM2'];
                if ($row['ISDISCOUNT'] == 1)
                    $content['isdiscount'] = "Y";
                else
                    $content['isdiscount'] = "N";
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
        //load the view
        $this->load->view('store/include/div-thirdparty-speedy-content-simple-box', $this->page);
    }

}

?>