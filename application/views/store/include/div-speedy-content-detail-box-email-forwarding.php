<?
//echo "<pre>";
//print_r($content);
//echo "</pre>";
if ($content['circlerating']==0)
    $billcaption= "Biaya";
else if ($content['circlerating']==1) {
    $billcaption= "Biaya Bulanan";
} else {
    $billcaption= "Biaya Bulanan / ".$content['circlerating']." Bln";
}
if ($islogin==true) {
    $speedyaccount= $sessions[1];
    $mdnaccount= $sessions[2];
    $speedyaccount[8]= "X";
    $speedyaccount[9]= "X";
    $speedyaccount[10]= "X";
    $speedyaccount[7]= "X";
    $speedyaccount[6]= "X";
//$mdnaccount[0]= "X";
//$mdnaccount[1]= "X";
    $mdnaccount[2]= "X";
    $mdnaccount[3]= "X";
    $mdnaccount[4]= "X";
    $mdnaccount[5]= "X";
    $timestamp= $sessions[3];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Pembelian Speedy Content & Application</title>
    </head>
    <!-- SITE STYLE -->
    <link rel="stylesheet" type="text/css" media="screen, projection" href="<?=$header['application']['baseurl']?>skin/default/css/speedystore.css" />
    <!-- JAVA SCRIPT -->
    <script type="text/javascript" src="<?=$header['application']['baseurl']?>js/jquery.js"></script>
    <script type="text/javascript" src="<?=$header['application']['baseurl']?>js/jquery.alerts.js"></script>
    <script type="text/javascript" src="<?=$header['application']['baseurl']?>js/bcds-1.01.js"></script>
    <style>
        body {background-color:#fff;}
    </style>
    <body>
        <div id="contentDetail">
            <div id="detailLeft" class="floatLeft">
                <div id="bigThumb"><img src="<?=$application['baseurl']?>content/images/<?=$content['contentimage']?>_big.png" width="221" height="221" /></div>
                <div class="h5px"></div>
                <div id="tableHeaderHeading">TARIF PEMBELIAN</div>
                <div class="h5px"></div>
                <div>Untuk berlangganan layanan konten ini Anda akan dikenakan
                    biaya <span class="content-tag"><?=$content['substype']?></span>
                    yang akan ditagihkan dalam satu tagihan billing Speedy Anda.
                    <? if ($content['priceseqtotal']>1) {
                        ?>
                    <div class="h5px"></div>
                    Siklus tagihan konten ini akan mengikuti skema tarif dibawah
                    dengan periode siklus <span class="content-tag"><?=$content['priceseqtotal']?></span> Bulan.
                    <div class="h5px"></div>
                        <?
                        echo '<table class="tbltariff" cellpadding="3" width="100%">
                                <tr class="tbltariffheader"><td width="30">Bulan </td><td width="75" align="right">Tarif Rp. </td><td align="right">Discount</td></tr>';
                        $i= 1;
                        while ($i<=$content['priceseqtotal']) {
                            if ($i%2==1) {
                                echo '<tr class="tbltariffodd"><td align="center">'.$i.'</td>
                                        <td align="right">'.$content['price'][$i].'&nbsp;&nbsp;&nbsp;</td>
                                        <td align="right">'.$content['discount'][$i]['discountdescription'].'</td></tr>';
                            } else {
                                echo '<tr class="tbltariffeven"><td align="center">'.$i.'</td>
                                        <td align="right">'.$content['price'][$i].'&nbsp;&nbsp;&nbsp;</td>
                                        <td align="right">'.$content['discount'][$i]['discountdescription'].'</td></tr>';
                            }
                            $i++;
                        }
                        echo '</table>';
                        ?>
                        <?
                    } else { ?>
                    <div id="tableCell">
                        <div class="floatLeft"><span class="content-tag">Biaya <?=$content['substype']?></span></div>
                        <div class="floatRight">Rp. <?=$content['price'][1]?></div>
                    </div>
                        <?if (@$content['isdiscount']=="Y") {
                            ?>
                    <div id="tableCell">
                        <div class="floatLeft"><span class="content-tag">DISCOUNT</span></div>
                        <div class="floatRight"><?=$content['discount'][1][discountdescription]?></div>
                    </div>
                            <?
                        } ?>
                        <?
                    }
                    ?>
                </div>
            </div>
            <div id="detailRight" class="floatLeft">
                <div class="detailTitle"><?=$content['name']?></div>
                <div>
                    <?=$content['longdescript']?>
                </div>
                <div class="h15px"></div>
                <div class="detailHeading">Formulir Berlangganan Konten</div>
                <div class="h10px"></div>
                <div id="div-speedy-order-form">
                    <form id="form-speedy-trx-buy">
                        <div id="tableHeaderHeading">
                            Nama Layanan
                        </div>
                        <div class="h5px"></div>
                        <div class="formLeft">Nama Konten</div>
                        <div class="formRight"><?=$content['name']?></div>
                        <div class="clearfix"></div>
                        <div class="h5px"></div>
                        <div class="formLeft">Tipe Konten</div>
                        <div class="formRight"><?=$content['category']?></div>
                        <div class="clearfix"></div>
                        <div class="h5px"></div>
                        <div class="formLeft">Mitra Provider</div>
                        <div class="formRight"><?=$content['provider']?></div>
                        <div class="clearfix"></div>
                        <div class="h5px"></div>
                        <?if ($content['substype']=="Bulanan") {
                            if ($content['isautoextend']==0) {
                                if (($content['minsubscriptionmonth']==0)||($content['minsubscriptionmonth']!=$content['priceseqtotal'])) {
                                    echo '<div class="formLeft">Lama Berlangganan</div>
                <div class="formRight">
                <select name="f_SUBSCRIPTIONMONTH" class="detailselect">';
                                    if (($content['minsubscriptionmonth']==0)) {
                                        echo '<option value="0" selected="selected" >sampai berhenti</option>';
                                        echo '<option value="1">1 &nbsp;&nbsp;bulan</option>
                <option value="3">3 &nbsp;&nbsp;bulan</option>
                <option value="6">6 &nbsp;&nbsp;bulan</option>
                <option value="9">9 &nbsp;&nbsp;bulan</option>
                <option value="12">12 bulan</option>';
                                    } else {
                                        if ($content['minsubscriptionmonth']>0) {
                                            for($i=$content['minsubscriptionmonth'];$i<=12;$i++) {
                                                echo '<option value="'.$i.'">'.$i.' bulan</option>';
                                            }
                                        }
                                    }
                                    echo '</select></div><div class="clearfix"></div>';
                                }
                            } else {
                                echo '<div class="formLeft">Lama Berlangganan</div><div class="formRight">
                                Layanan ini akan diperpanjang secara otomatis.</div><div class="clearfix"></div>';
                            }
                        } ?>
                        <?
                        if ($content['createcontentaccount']==1) {
                            ?>
                        <div class="h10px"></div>
                        <div id="tableHeaderHeading">
                            Masukkan User ID baru untuk akses ke konten
                        </div>
                        <div class="h5px"></div>
                        <div class="formLeft">Username (gunakan email)</div>
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
                            <?
                        }?>
                        <div class="h5px"></div>
                        <div id="tableHeaderHeading">
                            Data Pemesanan Anda
                        </div>
                        <div class="h5px"></div>
                        <div class="formLeft">Nama Perusahaan</div>
                        <div class="formRight"><input name="f_COMPANY" value="" maxlength="30" size="30" id="f_COMPANY" type="text" class="login" style="width:141px;text-transform:capitalize;"/></div>
                        <div class="clearfix"></div>
                        <div class="clearfix"></div>
                        <div class="h5px"></div>
                        <div class="formLeft">Jenis Usaha</div>
                        <div class="formRight"><input name="f_SERVICETYPE" value="" maxlength="30" size="30" id="f_SERVICETYPE" type="text" class="login" style="width:141px;text-transform:capitalize;"/></div>
                        <div class="clearfix"></div>
                        <div class="clearfix"></div>
                        <div class="h5px"></div>
                        <div class="formLeft">Alamat</div>
                        <div class="formRight"><input name="f_ADDRESS1" value="" maxlength="50" size="50" id="f_ADDRESS1" type="text" class="login" style="width:250px;text-transform:capitalize;"/></div>
                        <div class="clearfix"></div>
                        <div class="clearfix"></div>
                        <div class="formLeft">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                        <div class="formRight"><input name="f_ADDRESS2" value="" maxlength="50" size="50" id="f_ADDRESS2" type="text" class="login" style="width:250px;text-transform:capitalize;"/></div>
                        <div class="clearfix"></div>
                        <div class="clearfix"></div>
                        <div class="h5px"></div>
                        <div class="formLeft">Contact Person</div>
                        <div class="formRight"><input name="f_CONTACTPERSON" value="" maxlength="30" size="30" id="f_CONTACTPERSON" type="text" class="login" style="width:141px;text-transform:capitalize;"/></div>
                        <div class="clearfix"></div>                        
                        <div class="clearfix"></div>
                        <div class="h5px"></div>
                        <div class="formLeft">Telepon</div>
                        <div class="formRight"><input name="f_PHONE" value="" maxlength="15" size="15" id="f_PHONE" type="text" class="login" style="width:141px;text-transform:capitalize;"/></div>
                        <div class="clearfix"></div>
                        <div class="h5px"></div>
                        <div class="formLeft">Email</div>
                        <div class="formRight"><input name="f_EMAIL" value="" maxlength="50" size="50" id="f_EMAIL" type="text" class="login" style="width:141px;text-transform:lowercase"/></div>
                        <div class="clearfix"></div>
                        <div class="h5px"></div>
                        <div class="formLeft">Tindak lanjut via</div>
                        <div class="formRight">
                            <select name="f_FOLLOWUPTYPE" class="detailselect">';
                                    <option value="Telepon" selected="selected" >Telepon</option>
                                    <option value="Email">Email</option>
                            </select>
                            </div>
                        <div class="clearfix"></div>
                        <div class="formLeft">&nbsp;</div>
                        <div class="formRight termService"><input name="f_TOS" id="f_TOS" type="checkbox" value="Y" /> Menyetujui <a href="#">Terms of Service</a> layanan</div>
                        <div class="clearfix"></div>
                        <div class="h5px"></div>
                        <div class="formLeft">Kode keamanan</div>
                        <div class="formRight"><input name="f_CAPTCHA" id="f_CAPTCHA" value="" maxlength="25" size="25" type="text" class="login" style="width:141px;"/></div>
                        <div class="clearfix"></div>
                        <div class="h5px"></div>
                        <div class="formLeft">&nbsp;</div>
                        <div class="formRight"><div class="captcha"><img src="<?=$header['application']['baseurl'].'captcha.php'?>" /></div></div>

                        <div class="clearfix"></div>
                        <div class="h5px"></div>
                        <div style="border-top:1px solid #ddd; padding-top:5px;">
                        </div>
                        <div class="formLeft">&nbsp;</div>
                        <div class="formRight">
                            <input name="Pesan" type="button" onclick="javascript:etalase.speedysubmitorderbyemailform('div-speedy-order-form','form-speedy-trx-buy','<?=site_url()?>transaction/speedytrxorderbyemailcontent/f_CONTENTID/<?=$content['contentid']?>/')" value="Pesan" class="btnFormDetail"/>&nbsp;&nbsp;
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