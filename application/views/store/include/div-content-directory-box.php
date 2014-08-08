<div id="headerContent">
    <div id="contentNav" class="dirnavs">
        <ul>
            <li><a href="#content-newrelease-box">Terbaru</a></li>
            <li><a href="#content-list-box">Semua Konten</a></li>
            <li><a href="#content-mycontent-box">Kontenku</a></li>
        </ul>
    </div>
    <!--<div style="padding-top:6px;" class="floatRight"><input name="" type="text" class="search"/><input name="Search" type="submit" value="Search" class="btnSearch"/></div>-->
    <div class="clearfix"></div>
</div>
<div id="bodyContent">
    <div id="content-newrelease-box" class="dirtabs">
        <br />
        <img src="<?=$application['baseurl']?>skin/default/images/ajax-loading.gif">
        <script language="JavaScript">
            $('#content-newrelease-box').load('<?=$application['siteurl']?>content/getcontentnewrelease/ajax/true/');
        </script>
    </div>
    <div id="content-list-box" class="dirtabs">
        <br />
        <img src="<?=$application['baseurl']?>skin/default/images/ajax-loading.gif">
        <script>
            $('#content-list-box').load('<?=$application['siteurl']?>content/getcontentlist/ajax/true/');
        </script>
    </div>
    <div id="content-mycontent-box" class="dirtabs" style="padding:10px;">
        <br /><img src="<?=$application['baseurl']?>skin/default/images/ajax-loading.gif">
        <script>$('#content-mycontent-box').load('<?=$application['siteurl']?>content/getmycontentlist/ajax/true/');</script>
    </div>
    <script type='text/javascript'>
        $(".dirtabs").hide(); //Hide all content
        $(".dirnavs li:first").addClass("contentNavSelected").show(); //Activate first tab
        $(".dirtabs:first").show(); //Show first tab content

        //On Click Event
        $(".dirnavs li").click(function() {
            $(".dirnavs li").removeClass("contentNavSelected"); //Remove any "active" class
            $(this).addClass("contentNavSelected"); //Add "active" class to selected tab
            $(".dirtabs").hide(); //Hide all tab content
            var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
            $(activeTab).fadeIn('slow',function() {
            }); //Fade in the active ID content
            
        });
    </script>
</div>

<div><img src="<?=$application['baseurl']?>/skin/default/images/main_footer.png" width="629" height="9" alt="footer" /></div>