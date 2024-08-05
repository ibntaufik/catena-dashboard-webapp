function showSuccessMessage(msg){
  $.notify({
    message: msg 
  },{
    type: 'success',
    mouse_over: 'pause'
  });
}

function showErrorMessage(msg){
  $.notify({
    message: msg 
  },{
    type: 'danger',
    mouse_over: 'pause'
  });
}