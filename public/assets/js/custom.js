function ucFirstWord(val) {
    return val.toLowerCase().replace(/\b[a-z]/g, function(letter) {
      return letter.toUpperCase();
  });
}


function formatPrice(value, toFixed) {
    var val = (value/1).toFixed(toFixed).replace('.', ',');
    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}