<?
//echo "<pre>"; print_r($content); echo "</pre>";
if ($content['circlerating'] == 0)
    $billcaption = "Biaya";
else if ($content['circlerating'] == 1) {
    $billcaption = "Biaya Bulanan";
} else {
    $billcaption = "Biaya Bulanan / " . $content['circlerating'] . " Bln";
}
if (($islogin == true) && (@$sessions[0] == 'SPEEDY')) {
    $speedyaccount = $sessions[1];
    $mdnaccount = $sessions[2];
    $speedyaccount[8] = "X";
    $speedyaccount[9] = "X";
    $speedyaccount[10] = "X";
    $speedyaccount[7] = "X";
    $speedyaccount[6] = "X";
    $mdnaccount[2] = "X";
    $mdnaccount[3] = "X";
    $mdnaccount[4] = "X";
    $mdnaccount[5] = "X";
    $timestamp = $sessions[3];
} else if (($islogin == true) && (@$sessions[0] == 'SSC')) {
    $sscaccount = $sessions[1];
    $host = $sessions[2];
    $timestamp = $sessions[3];
    //echo "<pre>"; print_r($paymentaccounts); echo "</pre>"; 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Pembelian Speedy Content & Application</title>
    </head>
    <!-- SITE STYLE -->
    <link rel="stylesheet" type="text/css" media="screen, projection" href="<?= $header['application']['baseurl'] ?>skin/default/css/speedystore.css" />
    <!-- JAVA SCRIPT -->
    <script type="text/javascript" src="<?= $header['application']['baseurl'] ?>js/jquery.js"></script>
    <script type="text/javascript" src="<?= $header['application']['baseurl'] ?>js/jquery.dd.js"></script>
    <script type="text/javascript" src="<?= $header['application']['baseurl'] ?>js/jquery.alerts.js"></script>
    <script type="text/javascript" src="<?= $header['application']['baseurl'] ?>js/bcds-1.01.js"></script>
    <style>
        body {background-color:#fff;}
    </style>
    <body>
        <div id="contentDetail">
            <div id="detailLeft" class="floatLeft">
                <div id="bigThumb"><img src="<?= $application['baseurl'] ?>content/images/<?= $content['contentimage'] ?>_big.png" width="221" height="221" /></div>
                <div class="h5px"></div>
                <div id="tableHeaderHeading">TARIF PEMBELIAN</div>
                <div class="h5px"></div>
                <div>Untuk berlangganan layanan konten ini Anda akan dikenakan
                    biaya <span class="content-tag"><?= $content['substype'] ?></span>
                    yang akan ditagihkan dalam satu tagihan billing Speedy Anda.
                    <? if ($content['priceseqtotal'] > 1) {
                        ?>
                        <div class="h5px"></div>
                        Siklus tagihan konten ini akan mengikuti skema tarif dibawah
                        dengan periode siklus <span class="content-tag"><?= $content['priceseqtotal'] ?></span> Bulan.
                        <div class="h5px"></div>
                        <?
                        echo '<table class="tbltariff" cellpadding="3" width="100%">
                                <tr class="tbltariffheader"><td width="30">Bulan </td><td width="75" align="right">Tarif Rp. </td><td align="right">Discount</td></tr>';
                        $i = 1;
                        while ($i <= $content['priceseqtotal']) {
                            if ($i % 2 == 1) {
                                echo '<tr class="tbltariffodd"><td align="center">' . $i . '</td>
                                        <td align="right">' . $content['price'][$i] . '&nbsp;&nbsp;&nbsp;</td>
                                        <td align="right">' . $content['discount'][$i]['discountdescription'] . '</td></tr>';
                            } else {
                                echo '<tr class="tbltariffeven"><td align="center">' . $i . '</td>
                                        <td align="right">' . $content['price'][$i] . '&nbsp;&nbsp;&nbsp;</td>
                                        <td align="right">' . $content['discount'][$i]['discountdescription'] . '</td></tr>';
                            }
                            $i++;
                        }
                        echo '</table>';
                        ?>
                    <? } else { ?>
                        <div id="tableCell">
                            <div class="floatLeft"><span class="content-tag">Biaya <?= $content['substype'] ?></span></div>
                            <div class="floatRight">Rp. <?= $content['price'][1] ?></div>
                        </div>
                        <? if (@$content['isdiscount'] == "Y") {
                            ?>
                            <div id="tableCell">
                                <div class="floatLeft"><span class="content-tag">DISCOUNT</span></div>
                                <div class="floatRight"><?= $content['discount'][1]['discountdescription'] ?></div>
                            </div>
                        <? } ?>
                        <?
                    }
                    ?>
                </div>
            </div>
            <div id="detailRight" class="floatLeft">
                <div class="detailTitle"><?= $content['name'] ?></div>
                <div>
                    <?= $content['longdescript'] ?>
                </div>
                <div class="h15px"></div>
                <div>
                    <div class="detailHeading">Formulir Berlangganan Konten</div>
                    <div class="h10px"></div>
                    <div id="div-speedy-order-form">
                            <div id="tableHeaderHeading">
                                Pemesanan
                            </div>
                            <div class="h5px"></div>
                            <div class="formLeft">Nama Konten</div>
                            <div class="formRight"><?= $content['name'] ?></div>
                            <div class="clearfix"></div>
                            <div class="h5px"></div>
                            <div class="formLeft">Tipe Konten</div>
                            <div class="formRight"><?= $content['category'] ?></div>
                            <div class="clearfix"></div>
                            <div class="h5px"></div>
                            <div class="formLeft">Mitra Provider</div>
                            <div class="formRight"><?= $content['provider'] ?></div>
                            <div class="clearfix"></div>
                            <div class="h5px"></div>
                    </div>
                    </form>
                </div>
            </div>

        </div>
        <!-- FOOTER -->
    </body>
</html>
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-9816977-4']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>