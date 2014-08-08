<?php
if (!isset($header['application']['baseurl'])) //application configuration
    $header['application'] = array(
        'baseurl' => base_url(),
        'siteurl' => site_url(),
        'theme' => 'default'
    ); //skin css -> default
if (!isset($header['javascript'])) //header-javascript
    $header['javascript'] = array(
        'jquery' => $header['application']['baseurl'] . 'js/jquery.js'
        , 'bcds' => $header['application']['baseurl'] . 'js/bcds-1.01.js'
        , 'tabs' => $header['application']['baseurl'] . 'js/jquery.tabSwitch.yui.js'
        , 'fancy' => $header['application']['baseurl'] . 'js/jquery.fancybox-1.3.4.pack.js'
        , 'cycle' => $header['application']['baseurl'] . 'js/jquery.cycle.min.js'
    );
if (!isset($header['stylesheet'])) //header-stylesheet
    $header['stylesheet'] = array(
        'store' => $header['application']['baseurl'] . 'skin/' . $header['application']['theme'] . '/css/speedystore.css'
    );
if (!isset($header['title'])) //header-title
    $header['title'] = 'store.Telkomspeedy.com';

$categories = $this->mmasterdata->getcontentgroupname();
$rowprev = "";
$header['categories']['Beranda']['url'] = "javascript:etalase.loadpage('content-box','" . $header['application']['siteurl'] . "content/getcontentdirectory/ajax/true/')";
$header['categories']['Beranda']['qty'] = '';
foreach ($categories as $row) {
    if ($row['QTY'] > 0) {
        $header['categories'][$row['NAME']]['url'] = "javascript:etalase.loadpage('content-box','" . $header['application']['siteurl'] . "content/getcontentbycategory/f_CONTENTCATEGORY/" . $row['CONTENTCATEGORYID'] . "/" . "ajax/true/')";
        $header['categories'][$row['NAME']]['qty'] = ' (' . $row['QTY'] . ')';
    }
}
//print_r($header);
$this->load->view('store/header-store');
?>
<!-- CONTENT -->
<div id="wrapper">
    <!-- LEFT -->

    <div id="leftContent" class="floatLeft">
        <div><img src="<?= $header['application']['baseurl'] ?>/skin/default/images/sidemenu_header.png" width="180" height="9" alt="header" /></div>
        <div id="sideMenu">
            <div id="login-box" style="padding-left:17px; width:161px;">
                <?php
                $this->load->view('store/include/div-speedy-signin-box', array('header' => $header, 'iserror' => $iserror, 'error_mesg' => $error_mesg));
                ?>
            </div>
        </div>
        <div><img src="<?= $header['application']['baseurl'] ?>/skin/default/images/sidemenu_footer.png" width="180" height="9" alt="footer" /></div>
        <div class="h15px"></div>
        <div><img src="<?= $header['application']['baseurl'] ?>/skin/default/images/sidemenu_header.png" width="180" height="9" alt="header" /></div>
        <div id="sideMenu">
            <div style="width:178px;">
                <div class="h10px"></div>

                <div style="padding-left:17px;"><img src="<?= $header['application']['baseurl'] ?>/skin/default/images/txt_kategori.png" width="69" height="16" alt="My Content Login" /></div>
                <div class="h10px"></div>
                <div id="menu">
                    <ul>
                        <?
                        foreach (array_keys($header['categories']) as $key) {
                            echo '<li><a href="' . $header['categories'][$key]['url'] . '">' . $key . '<span> ' . $header['categories'][$key]['qty'] . '</span></a></li>';
                        }
                        ?>
                    </ul>
                    <div class="h5px"></div>
                </div>
            </div>
        </div>
        <div><img src="<?= $header['application']['baseurl'] ?>/skin/default/images/sidemenu_footer.png" width="180" height="9" alt="footer" /></div>
    </div>

</div>

<!-- RIGHT -->
<div id="rightContent" class="floatLeft">
    <div class="floatLeft">
        <div class="slideshow">
            <img src="<?= $header['application']['baseurl'] ?>/skin/default/images/banner1.png" width="440" height="180" />
            <img src="<?= $header['application']['baseurl'] ?>/skin/default/images/banner2.png" width="440" height="180" />
            <img src="<?= $header['application']['baseurl'] ?>/skin/default/images/banner3.png" width="440" height="180" />
        </div>

    </div>
    <div class="floatRight"><img src="<?= $header['application']['baseurl'] ?>/skin/default/images/banner_right.png" width="180" height="180" /></div>
    <div class="clearfix"></div>
    <div class="h15px"></div><div class="h10px"></div>
    <div id="pager"></div>
    <div class="clearfix"></div>
    <div id="content-box">
        <?php
        $this->load->view('store/include/div-content-directory-box', $header);
        ?>
    </div>
    <div class="h10px"></div>    
</div>
<div class="clearfix"></div>
<div class="h10px"></div>
<?php
$this->load->view('store/footer-store', $header);
?>