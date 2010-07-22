jQuery(document).ready(function($) {

    $('#colorSelectorFlash div').css('backgroundColor',$('#colorFlash').val());
    $('#colorSelectorFlash').ColorPicker({
        onChange: function (hsb, hex, rgb) {
		$('#colorSelectorFlash div').css('backgroundColor', '#' + hex);
                $('#colorFlash').val('#' + hex);
	}

    });

    $('#colorSelectorFleches div').css('backgroundColor',$('#colorFleches').val());
    $('#colorSelectorFleches').ColorPicker({
        onChange: function (hsb, hex, rgb) {
		$('#colorSelectorFleches div').css('backgroundColor', '#' + hex);
                $('#colorFleches').val('#' + hex);
	}

    });

    $('#colorSelectorBordure div').css('backgroundColor',$('#colorBordure').val());
    $('#colorSelectorBordure').ColorPicker({
        onChange: function (hsb, hex, rgb) {
		$('#colorSelectorBordure div').css('backgroundColor', '#' + hex);
                $('#colorBordure').val('#' + hex);
	}

    });

    $('#colorSelectorPolice div').css('backgroundColor',$('#colorPolice').val());
    $('#colorSelectorPolice').ColorPicker({
        onChange: function (hsb, hex, rgb) {
		$('#colorSelectorPolice div').css('backgroundColor', '#' + hex);
                $('#colorPolice').val('#' + hex);
	}

    });

    $('#colorSelectorFooter div').css('backgroundColor',$('#colorFooter').val());
    $('#colorSelectorFooter').ColorPicker({
        onChange: function (hsb, hex, rgb) {
		$('#colorSelectorFooter div').css('backgroundColor', '#' + hex);
                $('#colorFooter').val('#' + hex);
	}

    });
	
	//send ajax request to check email
	$('#userMail').change(function(){
		$.ajax({
			type: "POST",
			processData: false,
			data: "email="+$(this).attr("value"),
			url: pluginUrl+"checkMail.php",
			beforeSend: function(){
				$("#iconValidUserMail").css('background-image', 'url(\''+pluginUrl+'includes/css/images/loader.gif\')');
				$("#validUserMail").css('display', 'none');
				$("#validUserMail").html("");
			},
			success: function(data){
				$('#iconValidUserMail').html("");
				if(data == "invalid" || data == "notexists")
				{
					$("#iconValidUserMail").css('background-image', 'url(\''+pluginUrl+'includes/css/images/validno.png\')');
					
					if(data == "invalid")
						$("#validUserMail").html("Invalid format");
					else
						$("#validUserMail").html("Subscribe on the <a href=\"http://www.netglobers.com\" target=\"_blank\" >NetGlobers</a> website");
						
					$("#validUserMail").css('display', '');
				}
				else
				{
					$("#iconValidUserMail").css('background-image', 'url(\''+pluginUrl+'includes/css/images/validyes.png\')');
					$("#validUserMail").css('display', 'none');
					$("#validUserMail").html("");
				}
			}
		});
	});
});




