<?
//echo "<pre>";
//print_r($content);
//echo "</pre>";
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
    //$mdnaccount[0]= "X";
    //$mdnaccount[1]= "X";
    $mdnaccount[2] = "X";
    $mdnaccount[3] = "X";
    $mdnaccount[4] = "X";
    $mdnaccount[5] = "X";
    $timestamp = $sessions[3];
} else if (($islogin == true) && (@$sessions[0] == 'SSC')) {
    $sscaccount = $sessions[1];
    $host = $sessions[2];
    //$sscaccount[8] = "X";
    //$sscaccount[9] = "X";
    //$sscaccount[10] = "X";
    //$sscaccount[7] = "X";
    //$sscaccount[6] = "X";
    //$host[0]= "X";
    //$host[1]= "X";
    //$host[2] = "X";
    //$host[3] = "X";
    //$host[4] = "X";
    //$host[5] = "X";
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
    <script type="text/javascript" src="<?= $header['application']['baseurl'] ?>js/jquery.alerts.js"></script>
    <script type="text/javascript" src="<?= $header['application']['baseurl'] ?>js/bcds-1.01.js"></script>
	<script type="text/javascript" src="<?= $header['application']['baseurl'] ?>js/swfobject.js"></script>
	<script type="text/javascript">swfobject.registerObject("swfobj1", "9", "expressInstall.swf");</script>
	<script type="text/javascript">swfobject.registerObject("swfobj2", "9", "expressInstall.swf");</script>
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

							<!-- Video Demo untuk Pesona Edu -->
							<?
								if($content['contentid']==521  ||  $content['contentid']==522 || $content['contentid']==541 ||
									$content['contentid']==542  || $content['contentid']==543 || $content['contentid']==561) {
							?>
							<div>
								<p><b>Berikut demo simulasi interaktif kami untuk Anda:</b></p>
								<ul>
									<li>1. Bagaimana cara alarm kebakaran bekerja? 
									<a href="<?= $application['baseurl'] ?>content/video/file.php?file=alarm.avi">[Download]</a>
									</li>
									<li>2. Mengapa paku tajam lebih mudah menancap? 
									<a href="<?= $application['baseurl'] ?>content/video/file.php?file=CTV_paku.avi">[Download]</a>
									</li>
								</ul>
							</div>
							<? 
								}
							?>
							<!--- -------------------------------------->

					<!---------------- SWF untuk Bamboomedia --------------------------->
					<? if ($content['contentid']==471 ||  $content['contentid']==472) { ?>
						<p><b>1. Menyusun Potongan Gambar</b></p><br/>
						<object id="swfobj1" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="400" height="300">
							<param name="movie" value="<?= $application['baseurl'] ?>content/video/MenyusunGambar.swf" />
							<!--[if !IE]>-->
							<object type="application/x-shockwave-flash" data="<?= $application['baseurl'] ?>content/video/MenyusunGambar.swf" width="400" height="300">
							<!--<![endif]-->
							  <p>Alternative content</p>
							<!--[if !IE]>-->
							</object>
							<!--<![endif]-->
						</object><br/><br/>
						
						<p><b>2. Grafik Fungsi Kuadrat </b> (Perbesar/zoom halaman browser Anda untuk tampilan yang lebih jelas)</p><br/>
						<object id="swfobj2" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="400" height="300">
							<param name="movie" value="<?= $application['baseurl'] ?>content/video/GrafikFungsiKuadrat.swf" />
							<!--[if !IE]>-->
							<object type="application/x-shockwave-flash" data="<?= $application['baseurl'] ?>content/video/GrafikFungsiKuadrat.swf" width="400" height="300">
							<!--<![endif]-->
							  <p>Alternative content</p>
							<!--[if !IE]>-->
							</object>
							<!--<![endif]-->
						</object><br/>
					<? } ?>
					<!-------------------------------------------------------------------------->

                <div class="h15px"></div>
                <div class="detailHeading">Formulir Berlangganan Konten</div>
                <div class="h10px"></div>
                <div id="div-speedy-order-form">
                    <form id="form-speedy-trx-buy">
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
                        <?
                        if ($content['substype'] == "Bulanan") {
                            if ($content['isautoextend'] == 0) {
                                if (($content['minsubscriptionmonth'] == 0) || ($content['minsubscriptionmonth'] != $content['priceseqtotal'])) {
                                    echo '<div class="formLeft">Lama Berlangganan</div>
                <div class="formRight">
                <select name="f_SUBSCRIPTIONMONTH" class="detailselect">';
                                    if (($content['minsubscriptionmonth'] == 0)) {
                                        echo '<option value="0" selected="selected" >sampai berhenti</option>';
                                        echo '<option value="1">1 &nbsp;&nbsp;bulan</option>
                <option value="3">3 &nbsp;&nbsp;bulan</option>
                <option value="6">6 &nbsp;&nbsp;bulan</option>
                <option value="9">9 &nbsp;&nbsp;bulan</option>
                <option value="12">12 bulan</option>';
                                    } else {
                                        if ($content['minsubscriptionmonth'] > 0) {
                                            for ($i = $content['minsubscriptionmonth']; $i <= 12; $i++) {
                                                echo '<option value="' . $i . '">' . $i . ' bulan</option>';
                                            }
                                        }
                                    }
                                    echo '</select></div><div class="clearfix"></div>';
                                }
                            } else {
                                echo '<div class="formLeft">Lama Berlangganan</div><div class="formRight">
                                Layanan ini akan diperpanjang secara otomatis.</div><div class="clearfix"></div>';
                            }
                        }
                        ?>
                        <?
                        if ($content['createcontentaccount'] == 1) {
                            ?>
                            <div class="h10px"></div>
                            <div id="tableHeaderHeading">
                                Masukkan User ID baru untuk akses ke konten
                            </div>
                            <div class="h5px"></div>
                            <? if($content['contentid'] == 791 || $content['contentid'] == 792 || $content['contentid'] == 793){ ?>							
					<div class="formLeft">Username (voucher)</div>
                            <? } else { ?>
					<div class="formLeft">Username (gunakan email)</div>
				<? } ?>                            
				<div class="formRight">
                            <input name="f_USERIDCONTENT" value="" maxlength="25" size="25" id="f_USERIDCONTENT" type="text" class="login" style="width:141px;"/></div>
                            <div class="clearfix"></div>
                            <div class="h5px"></div>
                            <div class="formLeft">Password</div>
                            <div class="formRight"><input type="password" name="f_PASSWDCONTENT" value="" maxlength="25" size="25" id="f_PASSWDCONTENT" class="login" style="width:141px;"/></div>
                            <div class="clearfix"></div>
                            <div class="h5px"></div>
                            <div class="formLeft">Konfirmasi Password</div>
                            <div class="formRight"><input type="password" name="f_PASSWDCONTENT1" value="" maxlength="25" size="25" id="f_PASSWDCONTENT1" class="login" style="width:141px;"/></div>
                            <div class="clearfix"></div>
                        <? } ?>
                        <div class="h5px"></div>
                        <? if (($islogin == false) || (($islogin == true) && (@$sessions[0] == 'SPEEDY'))) { ?>
                            <div id="paymentspeedy">
                                <div id="tableHeaderHeading">
                                    Pembayaran dengan Postpaid Speedy
                                </div>
                                <div class="h5px"></div>
                                <div class="formLeft">No. Speedy</div>
                                <? if ($islogin == true) { ?>
                                    <div class="formRight"><input name="f_SPEEDYACCOUNT" readonly value="<?= $speedyaccount ?>" maxlength="12" size="25" id="f_SPEEDYACCOUNT" type="text" class="login-readonly" style="width:141px;"/></div>
                                <? } else { ?>
                                    <div class="formRight"><input name="f_SPEEDYACCOUNT" value="" maxlength="12" size="25" id="f_SPEEDYACCOUNT" type="text" class="login" style="width:141px;"/> @telkom.net</div>
                                <? } ?>
                                <div class="clearfix"></div>
                                <div class="h5px"></div>
                                <div class="formLeft">No. Telp Rumah Speedy</div>
                                <div class="formRight"><input name="f_PHONEHOME" value="" maxlength="25" size="25" id="f_PHONEHOME" type="text" class="login" style="width:141px;"/></div>
                                <div class="clearfix"></div>

                                <div class="h5px"></div>
                                <div class="formLeft">Email</div>
                                <div class="formRight"><input name="f_EMAIL" value="" maxlength="50" size="50" id="f_EMAIL" type="text" class="login" style="width:141px;"/></div>
                                <div class="clearfix"></div>
                            </div>
                        <? } else if (($islogin == true) && (@$sessions[0] == 'SSC')) { ?>
                            <div id="paymentssc">
                                <div id="tableHeaderHeading">
                                    Pembayaran Melalui Akun Email Anda
                                </div>
                                <div class="h5px"></div>
                                <div class="formLeft">Akun Anda</div>
                                <div class="formRight"><input name="f_SSCACCOUNT" readonly value="<?= $sscaccount ?>" maxlength="50" size="50" id="f_SSCACCOUNT" type="text" class="login-readonly" style="width:141px;"/></div>
                                <div class="clearfix"></div>
                                <div class="h5px"></div>
                                <div class="formLeft">Jenis Pembayaran</div>
                                <div class="formRight">
                                    <select name="f_PAYMENTACCOUNT" value="" id="f_PAYMENTACCOUNT" class="login" style="font-size: 11px;width:141px;">
                                        <?
                                        if (count($paymentaccounts['productnumbers']) > 0) {
                                            foreach (array_keys($paymentaccounts['productnumbers']) as $key) {
                                                echo $paymentaccounts['productnumbers'][$key];
                                                echo "<option value=\"" . $paymentaccounts['types'][$key]."|".$paymentaccounts['productnumbers'][$key]."|".$paymentaccounts['productattributes'][$key]  . "\">" . ucfirst($paymentaccounts['types'][$key]) . " - " . $paymentaccounts['productnumbers'][$key] . "</option>";
                                            }
                                        } else {
                                            echo "<option value=\"\">Belum ada data payment account yang terdaftar dengan akun Email Anda.</option>";
                                        }
                                        ?>
                                    </select>                                    
                                </div>
                                <div class="clearfix"></div>
                                <div class="h5px"></div>
                                <div class="formLeft">Email</div>
                                <div class="formRight"><input name="f_EMAIL" value="<?= $sscaccount ?>" maxlength="50" size="50" id="f_EMAIL" type="text" class="login" style="width:141px;"/></div>
                                <div class="clearfix"></div>
                            </div>
                        <? } ?>
                        <div class="formLeft">&nbsp;</div>
                        <div class="formRight termService"><input name="f_TOS" id="f_TOS" type="checkbox" value="Y" /> Menyetujui <a href="#">Terms of Service</a> layanan</div>
                        <div class="clearfix"></div>
                        <div class="h5px"></div>
                        <div class="formLeft">Kode keamanan</div>
                        <div class="formRight"><input name="f_CAPTCHA" id="f_CAPTCHA" value="" maxlength="25" size="25" type="text" class="login" style="width:141px;"/></div>
                        <div class="clearfix"></div>
                        <div class="h5px"></div>
                        <div class="formLeft">&nbsp;</div>
                        <div class="formRight"><div class="captcha"><img src="<?= $header['application']['baseurl'] . 'captcha.php' ?>" /></div></div>

                        <div class="clearfix"></div>
                        <div class="h5px"></div>
                        <div style="border-top:1px solid #ddd; padding-top:5px;">
                        </div>
                        <div class="formLeft">&nbsp;</div>
                        <div class="formRight">
                            <? if (($islogin == true) && (@$sessions[0] == 'SSC')) { ?>
                                <input name="Pesan" type="button" onclick="javascript:etalase.sscsubmitform('div-speedy-order-form','form-speedy-trx-buy','<?= site_url() ?>transaction/ssctrxbuycontent/f_CONTENTID/<?= $content['contentid'] ?>/')" value="Pesan" class="btnFormDetail"/>&nbsp;&nbsp;
                            <? } else { ?>
                                <input name="Pesan" type="button" onclick="javascript:etalase.speedysubmitform('div-speedy-order-form','form-speedy-trx-buy','<?= site_url() ?>transaction/speedytrxbuycontent/f_CONTENTID/<?= $content['contentid'] ?>/')" value="Pesan" class="btnFormDetail"/>&nbsp;&nbsp;
                            <? } ?>
                            <input name="Reset" type="reset" value="Reset" class="btnFormDetail"/></div>
                        <div class="clearfix"></div>
                        <div class="h5px"></div>
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