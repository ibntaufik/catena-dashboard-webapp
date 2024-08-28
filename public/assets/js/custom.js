function ucFirstWord(val) {
    return val.toLowerCase().replace(/\b[a-z]/g, function(letter) {
      return letter.toUpperCase();
  });
}


function formatPrice(value, toFixed) {
    var val = (value/1).toFixed(toFixed).replace('.', ',');
    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}


function parseErrorMessage(response){

    var message = "";          
    if(("responseJSON" in response) 
        && (response.responseJSON !== null) 
        && ("errors" in response.responseJSON) 
        ){
        message = "<ul>"
        for (let key in response.responseJSON.errors) {
           if (response.responseJSON.errors.hasOwnProperty(key)) {
              message += "<li>"+response.responseJSON.errors[key][0]+"</li>";
           }
        }
        message += "</ul>";
    }

    return message;
}