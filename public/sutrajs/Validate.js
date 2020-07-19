class Validate {
	
  constructor() {}
  static isValidPhone(data){
	    var pattern = /^\d{10}$/;
		if (data.match(pattern)) {
			return true;
		} else {
			return false;
		}
  }
	static isValidText(data){
		  var pattern = /^[a-zA-Z, ().,-]+$/;
			if (data.match(pattern)) {
				return true;
			} else {
				return false;
			}
	}
	static isValidNumber(data){
		  var pattern = /^[0-9.]+$/;
			if (data.match(pattern)) {
				return true;
			} else {
				return false;
			}
	}

	static isValidEmail(data){
		  var pattern = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
			if (data.match(pattern)) {
				return true;
			} else {
				return false;
			}
	}
	static isValidDate(data){
	    var pattern = /^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/;
		if (data.match(pattern)) {
			return true;
		} else {
			return false;
		}
	}
	static isValidTime(data){
	    var pattern = /^(0[1-9]|[1-2][0-9]|3[0-1]):(0[1-9]|1[0-2])$/;
		if (data.match(pattern)) {
			return true;
		} else {
			return false;
		}
	}
	static isEmpty(field){
		var data=$("#"+field).val();
		 if(data==""){
			 return true;
		 }
		  else{
			  return false;
		  }
	}
	static isValidRollNo(data){
		var pattern = /^(CT-[0-9]{2}-[a-zA-Z]{1,2}-[0-9]{2})$/;
	if (data.match(pattern)) {
		return true;
	} else {
		return false;
	}
}
}
