// JavaScript Document

$(document).ready(function(){
	
		calls.init({name:'appData',url:'http://logs.naukri.com/nLogger/staticDDs.php',responseVarName:'data',callback:callback.appData}).trigger({el:$('#selectAppLayer')});	
		$('form[name=appSelect]').submit(function(){
			$('#appName').attr('value',$('#source option:selected').html());	
		})
		$('#startDate').val(Utility.getUrlParam('startDate'))
		$('#endDate').val(Utility.getUrlParam('endDate'))		 
});

/** Hold callback related Function */
callback = {
	appData : function(data){
		 var data = data.appList;
		 for(key in data){
				 $('#source').append($('<option>').val(key).html(data[key]))
		 }
		 var appId = Utility.getUrlParam('appId');
		 if(appId)
	 	 	 $('#source').val(Utility.getUrlParam('appId'));	
		 else
		 	$('#source')[0].selectedIndex=0;
		
	}	
}