// JavaScript Document

$(document).ready(function(){

		$('#domain').change(function(){
			var option = {
							param:'apps',
							cb : 'callback.appData',
							id:$(this).val()
						}
			calls['appData'].trigger({el:$('#selectAppLayer')},option);

		})

		$('form[name=appSelect]').submit(function(){
			$('#appName').attr('value',$('#source option:selected').html());
		})

		var startDate = Utility.getUrlParam('startDate');
		var endDate = Utility.getUrlParam('endDate');
		startDate ?$('#startDate').val(startDate):''
		endDate?$('#endDate').val(endDate):''
});

calls.init({name:'appData',url:serviceBaseUrl+'/fetchdropdown.php?.=.'})
calls.init({name:'domainData',url:serviceBaseUrl+'/fetchdropdown.php?param=domain&cb=callback.domainData'}).trigger({el:$('#selectAppLayer')});

/** Hold callback related Function */
callback = {
	appData : function(data){
		 var data = data.apps;
		  $('#source').empty();
		 for(key in data){
				 $('#source').append($('<option value="'+key+'">'+data[key]['app_name']+'</option>'));
		 }
		 var appId = Utility.getUrlParam('appId');
		 if(appId)
	 	 	 $('#source').val(Utility.getUrlParam('appId'));
		 else
		 	$('#source')[0].selectedIndex=0;

	},
	domainData: function(data){
		var domain = data.domain;
	    $('#domain').empty();
		for(key in 	domain){
			$('#domain').append('<option value="'+key+'">'+domain[key]['domain_name']+'</option>')
		}
		 var domainId = Utility.getUrlParam('domainId');
		 if(domainId){
	 	 	 $('#domain').val(Utility.getUrlParam('domainId'));
		 }
		 else
		 	$('#domain')[0].selectedIndex=0;
		 $('#domain').change();
	}
}
