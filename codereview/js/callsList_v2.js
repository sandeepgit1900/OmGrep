// JavaScript Document

$(document).ready(function(){

	calls.init({name:'loadStatesData',url:'http://logs.naukri.com/nLogger/boomGetBreakupTimes.php',responseVarName:'loadStatesData',callback:callback.loadStatesData}).trigger({el:$('#loadStatesData')});	
	calls.init({name:'browserData',url:'http://logs.naukri.com/nLogger/boomGetBrowserWiseLoadTimesAndPageViews.php',responseVarName:'browserData',callback:callback.browserData}).trigger({el:''});	
	calls.init({name:'loadTimePageView',url:'http://logs.naukri.com/nLogger/boomGetLoadTimesAndPageViews.php',responseVarName:'loadTimePageView',callback:callback.loadTimePageViewData}).trigger({el:$('#visitor')});	
	calls.init({name:'loadTimeRanges',url:'http://logs.naukri.com/nLogger/boomGetLoadTimeRanges.php',responseVarName:'loadTimeRanges',callback:Graph.timeChart}).trigger({el:$('#timeChart')});	
	calls.init({name:'platformData',url:'http://logs.naukri.com/nLogger/boomGetOSWiseLoadTimesAndPageViews.php',responseVarName:'platformData',callback:Graph.platformData}).trigger({el:$('#browserShare')});	
	calls.init({name:'resourceTimer',url:'http://logs.naukri.com/nLogger/boomGetCustomTimes.php',responseVarName:'resourceTimer',callback:Tables.resourceTimeTable}).trigger({el:$('#_')});		
	
})

/** Hold callback related Function */
callback = {
	loadStatesData : function(data){
		Graph.navTime(data)
		map = {networkTime:'#t_dns',backendTime:'#t_resp',frontendTime:'#t_page',domReadyTime:'#t_domLoaded'}	
		for(key in map){
			var value = Utility.milliToSec(data[key]);
			$(map[key]+' strong value').text(value);	
		} 
	},
	browserData :function(data){
		var oTable = $('#example2').dataTable();
		oTable.fnClearTable();	
		var sum_pageViews= 0;	
		for(var i=0;i<data.length;i++){
			sum_pageViews+= data[i]['pageViews'] = parseInt(data[i]['pageViews']); 			
		}						
		for(var i=0;i<data.length;i++){
			data[i]['loadTime'] = Utility.milliToSec(data[i]['loadTime']); 
			var perc_pageViews = Utility.toFixed(100*(data[i]['pageViews']/sum_pageViews));
			oTable.fnAddData([data[i]['label']+' '+data[i]['version'],perc_pageViews+'%',data[i]['loadTime']]); 
		}			
	
	},
	loadTimePageViewData : function(data,medianLoadTime){
		xAxis = [];yAxis1=[];yAxis2=[];
		for(key in data.pageViews){
			var arr = key.split(/-|\s|:/);
			arr[1] = parseInt(arr[1])-1;
			xAxis[xAxis.length] = Date.UTC.apply(Date.UTC,arr);
			yAxis1[yAxis1.length] = parseFloat(data.pageViews[key]);
		}	
		for(key in data.loadTimes)
		{		
			yAxis2[yAxis2.length] = Utility.milliToSec(data.loadTimes[key],true);
		}
		medianLoadTime = Utility.getMedian(yAxis2)
		
		Graph.loadTimePageViewGraph(data,xAxis,yAxis1,yAxis2,medianLoadTime);
		Tables.visitorTable(data.interval,xAxis,yAxis1,yAxis2);		
		
		$('#lt_median value').html(Utility.toFixed(medianLoadTime));
		$('#lt_average value').html(Utility.toFixed(Utility.getAverage(yAxis2)));
		$('#pgv_total value').html(Utility.getTotal(yAxis1))	
	}
}
