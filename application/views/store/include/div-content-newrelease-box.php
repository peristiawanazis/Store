<table class="tb-new-release" bgcolor="#ffffff" width="100%" border="0px" cellpadding="10px" cellspacing="0x">
    <?php
    $isodd= true;
    $colnum= 0;
    //echo "<pre>";
    //print_r($newrelease);
    //echo "</pre>";
    $tblrowid= 0;
    foreach (array_keys($newrelease) as $key) {
        if ($colnum==0) {
            $tblrowid++;
            if ($isodd==true) {
                echo '<tr valign="top" class="odd-row" id="tb-new-release-'.$tblrowid.'">';
            } else {
                echo '<tr valign="top" class="even-row" id="tb-new-release-'.$tblrowid.'">';
            }
        }
        echo '<td width="75px" align="center">
                        <img src="'.$header['application']['baseurl'].'/content/images/'.$newrelease[$key]['contentimage'].'_small.png" width="75px" height="100px" class="content-newrelease-thumbnail"/>
                        </td>';
        if ($newrelease[$key]['circlerating']==0)
            $billcaption= "Biaya";
        else if ($newrelease[$key]['circlerating']==1) {
            $billcaption= "Biaya Bulanan";
        } else {
            $billcaption= "Biaya Bulanan dengan siklus ".$newrelease[$key]['circlerating']." Bulan";
        }
        echo '<td width="175px">
                <div class="content-description">
                        <span class="content-name">'.$newrelease[$key]['name'].'</span>
                        <span class="content-descript">'.$newrelease[$key]['shortdescript'].'</span>
                        <span class="content-attribute">
                        <span class="content-tag">Tipe</span>'.$newrelease[$key]['category'].'
                        <br /><span class="content-tag">Mitra</span>'.$newrelease[$key]['provider'].'
                        <br /><span class="content-tag">Cara Bayar</span>'.$newrelease[$key]['substype'].'
                        <br /><span class="content-tag">'.$billcaption.'</span>Rp. '.$newrelease[$key]['price'].'</span>';
        if (isset($newrelease[$key]['discount']))
            echo '<span class="content-discount">Discount</span>';

        echo '<div id="content-description-detail-'.$tblrowid.$colnum.'" class="content-description-detail mediumrounded"></div></div>';
        /*echo '<span class="content-button-view" onclick="javascript:etalase.viewdetailrow(\'newrelease-detail\',\''.$header['application']['controller'].'f_CONTENTID/'.$key.'/ajax/true/\',\'tb-new-release-'.$tblrowid.'\',\'4\')">Lihat</span>'.
                '<span class="content-button-buy" onclick="javascript:etalase.viewdetailrow(\'newrelease-detail\',\''.$header['application']['controller'].'f_CONTENTID/'.$key.'/ajax/true/\',\'tb-new-release-'.$tblrowid.'\',\'4\')">Beli</span>'.
                '<br /><br /></td>';
        */
        echo //'<span class="content-button-view" onclick="javascript:etalase.loadpage(\'content-description-detail-'.$tblrowid.$colnum.'\',\''.$header['application']['controller'].'showrowdetailcontent/f_CONTENTID/'.$key.'/ajax/true/\')">Lihat</span>'.
        '<span class="content-button-buy"><a class="popup" href="'.$header['application']['siteurl'].'transaction/speedygetcontentdetail/f_CONTENTID/'.$key.'/ajax/true/">Beli</a></span>'.
                '</td>';
        $colnum++;
        if ($colnum==2) {
            $isodd= !$isodd;
            echo '</tr>';
            $colnum= 0;
        }
    }
    if ($colnum!=2) {
        while ($colnum!=2) {
            echo '<td></td><td></td>';
            $colnum++;
        }
        echo '</tr>';
    }
    ?>
</table>
<script type="text/javascript">
    $(document).ready(function() {
        $(".popup").fancybox({
            'padding'			: 0,
            'margin'			: 10,
            'width'			: 743,
            'height'			: 830,
            'autoScale'			: false,
            'autoDimensions'    	: false,
            'transitionIn'		: 'none',
            'transitionOut'		: 'none',
            'type'				: 'iframe'
        });

    });
</script>
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