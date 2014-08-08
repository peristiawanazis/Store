<?
if ($islogin==false) {
    ?>
<form id="form-speedy-login">
    <div class="h10px"></div>
    <img src="<?=$header['application']['baseurl']?>/skin/default/images/txt_mycontentlogin.png" width="141" height="16" alt="My Content Login" />
    <div class="h5px"></div>
    <div class="title">Nomor Speedy:</div>
    <div class="h5px"></div>
    <div class="floatLeft"><input name="f_SPEEDYACCOUNT" value="" maxlength="12" size="25" id="f_SPEEDYACCOUNT" type="text" class="login" style="width:141px;"/></div>
    <div class="clearfix"></div>
    <div class="h5px"></div>
    <div class="title">Nomor Telepon Rumah:</div>
    <div class="h5px"></div>
    <div><input name="f_PHONEHOME" value="" maxlength="25" size="25" id="f_PHONEHOME" type="text" class="login" style="width:141px;"/></div>
    <div class="h5px"></div>
    <div class="title">Masukkan Kode Captcha dibawah:</div>
    <div class="h5px"></div>
    <div><input name="f_CAPTCHA" value="" maxlength="25" size="25" id="f_CAPTCHA" type="text" class="login" style="width:141px;"/></div>
    <div class="h5px"></div>
    <div onclick="{$(this).html('<img src=<?="\'".$header['application']['baseurl'].'captcha.php?'."'+new Date().getTime()+'\'"?>/>');}" class="captcha" style="cursor: pointer;"><img src="<?=$header['application']['baseurl'].'captcha.php'?>" /></div>
    <div class="h5px"></div>
        <? if ($iserror==true) {?>
    <div class="form-speedy-login-error">
                <?=@$error_mesg?>
    </div>
            <?
        }?>
    <div class="h10px"></div>
    <input name="login" type="button" value="LOGIN" class="login-button"
           onclick="javascript:etalase.speedysubmitloginform('login-box','form-speedy-login','<?=$header['application']['siteurl']?>welcome/signin/')" />
    <div class="h10px"></div>
</form>
    <?
} else {
    $speedyaccount= @$sessions[1];
    $mdnaccount= @$sessions[2];
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
    $timestamp= @$sessions[3];
    ?>
<form id="form-speedy-login">
    <div class="h5px"></div>
    <div class="form-speedy-login-succeed">Login telah berhasil, dengan menggunakan Nomor Speedy dan Telepon Rumah.
        <br />Pastikan Anda logout ketika meninggalkan komputer.</div>
    <div class="h5px"></div>
    <div class="logininfo"><?=$speedyaccount?></div>
    <div class="logininfo"><?=$mdnaccount?></div>
    <div class="logininfo"><?=$this->input->ip_address()?></div>
    <div class="h5px"></div>
    <div class="h10px"></div>
    <input name="login" type="button" value="LOGOUT" class="login-button"
           onclick="javascript:etalase.speedysubmitloginform('login-box','form-speedy-login','<?=$header['application']['siteurl']?>welcome/signout/')" />
    <div class="h10px"></div>
</form>
    <?
}
?>
<script>$('#content-mycontent-box').load('<?=$header['application']['siteurl']?>content/getmycontentlist/ajax/true/');</script>