siteurl= "http://store.telkomspeedy.com/store/index.php/";
baseurl= "http://store.telkomspeedy.com/store/";
/*navigator*/
var etalaseclass= function() {
    var etalase= {}
    etalase.viewdetailrow= function(containerrow,pageurl,currentrow,colspan) {
        //show loading icon
        $().ajaxError(function(error,response){
            if (error=="timeout")
                jAlert('There is problem with connection, timeout exceeded', 'E r r o r');
            else
                $('#'+containerrow).html(response.responseText);
            alert(pageurl);
        });
        if ($('#'+containerrow+'-row').length!=0)
            $('#'+containerrow+'-row').remove();
        $('<tr id="'+containerrow+'-row" class="table-row-view-detail"><td colspan="'+colspan+'"><div id="'+containerrow+'">loading...</div></td></tr>').insertAfter('#'+currentrow);
        $('#'+containerrow).load(pageurl+'dummycache/'+new Date().getTime(),
            function() {
                //hide loading icon
                null;
            });
    }
    etalase.unsubsmycontent= function(container,page_url,loaderstatus) {
        jConfirm('Anda yakin akan berhenti menikmati layanan Content ini ?', '', function(r) {
            if (r) {
                $('#'+container).html('<br /><img src="'+baseurl+'/skin/default/images/ajax-loading.gif">');
                $('#'+container).load(page_url+'dummycache/'+new Date().getTime(),
                    function() {
                        $().ajaxError(function(error,response){
                            if (error=="timeout")
                                jAlert('There is problem with connection, timeout exceeded', '');
                            else
                                jAlert(response.responseText, '');
                            $('#'+loaderstatus).hide();
                        });
                    });
            } else {
        }
        });
    }
    etalase.loadpage= function (container,page_url) {
        if ((container==undefined)||(container=='')) {
            jAlert('Container is not available', 'E r r o r');
            return;
        }
        $('#'+container).html('<br /><img src="'+baseurl+'/skin/default/images/ajax-loading.gif">');
        //check if container is already available
        $().ajaxError(function(error,response){
            if (error=="timeout")
                jAlert('There is problem with connection, timeout exceeded', 'E r r o r');
            else
                jAlert(response.responseText, 'E r r o r');
        });
        //$('#'+ container +' #wg-status #wg-ajaxloader').show(); //use ajax-loading window status
        //alert('#body-form-'+ container +' #wg-status #wg-ajaxloader');
        $('#'+container).load(page_url+'dummycache/'+new Date().getTime(),
            function(){
                $('#'+ container +' #wg-status #wg-ajaxloader').hide();
            });

    }
    etalase.speedysubmitloginform= function (container,form,page_url) {
        var param = $('#'+form).serialize();
        //alert (param);
        if (($('#'+form+' #f_SPEEDYACCOUNT').val()=='')||($('#'+form+' #f_PHONEHOME').val()=='')||($('#'+form+' #f_CAPTCHA').val()=='')) {
            jAlert('Pastikan semua field telah diisi.','', function(r) {});
            return;
        } else {
            $('#'+container).html('<br /><img src="'+baseurl+'/skin/default/images/ajax-loading.gif">');
            //alert(page_url);
            $.post(page_url,param,function(data) {
                //alert('test: '+data);
                $('#'+container).html(data);
            });
        }
        $().ajaxError(function(error,response){
            if (error=="timeout")
                jAlert('There is problem with connection, timeout exceeded','');
            else
                jAlert(response.responseText,'');
        });
    }
    etalase.speedysubmitform= function (container,form,page_url) {
        var param = $('#'+form).serialize();
        //alert (param);
        jConfirm('Anda yakin akan melakukan transaksi pembelian konten dengan menagihkan di Speedy ?','', function(r) {
            if (r) {
                if (($('#'+form+' #f_TOS').is(':checked')!=true)||($('#'+form+' #f_CAPTCHA').val()=='')||($('#'+form+' #f_SPEEDYACCOUNT').val()=='')||($('#'+form+' #f_PHONEHOME').val()=='')||($('#'+form+' #f_EMAIL').val()=='')||($('#'+form+' #f_SUBSCRIPTIONMONTH').val()=='')) {
                    jAlert('Pastikan semua field telah diisi.','', function(r) {});
                    return;
                } else {
                    if ($('#'+form+' #f_PASSWDCONTENT').length!=0) {
                        if ((($('#'+form+' #f_PASSWDCONTENT').val()).length<5)||(($('#'+form+' #f_PASSWDCONTENT').val()!=$('#'+form+' #f_PASSWDCONTENT1').val()))) {
                            jAlert('Konfirmasi password Anda salah dan pastikan panjangnya minimal 5 karakter.','', function(r) {});
                            return;
                        } else if (($('#'+form+' #f_USERIDCONTENT').val()=='')||($('#'+form+' #f_USERIDCONTENT').val().length<5)) {
                            jAlert('Pastikan User ID telah diisi dan panjangnya minimal 5 karakter.','', function(r) {});
                            return;
                        }
                    }
                    if ((param==undefined)||(param=='')) { //param is '' when There is no available submited form data
                        $('#'+container).load(page_url+'dummycache/'+new Date().getTime(),
                            function() {
                            });
                    } else {
                        //alert(param);
                        $('#'+container).html('<br /><img src="'+baseurl+'/skin/default/images/ajax-loading.gif">');
                        $.post(page_url,param,function(data) {
                            $('#'+container).html(data);
                        });
                    }
                }
            } 
        });
        $().ajaxError(function(error,response){
            if (error=="timeout")
                jAlert('There is problem with connection, timeout exceeded', 'E r r o r');
            else
                jAlert(response.responseText, 'E r r o r');
        });
    }
    etalase.speedysubmitorderbyemailform= function (container,form,page_url) {
        var param = $('#'+form).serialize();
        //alert (param);
        jConfirm('Anda yakin akan melakukan pemesanan layanan ini ?','', function(r) {
            if (r) {
                if (($('#'+form+' #f_TOS').is(':checked')!=true)||($('#'+form+' #f_CAPTCHA').val()=='')||($('#'+form+' #f_COMPANY').val()=='')||($('#'+form+' #f_SERVICETYPE').val()=='')||($('#'+form+' #f_ADDRESS1').val()=='')||($('#'+form+' #f_CONTACTPERSON').val()=='')||($('#'+form+' #f_PHONE').val()=='')||($('#'+form+' #f_EMAIL').val()=='')||($('#'+form+' #f_FOLLOWUPTYPE').val()=='')) {
                    jAlert('Pastikan semua field telah diisi.','', function(r) {});
                    return;
                } else {
                    if ($('#'+form+' #f_PASSWDCONTENT').length!=0) {
                        if ((($('#'+form+' #f_PASSWDCONTENT').val()).length<5)||(($('#'+form+' #f_PASSWDCONTENT').val()!=$('#'+form+' #f_PASSWDCONTENT1').val()))) {
                            jAlert('Konfirmasi password Anda salah dan pastikan panjangnya minimal 5 karakter.','', function(r) {});
                            return;
                        } else if (($('#'+form+' #f_USERIDCONTENT').val()=='')||($('#'+form+' #f_USERIDCONTENT').val().length<5)) {
                            jAlert('Pastikan User ID telah diisi dan panjangnya minimal 5 karakter.','', function(r) {});
                            return;
                        }
                    }
                    if ((param==undefined)||(param=='')) { //param is '' when There is no available submited form data
                        $('#'+container).load(page_url+'dummycache/'+new Date().getTime(),
                            function() {
                            });
                    } else {
                        //alert(param);
                        $('#'+container).html('<br /><img src="'+baseurl+'/skin/default/images/ajax-loading.gif">');
                        $.post(page_url,param,function(data) {
                            $('#'+container).html(data);
                        });
                    }
                }
            } 
        });
        $().ajaxError(function(error,response){
            if (error=="timeout")
                jAlert('There is problem with connection, timeout exceeded', 'E r r o r');
            else
                jAlert(response.responseText, 'E r r o r');
        });
    }
    return etalase;
}
var etalase= new etalaseclass();

