jQuery(document).ready(function($) {

     var ecart = 30;
     var bgColorDiv = flashvars.bgcolor.replace('0x','#');
     var widthBox = flashvars.flashsize+ecart;

     var heightFlash = flashvars.flashsize-20;
     var bordure = 5;
     var footerHeight = $('#netglobersFooter').height();
	 var headerHeight = footerHeight;
     var tailleDiv = heightFlash+ (bordure*2) + (footerHeight-bordure) + (headerHeight-bordure);

    $('#netglobersBox').css("width",widthBox);
    $('#netglobersBox').css("height",tailleDiv);
    $('#netglobersBox').css("background-color",''+bgColorDiv);
    $('#netglobersBox').css("border",bordure+"px solid "+borderColor);

	$('#netglobersHeader').css("width",flashvars.flashsize+ecart);
    $('#netglobersHeader').css("background-color",''+footerColor);
	
    $('#netglobersFooter').css("width",flashvars.flashsize+ecart);
    $('#netglobersFooter').css("background-color",''+footerColor);


	$('#pHeader').css("color",fontColor);
    $('#txtMore').css("color",fontColor);
    $('#pFooter').css("color",fontColor);
	
	 /* Calage horizontal du tooltip  */

    var paddingLeftTooltip = parseInt($('#tooltip').css('padding-left').replace('px',''));
    var marginLeftTooltip =  ((widthBox - $('#tooltip').width())  / 2) -  paddingLeftTooltip;
    $('#tooltip').css('margin-left',marginLeftTooltip);

    /* Calage vertical du tooltip */

    var paddingTopTooltip = parseInt($('#tooltip').css('padding-top').replace('px',''));
    var paddingBottomTooltip = parseInt($('#tooltip').css('padding-bottom').replace('px',''));     
    var marginTopTooltip =  tailleDiv - ($('#tooltip').height()+paddingTopTooltip+paddingBottomTooltip+footerHeight+headerHeight);
    $('#tooltip').css('margin-top',marginTopTooltip);


    /* Gestion des évenements du tooltip */

    $(".togglerTooltip").each(function(i,el){
        $(el).click(function(event) {
            event.preventDefault();
            var displayActual = $('#tooltip').css("display");
            switch(displayActual) {
                case 'none':
                    $('#tooltip').fadeIn(400);					
                    break;
                case 'block':
                    $('#tooltip').fadeOut(400);
					break;
            };
        });
	
		$(el).mouseover(function(event) {
            event.preventDefault();
            var displayActual = $('#tooltip').css("display");
            if(displayActual == 'none')
			{
                $('#tooltip').fadeIn(400);
            };
        });
    });
	
	$('#tooltip').mouseleave(function(event) {
        event.preventDefault();
        var displayActual = $('#tooltip').css("display");
        if(displayActual == 'block')
		{
            $('#tooltip').fadeOut(400);
        };
    });
		
	/* Affichage du flash */

    swfobject.embedSWF(urlFlash, "contentMap",flashvars.flashsize,heightFlash, "10.0.0",null,flashvars, params);

   

});


