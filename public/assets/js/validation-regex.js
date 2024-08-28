function isNumber(evt) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;

  if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 37 && charCode != 39) {
    return false;
  }
  return true;
}

function isDecimal(evt, obj, delimiter) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  var delimiterKeyCode = 46;
  if(delimiter == ","){
    delimiterKeyCode = 44;
  }

  if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != delimiterKeyCode) {
    return false;
  }else if(charCode == delimiterKeyCode && obj.value.indexOf(delimiter) !== -1){
    return false;
  }
  return true;
}

function formatDecimal(el){
    if(el.value == ""){
        el.value = 0;
    }
    el.value = parseFloat(el.value).toFixed(2);
}

function isAlphaNumericAndWhiteSpace(evt){
	var regex = new RegExp("^[a-zA-Z0-9 ]*$");
    var key = String.fromCharCode(evt.charCode ? evt.which : evt.charCode);
    if (!regex.test(key)) {
        evt.preventDefault();
        return false;
    }
}

function isAlphaNumericAndWhiteSpaceAndComma(evt){
  var regex = new RegExp("^[a-zA-Z0-9 ,]*$");
    var key = String.fromCharCode(evt.charCode ? evt.which : evt.charCode);
    if (!regex.test(key)) {
        evt.preventDefault();
        return false;
    }
}

function isAlphaNumericAndAnyWhiteSpace(evt){
	var regex = new RegExp("^[a-zA-Z0-9\s]*$");
    var key = String.fromCharCode(evt.charCode ? evt.which : evt.charCode);
    if (!regex.test(key)) {
        evt.preventDefault();
        return false;
    }
}

function isAlphaNumeric(evt){
	var regex = new RegExp("^[a-zA-Z0-9]*$");
    var key = String.fromCharCode(evt.charCode ? evt.which : evt.charCode);
    if (!regex.test(key)) {
        evt.preventDefault();
        return false;
    }
}

function isAlphaNumericDash(evt){
  var regex = new RegExp("^[a-zA-Z0-9-]*$");
    var key = String.fromCharCode(evt.charCode ? evt.which : evt.charCode);
    if (!regex.test(key)) {
        evt.preventDefault();
        return false;
    }
}

function isAlphaNumericDashSlash(evt){
  var regex = new RegExp("^[a-zA-Z0-9\/-]*$");
    var key = String.fromCharCode(evt.charCode ? evt.which : evt.charCode);
    if (!regex.test(key)) {
        evt.preventDefault();
        return false;
    }
}

function isAlphabetic(evt){
	var regex = new RegExp("^[a-zA-Z]*$");
    var key = String.fromCharCode(evt.charCode ? evt.which : evt.charCode);
    if (!regex.test(key)) {
        evt.preventDefault();
        return false;
    }
}

function validateDescription(evt){
  var regex = new RegExp("^[a-zA-Z0-9., \%\s\/-]*$");
    var key = String.fromCharCode(evt.charCode ? evt.which : evt.charCode);
    if (!regex.test(key)) {
        evt.preventDefault();
        return false;
    }
}

function isNumericAndDot(evt){
  var regex = new RegExp("^[0-9.]*$");
    var key = String.fromCharCode(evt.charCode ? evt.which : evt.charCode);
    if (!regex.test(key)) {
        evt.preventDefault();
        return false;
    }
}

function latitude(evt){
  var regex = new RegExp("^[0-9.-]*$");
    var key = String.fromCharCode(evt.charCode ? evt.which : evt.charCode);
    if (!regex.test(key)) {
        evt.preventDefault();
        return false;
    }
}

function validateAddress(evt){
	var regex = new RegExp("^[a-zA-Z0-9\s,$# \(\)\/.-]*$");
    var key = String.fromCharCode(evt.charCode ? evt.which : evt.charCode);
    if (!regex.test(key)) {
        evt.preventDefault();
        return false;
    }
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email.toLowerCase());
}