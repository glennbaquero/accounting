export default {
	methods: {
		empty(value) {
			let type = typeof value;
			if (type === 'undefined') {
				return true;
			}
			if (type === 'boolean') {
				return !value;
			}
			if (value === null) {
				return true;
			}
			if (value === undefined) {
				return true;
			}
			if (value instanceof Array) {
				if (value.length < 1) {
					return true;
				}
			} else if (type === 'string') {
				if (value.length < 1) {
					return true;
				}
				if (value === '0') {
					return true;
				}
			} else if (type === 'object') {
				if (Object.keys(value).length < 1) {
					return true;
				}
				if (value === 0) {
					return true;
				}
			}
			return false;
		},

		checker(journalName, journalNumber) {
			if(location.hash) {
				swal.fire({
				    title: 'Ooops',
				    text: 'You have successfully posted the lines into '+journalName+' (#'+journalNumber+')',
				    icon: 'success',
				    showCancelButton: false,
				    confirmButtonText: 'OK',
				    cancelButtonText: 'Cancel'
				}).then((result) => {
				   history.replaceState(null, null, ' ');
				})
			}
		},

		insertParam(url,key,value) {
			if(value!==undefined){
				value = encodeURI(value);
			  }
			  var hashIndex = url.indexOf("#")|0;
			  if (hashIndex === -1) hashIndex = url.length|0;
			  var urls = url.substring(0, hashIndex).split('?');
			  var baseUrl = urls[0];
			  var parameters = '';
			  var outPara = {};
			  if(urls.length>1){
				  parameters = urls[1];
			  }
			  if(parameters!==''){
				parameters = parameters.split('&');
				for(k in parameters){
				  var keyVal = parameters[k];
				  keyVal = keyVal.split('=');
				  var ekey = keyVal[0];
				  var evalue = '';
				  if(keyVal.length>1){
					  evalue = keyVal[1];
				  }
				  outPara[ekey] = evalue;
				}
			  }
		
			  if(value!==undefined){
				outPara[key] = value;
			  }else{
				delete outPara[key];
			  }
			  parameters = [];
			  for(var k in outPara){
				parameters.push(k + '=' + outPara[k]);
			  }
		
			  var finalUrl = baseUrl;
		
			  if(parameters.length>0){
				finalUrl += '?' + parameters.join('&'); 
			  }
		
			  return finalUrl + url.substring(hashIndex); 
		}
	}
}