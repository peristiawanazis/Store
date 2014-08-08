<table class="tb-list-box" width="100%" border="0px" cellpadding="10px" cellspacing="1px">
    <?php
    $isodd= true;
    $colnum= 0;
    //echo "<pre>";
    //print_r($contentlist);
    //echo "</pre>";
    $tblrowid= 0;
    foreach (array_keys($contentlist) as $key) {
        if ($colnum==0) {
            $tblrowid++;
            if ($isodd==true) {
                echo '<tr valign="top" class="odd-row" id="tb-list-box-row-'.$tblrowid.'">';
            } else {
                echo '<tr valign="top" class="even-row" id="tb-list-box-row-'.$tblrowid.'">';
            }
        }
        echo '<td width="75px" align="center">
                        <img src="'.$header['application']['baseurl'].'content/images/'.$contentlist[$key]['contentimage'].'_small.png" width="75px" height="100px" class="content-contentlist-thumbnail"/>
                        </td>';
        if ($contentlist[$key]['circlerating']==0)
            $billcaption= "Biaya";
        else if ($contentlist[$key]['circlerating']==1) {
            $billcaption= "Biaya Bulanan";
        } else {
            $billcaption= "Biaya Bulanan / ".$contentlist[$key]['circlerating']." Bln";
        }
        echo '<td width="175px">
                <div class="content-description">
                        <span class="content-name">'.$contentlist[$key]['name'].'</span>
                        <span class="content-descript">'.$contentlist[$key]['shortdescript'].'</span>
                        <span class="content-attribute">
                        <span class="content-tag">Tipe</span>'.$contentlist[$key]['category'].'
                        <br /><span class="content-tag">Mitra</span>'.$contentlist[$key]['provider'].'
                        <br /><span class="content-tag">Cara Bayar</span>'.$contentlist[$key]['substype'].'
                        <br /><span class="content-tag">'.$billcaption.'</span>Rp. '.$contentlist[$key]['price'].'</span>';
        if (isset($contentlist[$key]['discount']))
            echo '<span class="content-discount">Discount</span>';
        echo '<div id="content-listbox-detail-'.$tblrowid.$colnum.'" class="content-description-detail mediumrounded"></div></div>';
        echo //'<span class="content-button-view" onclick="javascript:etalase.loadpage(\'content-listbox-detail-'.$tblrowid.$colnum.'\',\''.$header['application']['baseurl'].'showrowdetailcontent/f_CONTENTID/'.$key.'/ajax/true/\')">Lihat</span>'.
        '<span class="content-button-buy"><a class="popup" href="'.$header['application']['siteurl'].'transaction/speedygetcontentdetail/f_CONTENTID/'.$key.'/ajax/true/">Beli</a></span>'.
                '</td>';
        $colnum++;
        if ($colnum==2) {
            $isodd= !$isodd;
            echo '</tr>';
            $colnum= 0;
        }
    }
    if (($colnum!=2)&&($colnum!=0)) {
        while ($colnum!=2) {
            echo '<td width="75px"></td><td  width="175px"></td>';
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
<?
$this->pagination->initialize($this->page['application']['paging']);
$this->page['application']['paging']['canvas']= $this->pagination->create_links();
echo $this->page['application']['paging']['canvas'];
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