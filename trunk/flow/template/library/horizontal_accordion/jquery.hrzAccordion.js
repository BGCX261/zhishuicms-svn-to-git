(function($) {
	$.hrzAccordion = {
       
	   setOnClick: function(i, container, finalWidth, settings){
			$("#"+container+"Handle"+i).bind("click",function() {
			  
			   			if( $("#"+container+"Handle"+i).attr("rel") != "selected"){
			    			$('[@id*='+container+'Handle]').attr("rel","");
			   
			   				$('[@id*='+container+'Handle]').attr("class",settings.handleClass);
			   				$("#"+container+"Handle"+i).addClass(settings.handleClassSelected);
												   
			   				$('div[@id*='+container+'Content]').animate({className:settings.contentStartClass},
																		settings.closeEaseSpeed,
																		settings.closeEaseAction);					
			   				
			   				$("."+settings.contentWrapper).css({width: finalWidth });
						$('#'+container+'Content'+i).animate({className:settings.contentEndClass, width: finalWidth }, 
																 		settings.openEaseSpeed, 
																		settings.openEaseAction);
			   				$("#"+container+"Handle"+i).attr("rel","selected");
							$("."+settings.contentWrapper).css({width: finalWidth });
						
							
						}
						
					});	
}
	    };
	$.fn.extend({
	
		hrzAccordion: function(options) {
			this.settings = {
			containerClass     : "hrzContainer",
			listItemClass      : "listItem",					
			contentStartClass  : "contentStart",
			contentEndClass    : "contentEnd",
			contentWrapper     : "contentWrapper",
			contentInnerWrapper: "contentInnerWrapper",
			handleClass        : "handle",
			handleClassOver    : "handleOver",
			handleClassSelected: "handleSelected",
			handlePosition     : "left",
			closeEaseAction    : "backin",
			closeEaseSpeed     : 500,
			openEaseAction     : "easein",
			openEaseSpeed      : 500,
			openOnLoad		   : 1
		};
	
		if(options)
			$.extend(this.settings, options);
			var settings = this.settings;
			
			return this.each(function(a){
    		
				
				var container = $(this).attr("id") || $(this).attr("class");
					
				$(this).wrap("<div class='"+settings.containerClass+"'></div>");
			
				var elementCount = $('#'+container+' > li, .'+container+' > li').size();
				var containerWidth = $("."+settings.containerClass).width();
			
				$('#'+container+' > li, .'+container+' > li').each(function(i) {
			
					$(this).attr('id', container+"ListItem"+i);
			   		$(this).attr('class',settings.listItemClass);
		       		$(this).html("<div class='"+settings.contentStartClass+"' id='"+container+"Content"+i+"'>"
								 +"<div class=\""+settings.contentWrapper+"\"><div class=\""+settings.contentInnerWrapper+"\"> "+$(this).html()+"</div></div></div>");
			   	
					if($("div",this).hasClass(settings.handleClass)){
						var html = $("div."+settings.handleClass,this).attr("id",""+container+"Handle"+i+"").html();
						$("div."+settings.handleClass,this).remove();
						var handle = "<div class=\""+settings.handleClass+"\" id='"+container+"Handle"+i+"'>"+html+"</div>";
					}else{
						var handle = "<div class=\""+settings.handleClass+"\" id='"+container+"Handle"+i+"'></div>";
					}
					switch(settings.handlePosition ){
						case "left":
						$(this).prepend( handle );
						break;
						case "right":	
						$(this).append( handle );	
						break;
						case "top":	
						$("."+container+"Top").append( handle );	
						break;
						case "bottom":	
						$("."+container+"Bottom").append( handle );	
						break;
					}
			   		
					var handleWidth = $("."+settings.handleClass).width();
					var finalWidth = containerWidth - (elementCount*handleWidth)-13;
				
					$("#"+container+"Handle"+i).bind("mouseover", function(){
						$("#"+container+"Handle"+i).addClass(settings.handleClassOver);
					});
			    
					$("#"+container+"Handle"+i).bind("mouseout", function(){
						if( $("#"+container+"Handle"+i).attr("rel") != "selected"){
							$("#"+container+"Handle"+i).removeClass(settings.handleClassOver);
						}
					});
					
					// resize check start
			    	$(window).bind('resize', function(){
						var containerWidth = $("."+settings.containerClass).width();
						var finalWidth = containerWidth - (elementCount*handleWidth)-13;
						
						$("#"+container+"Handle"+i).unbind("click");
						$("."+settings.contentWrapper).css({width: finalWidth });
						
						if( $("#"+container+"Handle"+i).attr("rel") == "selected"){
							$('#'+container+'Content'+i).animate({className:settings.contentEndClass, width: finalWidth }, 
																 		settings.openEaseSpeed, 
																		settings.openEaseAction);
						}
						$.hrzAccordion.setOnClick(i, container, finalWidth, settings);
					});
					// resize check end
					
					$.hrzAccordion.setOnClick(i, container, finalWidth, settings);
										
					if(settings.openOnLoad !=false){
						$("#"+container+"Handle"+(settings.openOnLoad-1)).click();				
						
					}				
					
				});	
			});	
		}
	});
})(jQuery);	