// JavaScript Document
$(document).ready(function(){

	/* Initializing Page Information */
	$('#urlName').html(Utility.getUrlParam('urlName'))
	$('#startDate').val(Utility.getUrlParam('startDate'))
	$('#endDate').val(Utility.getUrlParam('endDate'))
	
	/* Expanding Breadcrumb */
	
	$('#changeDate').click(function(){	  	
		document.location.href = document.location.href.replace(/startDate.*endDate[^&]*/,'startDate='+$('#startDate').val()+'&endDate='+$('#endDate').val())		
	}) 
	$('li.open:visible .title,.sub-menu li.open:visible').click();     
	
	/** Bind Events on Reload Button */
	$('.grid .tools a.reload').live('click', function (e) {
            var el =  jQuery(this).parents(".grid");		
			var rel = $(e.target).attr('rel');
			calls[rel].trigger({el:el});			
    });
	          

})