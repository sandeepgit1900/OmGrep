// JavaScript Document
var proto_calls = {
			url : '',
			responseVarName:'',
			callback:function(data){},
			/** obj : Element to show load on
				updateQs : New query string params to be merged
			 */
			trigger : function(obj,updateQs){
					var url = this.url;
					!this.responseVarName?$.ajaxSetup({cache:true}):'';
					updateQs = updateQs || {}
					if(this.responseVarName)
						updateQs.responseVarName = this.responseVarName;
						
					/** 2nd param is the query string which will be updated, default is document.location.search */

					var qs = Utility.updateQueryString(updateQs,(url.match(/\?.*/)||[])[0]);
					url=url.replace(/\?.*/,'');
					url+= qs;
					var _this = this;
					$.getScript(url,function(){Utility.unblockUI(obj.el||'');_this.callback(window[_this.responseVarName])})

					Utility.blockUI(obj.el||'');
				}	
			}
			

var calls = (function(){
	
	var callsObj = function(obj){
		for(key in obj){			
			this[key] = obj[key];
		}
	}
	callsObj.prototype =  proto_calls;
		
	var init = 	function(obj){		
		return (calls[obj.name] = new callsObj(obj));		
	}	
	return {init:init};
	
}())


