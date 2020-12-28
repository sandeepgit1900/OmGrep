// JavaScript Document
/**
	This file holds various utility functions like :-
	
	getUrlParam : Takes name of the param as value and return the value otherwise null.		
	getTotal : Return sum of an Array. For string array a concatinated string is returned. Otherwise 0.
	getMedian : 
	getAverage : 
	
	Need to include jQueryblockui.js
	blockUI : 
	unblockUI : 
	
 
 */

Utility = {
	getMedian : function(_arr){
		var arr = _arr.slice(0);			
			if(arr.length){
				arr.sort( function(a,b) {return a - b;} );		 
				window.median = arr;
				var half = Math.floor(arr.length/2);
			 
				if(arr.length % 2)
					return arr[half];
				else
					return (arr[half-1] + arr[half]) / 2.0;		
			}
			else
				return '0'
		},
	getAverage : function(arr){
		var sum = Utility.getTotal(arr);
		return sum/arr.length;
	},
	getTotal : function(_arr){
		var sum=0;
		for(var i=0;i<_arr.length;i++){
				sum+=_arr[i]
		}
		return sum;
	},
	getUrlParam : function(param){
			var arr =  document.location.search.slice(1).split(/&|=/);
			var i = $.inArray(param,arr);
			return  (i>-1)?decodeURIComponent(arr[i+1]).replace(/\+/g,' '):null;
	},
	updateQueryString : function(obj){
		var search = document.location.search;
		for(key in obj){
				var nameValue = key+'='+obj[key];
				var regex = new RegExp(key+'[^&]*')
				if(search.match(regex))
					search = search.replace(regex,nameValue)
				else
					search+= (search?'&':'?')+nameValue;
		}
		return search;
	},
	toFixed : function(num,typeNum){
		return !typeNum?num.toFixed(2):(Math.round(num*Math.pow(10,2))/Math.pow(10,2));		
	},
	milliToSec : function(ms,typeNum){
		ms = parseFloat(ms||0)
		num = ms/1000;
		return this.toFixed(num,typeNum)||(!typeNum?'0':0);
	},	
	blockUI : function(el) {		
			if(jQuery.blockUI){
				$(el).block({
					message: '<div class="loading-animator"></div>',
					css: {
						border: 'none',
						padding: '2px',
						backgroundColor: 'none'
					},
					overlayCSS: {
						backgroundColor: '#fff',
						opacity: 0.3,
						cursor: 'wait'
					}
				});
			}
	 },	 
	 unblockUI : function(el) {
		 if(jQuery.unblockUI)
			$(el).unblock();
	}	
}
