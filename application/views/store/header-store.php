<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Store.Telkomspeedy.com</title>
        <meta name="description" content="#" />
        <meta name="keywords" content="#" />
        <meta name="author" content="niweive.com" />

        <!-- SITE STYLE -->
        <link rel="stylesheet" type="text/css" media="screen, projection" href="<?=$application['baseurl']?>skin/default/css/speedystore.css" />
        <!-- JAVA SCRIPT -->
        <script type="text/javascript" src="<?=$application['baseurl']?>js/jquery.js"></script>
        <script type="text/javascript" src="<?=$application['baseurl']?>js/jquery.cycle.min.js"></script>
        <script type="text/javascript" src="<?=$application['baseurl']?>js/jquery.fancybox-1.3.4.pack.js"></script>
        <script type="text/javascript" src="<?=$application['baseurl']?>js/fancybox/jquery.easing-1.3.pack.js"></script>
        <script type="text/javascript" src="<?=$application['baseurl']?>js/bcds-1.01.js"></script>
        <script type="text/javascript" src="<?=$application['baseurl']?>js/jquery.alerts.js"></script>
        <link rel="stylesheet" type="text/css" href="<?=$application['baseurl']?>js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
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
            $('.slideshow').cycle({
                fx: 'fade', // choose your transition type, ex: fade, scrollUp, shuffle, etc...
                pager: '#pager',
                pagerAnchorBuilder: function(index, el) {
                    return '<a href="#">&nbsp;</a>'; // whatever markup you want
                }
            });
        </script>
        <style type="text/css">
            .slideshow { height: 180px; width: 440px; margin: auto }
            .slideshow img { }
        </style>
    </head>
    <body>
        <!-- HEADER -->
        <div id="header">
            <div id="wrapper">
                <div id="navigation">
                    <ul>
                        <li><a class="popup" href="<?=$application['siteurl']?>/about/faq/">FAQ</a></li>
                        <li><a class="popup" href="<?=$application['siteurl']?>/about/disclaimer/">Disclaimer</a></li>
                        <li><a class="popup" href="<?=$application['siteurl']?>/about/tos/">Term Of Service</a></li>
                        <li><a href="http://www.telkomspeedy.com" target="blank">Speedy lead your life !</a></li>s
                    </ul>
                </div>
            </div>
        </div>

        <!-- LOGO -->
        <div id="wrapper">
            <div class="h5px"></div>
            <div class="floatRight" style="width:135px; padding-top:1px;"><img src="<?=$application['baseurl']?>/skin/default/images/logo.png" width="135" height="54" alt="Speedy"/></div>
            <div class="floatLeft" style="padding-top:25px;"><a href="<?=$application['baseurl']?>"><img src="<?=$application['baseurl']?>/skin/default/images/text_url.png" width="261" height="23"/></a></div>
            <div class="clearfix"></div>
        </div>
        <div class="h5px"></div>
