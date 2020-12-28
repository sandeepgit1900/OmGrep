
	
Graph = {
//Load Time Graph Call
	loadTimePageViewGraph : function(data,xAxis,yAxis1,yAxis2,medianLoadTime){
		
		_yAxis1 = yAxis1.slice(0);
		_yAxis2 = yAxis2.slice(0);
		for(key in _yAxis1){
			if(_yAxis1.hasOwnProperty(key))
			_yAxis1[key] = [xAxis[key],_yAxis1[key]]
			_yAxis2[key] = [xAxis[key],_yAxis2[key]]
		}		

		$('#visitor').highcharts({
				chart: {
					zoomType: 'x'
				},
				title: {
					text: ''
				},
				xAxis: [{
					minRange: 3600 * 1000,
					type: 'datetime'
				}],
				yAxis: [
					{ // Primary yAxis
					labels: {
						format: '{value}',
						style: {
							color: '#89A54E'
						}
					},
					title: {
						text: 'No of Visitor',
						style: {
							color: '#89A54E'
						}
					}
				}, { // Secondary yAxis
					title: {
						text: 'Avg Time',
						style: {
							color: '#4572A7'
						}
					},
					plotLines : [{
					value : medianLoadTime,
					color : 'brown',
					dashStyle : 'shortdash',
					width : 2,
					label : {
						text : 'Median Load Time'
					}
				}],
					labels: {
						format: '{value} sec',
						style: {
							color: '#4572A7'
						}
					},
					opposite: true					
				}],
				tooltip: {
					shared: true
				},
				legend: {              
					backgroundColor: '#FFFFFF'
				},
				series: [{
					name: 'No of Visitor',
					color: '#89A54E',
					type: 'column',
					data: _yAxis1,//[1, 1.1, 1.7, 1, 2.5, 10, 2, 3.5, 2.5, 1.8, 2, 3,2.5, 1.8, 2, 3],
					pointStart: xAxis[0],
					tooltip: {
						valueSuffix: ''
						}
					},
					{
					name: 'Avg Time',
					color: '#4572A7',
					type: 'spline',
					yAxis: 1,
					zIndex:1,
					data: _yAxis2,//[1, 1.1, 1.7, 1, 2.5, 10, 2, 3.5, 2.5, 1.8, 2, 3,2.5, 1.8, 2, 3],
					pointStart: xAxis[0],
					tooltip: {
						valueSuffix: 'sec'
						}
					},
				],

			});
	},	
	threshold : [2,5,Number.MAX_VALUE],//'' signify values ahead
	vistorBucket : [0,0,0],
	visitorSum : 0,
	colors : ['#06c27a','#14a4d2','#f1764b'],
	fillVisitorBucket : function(time,visitor){
		for(var i=0;i<this.threshold.length;i++)
		{
			if(parseInt(time)<=this.threshold[i])
			{				
				this.vistorBucket[i]+=parseInt(visitor);  
				this.visitorSum+=parseInt(visitor);
				return this.colors[i];
			}
		}		
	},
	percentage : function(){
		var a = [];
		for(var i=0;i<this.vistorBucket.length;i++){
			a[i] = parseInt(((this.vistorBucket[i]/this.visitorSum)*100).toFixed(0));
		}
		return a;
	},
//timeChartPerSecond
	timeChart :function(data,arr,colors,color){
		
		arr = [],sec = [];

		for(key in data){			
			if(data.hasOwnProperty(key)){
				sec.push(arr.length) 
//				color = Graph.fillVisitorBucket((arr.length+1),data[key]);			
				color = Graph.fillVisitorBucket((arr.length),data[key]);			
				arr.push({y:parseInt(data[key]),color:color}); 	

			}
		}		

		$('#timeChart').highcharts({
			title: {
				text: ''
			},
			chart: {
				type: 'column'
			},
			xAxis: {
				categories: sec,//['1 Sec', '2 Sec', '3 Sec', '4 Sec', '5 Sec', '6 Sec', '7 Sec', '8 Sec', '9 Sec', '10 Sec', '11 Sec', '12 Sec']
				title: {
					text: 'Seconds took'
				},
			},
			yAxis: {
			title: {
				text: 'Number of Visitors'
			}},
			legend: {
				enabled:0
			},
			credits: {
				enabled:0
			},
			tooltip: {
				headerFormat: '<span style="font-size:11px">{point.x} second</span><br>',
				pointFormat: '<span style="color:{point.color}"><b>{point.y} visitors</b><br/>'
			},
			series: [{
				name: 'Visitors',
				data: arr//[{y:280.9,color:"#06c27a"}, {y:71.5,color:"#06c27a"}, {y:144.0,color:"#06c27a"}, {y:176.0,color:"#06c27a"}, {y:135.6,color:"#14a4d2"}, {y:148.5,color:"#14a4d2"}, {y: 216.4,color: '#14a4d2'}, {y: 50,color: '#f1764b'} , {y: 20,color: '#f1764b'}]
			}]
		});	
		Graph.userExperience();
	},//user exp chart
	userExperience : function(){
		var arr = Graph.percentage();	
		$('#userExp').highcharts({
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: 0,
				plotShadow: false
			},
			title: {
				text: 'User<br>Experience',
				align: 'center',
				verticalAlign: 'middle',
				y: 10,
				style: {
							fontSize: '14px'
						}
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					dataLabels: {
						enabled: true,
						distance: -35,
						style: {
							fontWeight: 'bold',
							color: 'white',
							textShadow: '0px 1px 2px black'
						}
					},
					startAngle: -120,
					endAngle: 120,
					center: ['50%', '75%'],
					size: 260
				}
			},area: {
					fillColor: '#000000',
				},
				credits: {
				enabled:0
			},
			series: [{
				type: 'pie',
				name: 'Browser share',
				innerSize: '90%',
				data: [{name:"Happy",y:arr[0],color:"#06c27a"},{name:"Satisfied",y:arr[1],color:"#14a4d2"},{name:"Unhappy",y:arr[2],color:"#f1764b"}]
			}]
		});
	},
	platformData:function(data){
		
		
		var obj1 = {name:'Desktop',y:0,drilldown:'desktop'}
		var obj2 = {name:'Mobile',y:0,drilldown:'mobile'}		
		var series = [obj1,obj2]
				
		var dobj1 = {id:'desktop',name: 'Desktop Os',data:[]}
		var dobj2 = {id:'mobile',name: 'Mobile Os',data:[]}
		var dseries = [dobj1,dobj2]
		
		var fillData = function(arr,name,value){			
				arr.push([name,value]);
		}		
		
		//Filling OS data				
		for(key in data){
			if(data.hasOwnProperty(key)){
				if(data[key].type=='mobile'){
					obj2.y++;					
					fillData(dobj2.data,data[key].label,parseInt(data[key].pageViews))
				}
				else{
					obj1.y++;
					fillData(dobj1.data,data[key].label,parseInt(data[key].pageViews))
				}
				
			}
		}
		
		$('#browserShare').highcharts({
			chart: {
				type: 'pie'
			},
			title: {
				text: 'OS wise Visitor Distribution'
			},
			subtitle: {
				text: 'Click the slices to view versions.'
			},
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						format: '{point.name}: {point.y}'
					}
				},pie: {
					size: '50%'
				}
			},
			tooltip: {
				headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
				pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>'
			},
			series: [{
					name: 'OS Type',
					colorByPoint: true,
					data: series
				}],
			drilldown: {
				series: dseries
				}
		})
	},
	navTime : function(data,map){
		var series = [];
		var color = this.colors;
		map = {'frontendTime':'Page Render','backendTime':'First Byte','networkTime':'DNS Lookup Time'}

		for(key in map){
			var value = Utility.milliToSec(data[key],true);		
			var obj = {name:map[key],data:[value],color:color[(series.length)%color.length]}
			series.push(obj);
		} 
		$('#container').highcharts({
			chart: {
				type: 'bar',
				renderTo: 'container',

			},
			title: {
				text: 'Navigation Time'
			},
			xAxis: {
				type: 'datetime',
				categories: ['Time'],

			},
			legend: {
				backgroundColor: '#FFFFFF',
				reversed:true,
			},
			tooltip: {
				borderRadius: '0px'
			},
			plotOptions: {
				area: {
					fillColor: '#000000',
				},
				series: {
					stacking: 'normal'
				}
			},
			 series:series
	
		});
	}
}