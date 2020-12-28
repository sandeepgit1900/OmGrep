//Cool ios7 switch - Beta version
//Done using pure Javascript
$(document).ready(function(){
//for sidebar
	var x = [
    	{
        	class: "icon-rocket",
        	title: "Performance",
			url:"boomDashboardUrls.php",
			submenu:[{class: "icon-bar-chart",title: "URL Summary",url:"boomDashboardSummary.php"},
					{class: "icon-map-marker",title: "Geo Map",url:"boomDashboardMap.php"}
					]
    	},
    	{
        	class: "icon-bug",
        	title: "Errors",
			url:"nlogger.php"
    	},
        {
        	class: "li_fire",
        	title: "HeatMap",
			url:"heatMapUrlList.php"
    	}
	];
	sidebar.init(x);

  //Date Pickers

 	var sDate = Utility.getUrlParam('startDate')? new Date(Utility.getUrlParam('startDate')):new Date();
 	var eDate = Utility.getUrlParam('endDate')? new Date(Utility.getUrlParam('endDate')):new Date();

	$('#startDate').datepicker({
		format:'yyyy-mm-dd',
	 	autoclose: true,
	 	todayHighlight: true
   	}).datepicker('setDate',sDate);

	$('#endDate').datepicker({
		format:'yyyy-mm-dd',
	 	autoclose: true,
	 	todayHighlight: true
   	}).datepicker('setDate',sDate);


	if(location.pathname=='/newmonk/boomDashboardSummary.php' || location.pathname=='/newmonk/boomDashboardMap.php'){
		console.log("here I am!!!");
		$('#main-menu ul > li').eq(0).find('.sub-menu').show();
		$('#main-menu ul > li').eq(0).find('a > .arrow').addClass('open')

	}

	//for boomerang submenu
	$('#main-menu ul > li').eq(0).find('.sub-menu a').click(function(e){
		console.log("was it me??")
  		this.href+=document.location.search;
 	});
});



var sidebar=(function(){

	var init=function(obj){
	var str='<ul>';
	for (var i in obj){

		str+='<li>'
			//+'<a href="'+(window.serviceBaseUrl||window.serviceBaseUrl)+'/'+obj[i].url+'?sideBarIndex='+i +'">';
			+'<a href="'+obj[i].url+'?sideBarIndex='+i+'">';

		if(obj[i].submenu){
			str+='<i class="'+ obj[i].class+'"></i><span class="title">'+ obj[i].title+'</span><span class="arrow"></span></a>'
				+'<ul class="sub-menu">'
			for(var x in obj[i].submenu){
          		str+='<li> <a href="'+obj[i].submenu[x].url+'"><i class="'+obj[i].submenu[x].class+'"></i>'+obj[i].submenu[x].title+'</a> </li>';
       			}
				str+='</ul>';
			}
		else{
			str+='<i class="'+ obj[i].class+'"></i><span class="title">'+ obj[i].title+'</span></a>'
			}

		  	str+='</li>';
		}
		str+='</ul>';
	$("#main-menu").html(str);
		}
		return {init:init};

	}());


