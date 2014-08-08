<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class content extends CI_Controller {
    public
            $page= array();
    function __construct() {
        parent::__construct();
        $this->getparam();
        $this->checksignstatus();
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
            if (@$this->urisegments['ajax']=='true') {
                $this->urisegments['ajax']?$this->page['application']['ajax']= true:$this->page['application']['ajax']= false;
            }
            if (isset($this->urisegments['page']))
                is_numeric($this->urisegments['page'])?null:$this->urisegments['page']= 0;
            else
                $this->urisegments['page']= 0;
            if (isset($this->urisegments['widget']))
                ($this->urisegments['widget']=='true')?$this->dgparams['pageconfig']['widget']= true:null;
            $this->page['application']['urisegments']= $this->urisegments;
        } else {
            $this->page['application']['urisegments']['page']= 0;
        }
        //URI PARAM
        //f_CONTENTCATEGORY

        //echo "<pre>";
        //print_r($this->page);
        //echo "</pre>";
    }
    function getcontentbycategory() {
        $this->page['application']['controller']= site_url()."content/getcontentbycategory/f_CONTENTCATEGORY/".$this->page['application']['urisegments']['f_CONTENTCATEGORY']."/";
        $this->page['contentlist']= array();
        $where= array('CONTENTCATEGORY'=>$this->page['application']['urisegments']['f_CONTENTCATEGORY']);
        $contents= $this->mmasterdata->getcontentlist($where,$this->page['application']['urisegments']['page'],$this->config->item('per_page'));
        $totrow= $this->mmasterdata->getcontenttotalrow($where);
        $rowseq= 0;
        foreach ($contents as $row) {
            $this->page['content']['categoryname']= $row['CONTENTCATEGORY'];
            $this->page['contentlist'][$row['CONTENTID']]['name']= $row['NAME'];
            $this->page['contentlist'][$row['CONTENTID']]['contentimage']= $row['CONTENTIMAGE'];
            $this->page['contentlist'][$row['CONTENTID']]['shortdescript']= $row['SHORTDESCRIPT'];
            $this->page['contentlist'][$row['CONTENTID']]['category']= $row['CONTENTCATEGORY'];
            $this->page['contentlist'][$row['CONTENTID']]['provider']= $row['CONTENTPROVIDER'];
            $this->page['contentlist'][$row['CONTENTID']]['price']= number_format($row['RATINGVALUE'],0,'.',',');
            if (is_numeric($row['CONTENTDISCOUNTID'])) {
                $this->page['contentlist'][$row['CONTENTID']]['discount']= "Y";
            }
            if ($row['SUBSCRIPTIONTYPE']=='SBC')
                $this->page['contentlist'][$row['CONTENTID']]['substype']= "Bulanan";
            else
                $this->page['contentlist'][$row['CONTENTID']]['substype']= "Sekali Bayar";
            $this->page['contentlist'][$row['CONTENTID']]['circlerating']= $row['CIRCLERATINGPERIOD'];
            $this->page['contentlist'][$row['CONTENTID']]['priceseqtotal']= 1;
            $rowseq++;
        }
        $this->page['application']['paging']['total_rows']= $totrow;
        $this->page['application']['paging']['base_url']= $this->page['application']['controller'];
        $this->page['application']['paging']['per_page']= $this->config->item('per_page');
        $this->page['application']['paging']['cur_page']= @$this->page['application']['urisegments']['page'];
        $this->page['application']['paging']['uri_segment']= $this->config->item('uriparam_index');
        $this->page['application']['paging']['container']= "content-box";
        if (@$this->page['application']['urisegments']['ajax']==true) {
            $this->load->view('store/include/div-content-bycategory-box', $this->page);
        }
    }
    function getcontentbyprovider() {
        $this->page['application']['urisegments']['f_CONTENTPROVIDERID']= intval($this->page['application']['urisegments']['f_CONTENTPROVIDERID']);
        //echo "<pre>";
        //print_r($this->page);
        //echo "</pre>";
        $this->page['application']['controller']= site_url()."content/getcontentbyprovider/f_CONTENTPROVIDERID/".$this->page['application']['urisegments']['f_CONTENTPROVIDERID']."/";
        $this->page['contentlist']= array();
        $where= array('CONTENTPROVIDERID'=>$this->page['application']['urisegments']['f_CONTENTPROVIDERID']);
        $contents= $this->mmasterdata->getcontentlist($where,$this->page['application']['urisegments']['page'],$this->config->item('per_page'));
        $totrow= $this->mmasterdata->getcontenttotalrow($where);
        $rowseq= 0;
        //print_r($contents);
        foreach ($contents as $row) {
            $this->page['content']['categoryname']= $row['CONTENTPROVIDER'];
            $this->page['contentlist'][$row['CONTENTID']]['name']= $row['NAME'];
            $this->page['contentlist'][$row['CONTENTID']]['contentimage']= $row['CONTENTIMAGE'];
            $this->page['contentlist'][$row['CONTENTID']]['shortdescript']= $row['SHORTDESCRIPT'];
            $this->page['contentlist'][$row['CONTENTID']]['category']= $row['CONTENTCATEGORY'];
            $this->page['contentlist'][$row['CONTENTID']]['provider']= $row['CONTENTPROVIDER'];
            $this->page['contentlist'][$row['CONTENTID']]['price']= number_format($row['RATINGVALUE'],0,'.',',');
            if (is_numeric($row['CONTENTDISCOUNTID'])) {
                $this->page['contentlist'][$row['CONTENTID']]['discount']= "Y";
            }
            if ($row['SUBSCRIPTIONTYPE']=='SBC')
                $this->page['contentlist'][$row['CONTENTID']]['substype']= "Bulanan";
            else
                $this->page['contentlist'][$row['CONTENTID']]['substype']= "Sekali Bayar";
            $this->page['contentlist'][$row['CONTENTID']]['circlerating']= $row['CIRCLERATINGPERIOD'];
            $this->page['contentlist'][$row['CONTENTID']]['priceseqtotal']= 1;
            $rowseq++;
        }
        $this->page['application']['paging']['total_rows']= $totrow;
        $this->page['application']['paging']['base_url']= $this->page['application']['controller'];
        $this->page['application']['paging']['per_page']= $this->config->item('per_page');
        $this->page['application']['paging']['cur_page']= @$this->page['application']['urisegments']['page'];
        $this->page['application']['paging']['uri_segment']= $this->config->item('uriparam_index');
        $this->page['application']['paging']['container']= "content-box";
        //echo "<pre>";
        //print_r($this->page);
        //echo "</pre>";
        //return;
        if (@$this->page['application']['urisegments']['ajax']==true) {
            $this->load->view('store/include/div-content-bycategory-box', $this->page);
        }
    }
    function getcontentlist() {
        $this->page['application']['controller']= site_url()."content/getcontentlist/";
        $this->page['contentlist']= array();
        $contents= $this->mmasterdata->getcontentlist(null,$this->page['application']['urisegments']['page'],$this->config->item('per_page'));
        $totrow= $this->mmasterdata->getcontenttotalrow();
        $rowseq= 0;
        foreach ($contents as $row) {
            $this->page['contentlist'][$row['CONTENTID']]['name']= $row['NAME'];
            $this->page['contentlist'][$row['CONTENTID']]['contentimage']= $row['CONTENTIMAGE'];
            $this->page['contentlist'][$row['CONTENTID']]['shortdescript']= $row['SHORTDESCRIPT'];
            $this->page['contentlist'][$row['CONTENTID']]['category']= $row['CONTENTCATEGORY'];
            $this->page['contentlist'][$row['CONTENTID']]['provider']= $row['CONTENTPROVIDER'];
            $this->page['contentlist'][$row['CONTENTID']]['price']= number_format($row['RATINGVALUE'],0,'.',',');
            if (is_numeric($row['CONTENTDISCOUNTID'])) {
                $this->page['contentlist'][$row['CONTENTID']]['discount']= "Y";
            }
            if ($row['SUBSCRIPTIONTYPE']=='SBC')
                $this->page['contentlist'][$row['CONTENTID']]['substype']= "Bulanan";
            else
                $this->page['contentlist'][$row['CONTENTID']]['substype']= "Sekali Bayar";
            $this->page['contentlist'][$row['CONTENTID']]['circlerating']= $row['CIRCLERATINGPERIOD'];
            $this->page['contentlist'][$row['CONTENTID']]['priceseqtotal']= 1;
            $rowseq++;
        }
        $this->page['application']['paging']['total_rows']= $totrow;
        $this->page['application']['paging']['base_url']= $this->page['application']['controller'];
        $this->page['application']['paging']['per_page']= $this->config->item('per_page');
        $this->page['application']['paging']['cur_page']= @$this->page['application']['urisegments']['page'];
        $this->page['application']['paging']['uri_segment']= $this->config->item('uriparam_index');
        $this->page['application']['paging']['container']= "content-list-box";
        if (@$this->page['application']['urisegments']['ajax']==true) {
            $this->load->view('store/include/div-content-list-box', $this->page);
        }
    }
    function getcontentnewrelease() {
        $this->page['application']['controller']= site_url()."content/getcontentnewrelease";
        $this->page['newrelease']= array();
        $newreleases= $this->mmasterdata->getcontentnewrelease();
        $prevrow= "";
        $rowseq= 0;
        //print_r($newreleases);
        foreach ($newreleases as $row) {
            if ($prevrow!=$row['CONTENTID']) {
                $prevrow= $row['CONTENTID'];
                $this->page['newrelease'][$row['CONTENTID']]['name']= $row['NAME'];
                $this->page['newrelease'][$row['CONTENTID']]['shortdescript']= $row['SHORTDESCRIPT'];
                $this->page['newrelease'][$row['CONTENTID']]['category']= $row['CONTENTCATEGORY'];
                $this->page['newrelease'][$row['CONTENTID']]['contentimage']= $row['CONTENTIMAGE'];
                $this->page['newrelease'][$row['CONTENTID']]['provider']= $row['CONTENTPROVIDER'];
                $this->page['newrelease'][$row['CONTENTID']]['price']= number_format($row['RATINGVALUE'],0,'.',',');
                if (is_numeric($row['CONTENTDISCOUNTID'])) {
                    $this->page['newrelease'][$row['CONTENTID']]['discount']= "Y";
                }
                if ($row['SUBSCRIPTIONTYPE']=='SBC')
                    $this->page['newrelease'][$row['CONTENTID']]['substype']= "Bulanan";
                else
                    $this->page['newrelease'][$row['CONTENTID']]['substype']= "Sekali Bayar";
                $this->page['newrelease'][$row['CONTENTID']]['circlerating']= $row['CIRCLERATINGPERIOD'];
                $this->page['newrelease'][$row['CONTENTID']]['priceseqtotal']= 1;
                $rowseq++;
            } else {
                $this->page['newrelease'][$row['CONTENTID']]['price'].= "; ".number_format($row['RATINGVALUE'],0,'.',',');
                $this->page['newrelease'][$row['CONTENTID']]['priceseqtotal']++;
            }
            if ($rowseq==$this->config->item('max_newrelease'))
                break;
        }
        //echo "<pre>";
        //print_r($this->page);
        //echo "</pre>";
        if ($this->page['application']['urisegments']['ajax']==true) {
            $this->load->view('store/include/div-content-newrelease-box', $this->page);
        }
    }
    function getcontentdirectory() {
        $this->load->view('store/include/div-content-directory-box',$this->page['header']);
    }
    function getmycontentlist() {
        if (isset($this->page['sessions'][0])) {
            if ($this->page['sessions'][0]=="SPEEDY") {
                $trxparams['SPEEDYACCOUNT']= $this->page['sessions'][1];
                $this->page['subscriptions']= $this->mmasterdata->getcontentsubscriptionspeedy($trxparams['SPEEDYACCOUNT']);
                //echo "<pre>";
                //print_r($this->page['subscriptions']);
                //echo "</pre>";
            }
        }
        $this->load->view('store/include/div-content-mycontent-box',$this->page);
    }
    function getcontentdetail() {
        $contents= $this->mmasterdata->getcontentdetail(array('contentid'=>$this->page['application']['urisegments']['f_CONTENTID']));
        $content= array();
        $rowseq= 0;
        $content= null;
        foreach ($contents as $row) {
            if ($rowseq==0) {
                $content['contentid']= $row['CONTENTID'];
                $content['name']= $row['NAME'];
                $content['contentimage']= $row['CONTENTIMAGE'];
                $content['longdescript']= $row['LONGDESCRIPT'];
                $content['category']= $row['CONTENTCATEGORY'];
                $content['provider']= $row['CONTENTPROVIDER'];
                $content['circlerating']= $row['CIRCLERATINGPERIOD'];
                $content['subscriptiontype']= $row['SUBSCRIPTIONTYPE'];
                $content['minsubscriptionmonth']= $row['MINSUBSCRIPTIONMONTH'];
                $content['createcontentaccount']= $row['CREATECONTENTACCOUNT'];
                $content['param1']= $row['PARAM1'];
                $content['param2']= $row['PARAM2'];
                if (is_numeric($row['CONTENTDISCOUNTID'])) {
                    $content['isdiscount']= "Y";
                }
                
                /*
                if ($row['ISDISCOUNT']==1)
                    $content['isdiscount']= "Y";
                else
                    $content['isdiscount']= "N";
                 */
                if ($row['SUBSCRIPTIONTYPE']=='SBC')
                    $content['substype']= "Bulanan";
                else
                    $content['substype']= "Sekali Bayar";
            }
            $content['price'][$row['RATINGSEQ']]= number_format($row['RATINGVALUE'],0,'.',',');
            $content['discount'][$row['RATINGSEQ']]['discountvalue']= $row['DISCOUNTVALUE'];
            $content['discount'][$row['RATINGSEQ']]['discountmetric']= $row['DISCOUNTMETRIC'];
            $content['discount'][$row['RATINGSEQ']]['discountdescription']= $row['DISCOUNTDESCRIPTION'];
            $content['discount'][$row['RATINGSEQ']]['datestart']= $row['DATESTART'];
            $content['discount'][$row['RATINGSEQ']]['dateend']= $row['DATEEND'];
            $content['discount'][$row['RATINGSEQ']]['discountcoveragetype']= $row['DISCOUNTCOVERAGETYPE'];
            $content['discount'][$row['RATINGSEQ']]['discountcoverageparam']= $row['DISCOUNTCOVERAGEPARAM'];
            $content['discount'][$row['RATINGSEQ']]['discountperiodtSype']= $row['DISCOUNTPERIODTYPE'];
            $content['discount'][$row['RATINGSEQ']]['discountperiodparam']= $row['DISCOUNTPERIODPARAM'];
            $content['discount'][$row['RATINGSEQ']]['discountpaymentmethodtype']= $row['DISCOUNTPAYMENTMETHODTYPE'];
            $content['discount'][$row['RATINGSEQ']]['discountpaymentmethodparam']= $row['DISCOUNTPAYMENTMETHODPARAM'];
            $content['discount'][$row['RATINGSEQ']]['discountdeliverychanneltype']= $row['DISCOUNTDELIVERYCHANNELTYPE'];
            $content['discount'][$row['RATINGSEQ']]['discountdeliverychannelparam']= $row['DISCOUNTDELIVERYCHANNELPARAM'];
            if ($content['discount'][$row['RATINGSEQ']]['discountdescription']=="")
                $content['discount'][$row['RATINGSEQ']]['discountdescription']= "-";
            $rowseq++;
            $content['priceseqtotal']= $rowseq;
        }
        $this->page['content']= $content;
        $this->load->view('store/include/div-speedy-content-detail-box',$this->page);
    }
    function checksignstatus() {
        if (isset($_SESSION['store_sesion_code'])) {
            $rawsession= $this->encrypt->decode($_SESSION['store_sesion_code']);
            $this->page['sessions']= explode('||',$rawsession);
            if (count($this->page['sessions'])>=3)
                $this->page['islogin']= true;
            //print_r($this->page);
        }
    }
}
?>