// JavaScript Document
var proto_calls = {
			url : '',
			responseVarName:'',
			callback:function(data){},
			trigger : function(obj,updateQs){
					var url = this.url;

					updateQs = updateQs || {}
					updateQs.responseVarName = this.responseVarName;

					var qs = Utility.updateQueryString(updateQs)
					var _this = this;
					url+= qs;
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


