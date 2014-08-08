<div id="tableHeaderHeading">
    Nama Layanan
</div>
<div class="h5px"></div>
<div class="formLeft">Nama Konten</div>
<div class="formRight"><?= @$contentparams['NAME'] ?></div>
<div class="clearfix"></div>
<div class="h5px"></div>
<div class="formLeft">Tipe Konten</div>
<div class="formRight"><?= @$contentparams['CATEGORY'] ?></div>
<div class="clearfix"></div>
<div class="h5px"></div>
<div class="formLeft">Mitra Provider</div>
<div class="formRight"><?= @$contentparams['PROVIDER'] ?></div>
<div class="clearfix"></div>
<div class="h5px"></div>
<?
if ($contentparams['SUBSCRIPTIONTYPE'] == "SBC") {
    if ($trxparams['SUBSCRIPTIONMONTH'] == 0) {
        echo '<div class="formLeft">Lama Berlangganan</div>
                <div class="formRight">';
        if (($trxparams['SUBSCRIPTIONMONTH'] == 0)) {
            echo 'Sampai Berhenti';
        } else {
            echo $trxparams['SUBSCRIPTIONMONTH'] . ' Bulan</option>';
        }
        echo '</div><div class="clearfix"></div>';
    }
}
?>
<?
if ($contentparams['CREATECONTENTACCOUNT'] == 1) {
    ?>
    <div class="h10px"></div>
    <div id="tableHeaderHeading">
        User ID baru untuk akses ke konten
    </div>
    <div class="h5px"></div>
    <div class="formLeft">Username</div>
    <div class="formRight">
    <?= @$trxparams['USERIDCONTENT'] ?></div>
    <div class="clearfix"></div>
    <div class="h5px"></div>
    <div class="formLeft">Password</div>
    <div class="formRight"></div>
    <div class="clearfix"></div>
    <? } ?>
<div class="h5px"></div>
<div id="tableHeaderHeading">
    Data Pemesanan Anda
</div>
<div class="h5px"></div>
<div class="formLeft">Nama Perusahaan</div>
<div class="formRight"><?= @$trxparams['COMPANY'] ?></div>
<div class="clearfix"></div>
<div class="h5px"></div>
<div class="formLeft">Jenis Usaha</div>
<div class="formRight"><?= @$trxparams['SERVICETYPE'] ?></div>
<div class="clearfix"></div>
<div class="h5px"></div>
<div class="formLeft">Alamat</div>
<div class="formRight"><?= @$trxparams['ADDRESS1'] ?></div>
<div class="clearfix"></div>
<div class="clearfix"></div>
<div class="formLeft">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
<div class="formRight"><?= @$trxparams['ADDRESS2'] ?></div>
<div class="clearfix"></div>
<div class="h5px"></div>
<div class="formLeft">Contact Person</div>
<div class="formRight"><?= @$trxparams['CONTACTPERSON'] ?></div>
<div class="clearfix"></div>
<div class="h5px"></div>
<div class="formLeft">Telepon</div>
<div class="formRight"><?= @$trxparams['PHONE'] ?></div>
<div class="clearfix"></div>
<div class="h5px"></div>
<div class="formLeft">Email</div>
<div class="formRight"><?= @$trxparams['EMAILACCOUNT'] ?></div>
<div class="h10px"></div>
<div style="border-bottom:1px solid #ddd; padding-top:5px;"></div>
<div class="h10px"></div>
<div style="border-bottom:1px solid #ddd;padding:5px;"><?= $error_mesg ?></div>
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