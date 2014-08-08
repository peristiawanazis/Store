<div id="tableHeaderHeading">
    Pemesanan
</div>
<div class="h5px"></div>
<div class="formLeft">Nama Konten</div>
<div class="formRight"><?=$contentparams['NAME']?></div>
<div class="clearfix"></div>
<div class="h5px"></div>
<div class="formLeft">Tipe Konten</div>
<div class="formRight"><?=$contentparams['CATEGORY']?></div>
<div class="clearfix"></div>
<div class="h5px"></div>
<div class="formLeft">Mitra Provider</div>
<div class="formRight"><?=$contentparams['PROVIDER']?></div>
<div class="clearfix"></div>
<div class="h5px"></div>
<?if ($contentparams['SUBSCRIPTIONTYPE']=="SBC") {
    if ($trxparams['SUBSCRIPTIONMONTH']==0) {
        echo '<div class="formLeft">Lama Berlangganan</div>
                <div class="formRight">';
        if (($trxparams['SUBSCRIPTIONMONTH']==0)) {
            echo 'Sampai Berhenti';

        } else {
            echo $trxparams['SUBSCRIPTIONMONTH'].' Bulan</option>';
        }
        echo '</div><div class="clearfix"></div>';
    }
} ?>
<?
if ($contentparams['CREATECONTENTACCOUNT']==1) {
    ?>
<div class="h10px"></div>
<div id="tableHeaderHeading">
    User ID baru untuk akses ke konten
</div>
<div class="h5px"></div>
<div class="formLeft">Username</div>
<div class="formRight">
        <?=$trxparams['USERIDCONTENT']?></div>
<div class="clearfix"></div>
<div class="h5px"></div>
<div class="formLeft">Password</div>
<div class="formRight"></div>
<div class="clearfix"></div>
    <?
}?>
<div class="h5px"></div>
<div id="tableHeaderHeading">
    Pembayaran dengan Postpaid Speedy
</div>
<?
$trxparams['SPEEDYACCOUNT'][8]= "X";
$trxparams['SPEEDYACCOUNT'][9]= "X";
$trxparams['SPEEDYACCOUNT'][10]= "X";
$trxparams['SPEEDYACCOUNT'][7]= "X";
$trxparams['SPEEDYACCOUNT'][6]= "X";
//$mdnaccount[0]= "X";
//$mdnaccount[1]= "X";
$trxparams['MDNNUMBER'][2]= "X";
$trxparams['MDNNUMBER'][3]= "X";
$trxparams['MDNNUMBER'][4]= "X";
$trxparams['MDNNUMBER'][5]= "X";
?>
<div class="h5px"></div>
<div class="formLeft">No. Speedy</div>
<div class="formRight"><?=$trxparams['SPEEDYACCOUNT']?></div>
<div class="clearfix"></div>
<div class="h5px"></div>
<div class="formLeft">No. Telp Rumah</div>
<div class="formRight"><?=$trxparams['MDNNUMBER']?></div>
<div class="clearfix"></div>

<div class="h5px"></div>
<div class="formLeft">Email</div>
<div class="formRight"><?=$trxparams['EMAILACCOUNT']?></div>
<div class="h10px"></div>
<div style="border-bottom:1px solid #ddd; padding-top:5px;"></div>
<div class="h10px"></div>
<div style="border-bottom:1px solid #ddd;padding:5px;"><?=$error_mesg?></div>