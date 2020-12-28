// JavaScript Document
$(document).ready(function(){

	/* Initializing Page Information */
	$('#urlName').html(Utility.getUrlParam('urlName'))
	
	var startDate = Utility.getUrlParam('startDate');
	var endDate = Utility.getUrlParam('endDate');
	startDate ?$('#startDate').val(startDate):''
	endDate?$('#endDate').val(endDate):''
	
	/* Expanding Breadcrumb */
	
	$('#changeDate').click(function(){	  	
		document.location.href = Utility.updateQueryString({startDate:$('#startDate').val(),endDate:$('#endDate').val()})//document.location.href.replace(/startDate.*endDate[^&]*/,'startDate='+$('#startDate').val()+'&endDate='+$('#endDate').val())		
	}) 
	$('li.open:visible .title,.sub-menu li.open:visible').click();     
	
	/** Bind Events on Reload Button */
	$('.grid .tools a.reload').live('click', function (e) {
            var el =  jQuery(this).parents(".grid");		
			var rel = $(e.target).attr('rel');
			calls[rel].trigger({el:el});			
    });
	          

})