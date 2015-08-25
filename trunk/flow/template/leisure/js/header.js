jQuery(function(){
    jQuery("#tags-head > ul").tabs( );
	jQuery("#tags-ha1").hrzAccordion( {openOnLoad: 3} );
	jQuery("#tags-ha2").hrzAccordion( {openOnLoad: 3} );
	jQuery("#tags-ha3").hrzAccordion( {openOnLoad: 4} );
	jQuery("#tags-ha4").hrzAccordion( {openOnLoad: 1} );
  });
function ScrollPicBack(obj){
        jQuery("#scrollPic").find("ul:first").animate({
                opacity:0
        },500,function(){
                jQuery(this).css({opacity:1}).find("li:last").prependTo(this);
        });
};
function ScrollPicForward(obj){
        jQuery("#scrollPic").find("ul:first").animate({
                opacity:0
        },500,function(){
                jQuery(this).css({opacity:1}).find("li:first").appendTo(this);
        });
		if(!scrollPause){
			scrolling = setTimeout("ScrollPicForward();",3000);
		}
};