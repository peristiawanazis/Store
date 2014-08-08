<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/
if ($islogin==true) {
    //print_r($sessions);
    if ($sessions[0]=="SPEEDY") {
        if (count($subscriptions)>0) {
            echo '<table width="100%">
                <tr style="background:#666;height:30px;border-bottom=1px solid #aaa;font-weight: bold;color:#fff;">
                <td width="30px">#</td>
                <td width="150px">CONTENT</td>
                <td width="150px">EMAIL/HOST <BR />ASAL TRANSAKSI</td>
                <td width="150px">STATUS <br />BERLANGGANAN</td>
                <td align="center">AKTIVASI</td>
                </tr>';
            $i= 1;
            foreach ($subscriptions as $row) {
                $command= "";
                if ($row['SUBSCRIPTIONSTATUSID']=='S') { //sudah berlangganan / aktif
                    if ($row['SUBSCRIPTIONTYPEID']=="CBC") {
                        $row['SUBSCRIPTIONSTATUS']= "Sekali Bayar";
                    } else {
                        $command= '<a  href="javascript:etalase.unsubsmycontent(\'content-mycontent-box\',\''.$header['application']['siteurl'].'transaction/speedyunsubscontent/f_SUBSCRIPTIONID/'.$row['SUBSCRIPTIONID'].'/\',null)" class="mycontent-command">berhenti</a>';
                    }
                } elseif ($row['SUBSCRIPTIONSTATUSID']=='NA') {
                    //$command= "Klik email aktifasi di <h3>".strtolower($row['EMAILACCOUNT'])."</h3>";
                    $command= 'Aktifkan dgn klik url aktivasi di email Anda.';
                } elseif ($row['SUBSCRIPTIONSTATUSID']=='NS') { //sudah berlangganan / aktif
                    if ($row['SUBSCRIPTIONTYPEID']=="CBC") {
                        $row['SUBSCRIPTIONSTATUS']= "Sekali Bayar";
                        $command= 'Hanya sekali bayar.';
                    } else
                        $command= 'Telah berhenti.';
                }
                if ($i%2==0) {
                    if ($row['SUBSCRIPTIONSTATUS']=='Belum Aktif') {
                        echo '<tr style="background:#fee;height:45px;border-bottom=1px solid #666;">';
                    } else {
                        echo '<tr style="background:#eaeaea;height:45px;border-bottom=1px solid #666;">';
                    }
                } else {
                    if ($row['SUBSCRIPTIONSTATUS']=='Belum Aktif') {
                        echo '<tr style="background:#fdd;height:45px;border-bottom=1px solid #666;">';
                    } else {
                        echo '<tr style="background:#e0e0e0;height:45px;border-bottom=1px solid #666;">';
                    }
                }
                echo '
                    <td align="center">'.$i.'.&nbsp;</td>
                    <td>'.$row['CONTENT'].'</td>
                    <td>'.$row['EMAILACCOUNT'].'<br />dari host: '.$row['HOSTIP'].'</td>
                    <td>'.$row['SUBSCRIPTIONSTATUS'].'<br />'.$row['SUBSCRIPTIONTYPE'].'<br />'.$row['SUBSSTARTDATE'].'</td>
                    <td align="right">'.$command.'</td>
                    </tr>';
                $i++;
            }
            echo '</table>';

        }
    }
    ?>
    <?
} else {
    ?>
<div id="tableHeaderHeading">Berlangganan Layanan Content</div>
<div class="clearfix"></div>
<div class="h5px"></div>
<div class="mycontent-formLeft">1.</div>
<div class="mycontent-formRight">Pilih konten yang disukai, cek harga dan diskonnya untuk mendapatkan harga terbaik.</div>
<div class="clearfix"></div>
<div class="h5px"></div>
<div class="mycontent-formLeft">2.</div>
<div class="mycontent-formRight">Login dengan menggunakan Nomor Speedy dan Nomor Telepon Rumah atau lakukan dengan langsung klik tombol Beli.
    <br />Transaksi pembelian dengan menggunakan Speedy dijamin keamananya karena hanya dapat dilakukan dari Rumah Anda.</div>
<div class="clearfix"></div>
<div class="h5px"></div>
<div class="mycontent-formLeft">3.</div>
<div class="mycontent-formRight">Masukkan data pembelian untuk melengkapi transaksi pembelian Anda.</div>
<div class="clearfix"></div>
<div class="h5px"></div>
<div class="mycontent-formLeft">4.</div>
<div class="mycontent-formRight">Agar layanan content dapat digunakan, aktivasi transaksi pembelian Anda dengan klik url aktivasi di email yang Anda daftarkan.</div>
<div class="clearfix"></div>

<div class="h10px"></div>
<div class="h10px"></div>
<div class="h10px"></div>
<div id="tableHeaderHeading">Berhenti Layanan Content
</div>
<div class="clearfix"></div>
<div class="h5px"></div>
<div class="mycontent-formLeft">1.</div>
<div class="mycontent-formRight">Login dengan menggunakan Nomor Speedy Anda dan Nomor Telepon Rumah, kemudian pilih
    menu My Content untuk melihat data berlangganan konten Anda.</div>
<div class="clearfix"></div>
<div class="h5px"></div>
<div class="mycontent-formLeft">2.</div>
<div class="mycontent-formRight">Pilih data berlangganan konten Anda, untuk data berlangganan yang masih aktif terdapat tombol Berhenti, klik tombol Berhenti untuk berhenti berlangganan.</div>
<div class="clearfix"></div>
<div class="h5px"></div>
<div class="mycontent-formLeft">3.</div>
<div class="mycontent-formRight">Informasi pemberhentian layanan konten Anda akan ditampilkan dan pastikan bahwa informasi yang ditampilkan pemberhentian layanan telah sukses.</div>
<div class="clearfix"></div>
<div class="h5px"></div>
    <?
}
?>
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