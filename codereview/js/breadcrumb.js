// JavaScript Document


var breadcrumb = (function(){
	var holder = {};

	$(document).ready(function(){	
		holder.appName = $('ul.breadcrumb li a').eq(0);
		holder.urlList = $('ul.breadcrumb li a').eq(1);
		holder.sideBar = $('ul.breadcrumb li a').eq(2);			
		breadcrumb.setValue();
	});	

	var getValue  = function(option){
		
		return {
					sideBar : option.sideBar||'',
					appName : Utility.getUrlParam('appName')		
		}
	}
	var setValue = function(option){
		value = getValue(option||{});

		for(key in value){

			holder[key].html(value[key]) 
		}
		holder.appName.attr('href','boomDashboard.php'+document.location.search)
		holder.urlList.attr('href','boomDashboardUrls.php'+document.location.search)
	}
	return {setValue:setValue}
		
}())

