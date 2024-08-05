@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<style type="text/css">
  .submit-button{
    float:right;
    width: 40%;
    cursor: pointer;
  }
</style>
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Master Data</a></li>
    <li class="breadcrumb-item active" aria-current="page">Farmer Account</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <h6 class="card-title">Create Farmer Account</h6>
          <div class="row">
            
            <input type="text" name="username_fake" id="username_fake" autocomplete="off" style="display:none;">
            <input type="password" name="password_fake" autocomplete="off" style="display:none;">

            <div class="col-sm-3">
              <div class="mb-3">
                <label for="name" class="form-label">Farmer Name</label>
                <input type="text" class="form-control" id="name" maxlength="255" placeholder="Farmer Name" onkeypress="return isAlphaNumericAndWhiteSpace(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email_user" maxlength="255" autocomplete="off" placeholder="me@mail.co">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" maxlength="255" autocomplete="new-password" placeholder="Password" onkeypress="return isAlphaNumeric(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="location_id" class="form-label">Location</label>
                <select id="location_id" class="form-control" name="location_id">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="latitude" class="form-label">Latitude</label>
                <input type="text" class="form-control" id="latitude" maxlength="255" placeholder="Latitude" onkeypress="return isNumericAndDot(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="longitude" class="form-label">Longitude</label>
                <input type="text" class="form-control" id="longitude" maxlength="255" placeholder="Longitude" onkeypress="return isNumericAndDot(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="id_number" class="form-label">Identity Number</label>
                <input type="text" class="form-control" id="id_number" maxlength="255" placeholder="(SIM, KK, or KTP))" onkeypress="return isNumber(event);">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="city" class="form-label">Address</label>
                <textarea type="text" class="form-control" id="address" rows="3" placeholder="Address" onkeypress="return validateAddress(event);"></textarea>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="mb-3">
                <div class="row col-sm-12">
                  <div class="col-md-6">
                    <div class="form-group" style="height: 40%;">
                        <label class="mandatory">Upload ID Photo</label>
                        <input type="file" id="id_file" name="id_file" style="opacity: 1; position: relative; height: 30px;" onchange="getBase64(this.id)">
                    </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <img id="img-preview-id_file" style="max-width: 100%;height: auto;">
                          <input type="hidden" id="img-base64-id_file">
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="card-footer">
          <div class="row">
            <div class="col-sm-9">
              <div id="response_message" class="alert alert-danger alert-dismissible fade show" role="alert" style="">
                  <strong id="success">Message</strong>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            </div>
            <div class="col-sm-3">
              <button type="submit" class="btn btn-primary submit-button" style="" onclick="submit();">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>Create
              </button>
              <button class="btn btn-secondary me-2 submit-button" style="">Cancel</button>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">List of Farmer Account</h6>
        <div class="table-responsive">
          <table id="gridDataTable" class="table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>ID Number</th>
                <th>Location</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Address</th>
                <th></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('javascript')
<script type="text/javascript">
    var start = 0;
    var limit = 10;
    var candidate = {!! json_encode($candidate) !!};
    var fileType = "";

  $(document).ready(function() {
      
      $("#response_message").attr("style", 'display: none;');

      $('#location_id').select2({
            width: '100%',
            data: candidate
      });

      $('#gridDataTable').DataTable( {
          'paging'        : true,
          'lengthChange'  : false,
          'ordering'      : false,
          'info'          : true,
          'autoWidth'     : false,
          "processing"    : true,
          "searching"     : true,
          "pageLength"    : limit,
          "ajax": {
            "url": "{{ route('farmer.grid-list') }}",
            "data": function ( d ) {
              var info = $('#gridDataTable').DataTable().page.info();
              d.start = info.start;
              d.limit = limit;
            },
            "dataSrc": function(json){
              
              json.recordsTotal = json.data.length;
              json.recordsFiltered = json.data.length;

              return json.data;
            }
          },
                                
          "columnDefs" : [
            { "targets": 0, "data": "name" },
            { "targets": 1, "data": "email" },
            { "targets": 2, "data": "id_number" },
            { "targets": 3, "data": "location" },
            { "targets": 4, "data": "latitude" },
            { "targets": 5, "data": "longitude" },
            { "targets": 6, "data": "address" },
            { "targets": 7, "data": function(data, type, row, meta){
                  return '<a href="#" onclick=$(this).delete("'+data.id_number+'") style="cursor: pointer;"><i data-feather="trash-2"></i>';
              }
            },
          ],
          "drawCallback": function(settings) {
              feather.replace(); // Initialize Feather icons
          }
      });

      $.fn.delete = function(idNumber) {
        $.ajax({
            type: "POST",
            url: "{{ route('farmer.remove') }}",
            data: {
              _token: "{{ csrf_token() }}",
              id_number: idNumber,
            },
            dataType: "json",
            timeout: 300000
        }).done(function(data){
            $('#gridDataTable').DataTable().ajax.reload();
        }).fail(function(data){
            
        });
      };
  });
  
  function validateInput(input){
      var re = /^[a-zA-Z0-9.,\s\/-]*$/;
      return re.test(input);
  }

  function submit()
  {
      $("#name, #address, #latitude, #longitude").attr('style', '');
      $("#email_user, #name, #password").attr('style', '');
      $("#response_message").attr("style", 'display: none;');
      $(".submit-button").addClass("disabled");
      $(".spinner-border").attr("style", '');

      var pass = true;

      $("#name, #address, #latitude, #longitude, #email_user, #name, #password, #id_number").each(function(){
          $(this).attr('style', '');
          if($(this).val() == ''){
            $(this).attr('style', 'border: 1px solid #d57171 !important');
            pass = false;
            return;
          }
      });

      if(pass && !validateEmail($("#email_user").val())){
        $('#response_message').removeClass('alert-success');
        $('#response_message').addClass('alert-danger');
        $("#response_message").attr("style", '');
        $("#success").html("Email is not valid");
        $('#response_message').fadeTo(3000, 500).slideUp(500, function() {
          $("#success-alert").slideUp(500);
        });
        pass = false;
        return;
      }

      if(pass){
        var submitData = {
            _token: "{{ csrf_token() }}",
            name: $('#name').val(),
            email: $('#email_user').val(),
            password: $('#password').val(),
            address: $('#address').val(),
            latitude: $('#latitude').val(),
            longitude: $('#longitude').val(),
            id_number: $('#id_number').val(),
            location_code: $('#location_id').val(),
            file: $('#img-base64-id_file').val(),
            file_type: fileType
        };

        $.ajax({
            type: "POST",
            url: "{{ route('farmer.submit') }}",
            data: submitData,
            dataType: "json",
            timeout: 300000
        }).done(function(data){
            if(data.code == 200){
              $('#response_message').removeClass('alert-danger');
              $('#response_message').addClass('alert-success');
              reset();
            } else {
              $('#response_message').removeClass('alert-success');
              $('#response_message').addClass('alert-danger');
            }
            $("#response_message").attr("style", '');
            $('#response_message').fadeTo(3000, 500).slideUp(500, function() {
              $("#success-alert").slideUp(500);
            });
            $("#success").html(data.message);
        }).fail(function(data){
            $('#response_message').removeClass('alert-success');
            $('#response_message').addClass('alert-danger');
            $("#success").html("Koneksi ke server terkendala. Silakan coba lagi.");
        });
      }

      $(".submit-button").removeClass("disabled");
      $(".spinner-border").attr("style", "display: none;");
  }

  function reset(){

      $('#gridDataTable').DataTable().ajax.reload();
      $("#name, #address, #latitude, #longitude, #email_user, #name, #password, #id_number").val('');
      $("#location_id").val('select').select2();
      $("#img-preview-id_file").attr({'src':''});
      $('#img-base64-id_file').attr({'value':''});
      $('#id_file').val('');
  }

  function getBase64(file) {
      var files = document.getElementById(file).files;
      previewImg = document.getElementById("img-preview-"+file);
      base64 = document.getElementById("img-base64-"+file);

      if (files[0].type != 'image/jpeg' && files[0].type != 'image/png'){
          previewImg.removeAttribute("src");
          base64.removeAttribute("value");
          $('#response_message').removeClass('alert-success');
          $('#response_message').addClass('alert-danger');  
          $("#success").html("File type must be JPEG or PNG");
          return false;

      } else if (files[0].size > 1000000){
          previewImg.removeAttribute("src");
          base64.removeAttribute("value");
          $('#response_message').removeClass('alert-success');
          $('#response_message').addClass('alert-danger');  
          $("#success").html("File size max. 1MB");
          return false;
      }

      if (files.length > 0 && (files[0].type == 'image/jpeg' || files[0].type == 'image/png')) {
          var reader = new FileReader();
          reader.readAsDataURL(files[0]);
          reader.onload = function () {
            previewImg.setAttribute("src",reader.result);
            base64.setAttribute("value",reader.result);
          };
          reader.onerror = function (error) {
              showErrorMessage(error);
              return false;
          };
          if(files[0].type == 'image/jpeg'){
            fileType = ".jpeg";
          }else if(files[0].type == 'image/png'){
            fileType = ".png";
          }
      }
  }


</script>
@endsection  

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
