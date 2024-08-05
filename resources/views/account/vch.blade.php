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
    <li class="breadcrumb-item active" aria-current="page">VCH Account</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <h6 class="card-title">Create VCH Account</h6>
          <div class="row">
            
            <input type="text" name="username_fake" id="username_fake" autocomplete="off" style="display:none;">
            <input type="password" name="password_fake" autocomplete="off" style="display:none;">

            <div class="col-sm-3">
              <div class="mb-3">
                <label for="vch-code" class="form-label">VCH Code</label>
                <input type="text" class="form-control" id="vch-code" maxlength="255" autocomplete="off" placeholder="ID-XYZ-123" onkeypress="return isAlphaNumericDash(event);">
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
                <label for="vendor_id" class="form-label">Vendor ID</label>
                <input type="text" class="form-control" id="vendor_id" maxlength="255" placeholder="Vendor ID" onkeypress="return isAlphaNumericDash(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="vendor_name" class="form-label">Vendor Name</label>
                <input type="text" class="form-control" id="vendor_name" maxlength="255" placeholder="Vendor Name" onkeypress="return isAlphaNumericAndWhiteSpace(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="vendor_name" class="form-label">Vendor Bank Name</label>
                <select id="bank_id" class="form-control" name="bank_id">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="vendor_name" class="form-label">Vendor Bank Location</label>
                <input type="text" class="form-control" id="vendor_bank_location" maxlength="255" placeholder="Vendor Bank Location" onkeypress="return isAlphaNumericAndWhiteSpace(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="vendor_name" class="form-label">Vendor Account Number</label>
                <input type="text" class="form-control" id="vendor_bank_account_number" maxlength="255" placeholder="Vendor Account Number" onkeypress="return isNumber(event);">
              </div>
            </div>
            <div class="col-sm-9">
              <div class="mb-3">
                <label for="city" class="form-label">Address</label>
                <textarea type="text" class="form-control" id="address" rows="2" placeholder="Address" onkeypress="return validateAddress(event);"></textarea>
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
        <h6 class="card-title">List of VCH Account</h6>
        <div class="table-responsive">
          <table id="gridDataTable" class="table">
            <thead>
              <tr>
                <th>VCH Code</th>
                <th>Email</th>
                <th>Location</th>
                <th>Address</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Vendor ID</th>
                <th>Vendor Name</th>
                <th>Vendor Bank Name</th>
                <th>Vendor Bank Location</th>
                <th>Vendor Account Number</th>
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
    var bank = {!! json_encode($bank) !!};

  $(document).ready(function() {
      
      $("#response_message").attr("style", 'display: none;');

      $('#location_id').select2({
            width: '100%',
            data: candidate
      });

      $('#bank_id').select2({
            width: '100%',
            data: bank
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
            "url": "{{ route('vch.grid-list') }}",
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
            { "targets": 0, "data": "vch_code" },
            { "targets": 1, "data": "email" },
            { "targets": 2, "data": "sub_district" },
            { "targets": 3, "data": "address" },
            { "targets": 4, "data": "latitude" },
            { "targets": 5, "data": "longitude" },
            { "targets": 6, "data": "vendor_id" },
            { "targets": 7, "data": "vendor_name" },
            { "targets": 8, "data": "vendor_bank_name" },
            { "targets": 9, "data": "vendor_bank_address" },
            { "targets": 10, "data": "vendor_bank_account_number" },
            { "targets": 11, "data": function(data, type, row, meta){
                  return '<a href="#" onclick=$(this).delete("'+data.vch_code+'") style="cursor: pointer;"><i data-feather="trash-2"></i>';
              }
            },
          ],
          "drawCallback": function(settings) {
              feather.replace(); // Initialize Feather icons
          }
      });

      $.fn.delete = function(code) {
        $.ajax({
            type: "POST",
            url: "{{ route('vch.remove') }}",
            data: {
              _token: "{{ csrf_token() }}",
              vch_code: code,
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
      $("#vch-code, #email_user, #password, #address, #vendor_id, #vendor_name, #latitude, #longitude, #vendor_bank_location, #vendor_bank_account_number").attr('style', '');
      $("#response_message").attr("style", 'display: none;');
      $(".submit-button").addClass("disabled");
      $(".spinner-border").attr("style", '');

      var pass = true;

      $("#vch-code, #email_user, #password, #address, #vendor_id, #vendor_name, #latitude, #longitude, #vendor_bank_location, #vendor_bank_account_number").each(function(){
          $(this).attr('style', '');
          if($(this).val() == ''){
            $(this).attr('style', 'border: 1px solid #d57171 !important');
            pass = false;
          }
      });

      if(pass){
        var submitData = {
            _token: "{{ csrf_token() }}",
            vch_code: $('#vch-code').val(),
            email: $('#email_user').val(),
            password: $('#password').val(),
            location_code: $('#location_id').val(),
            address: $('#address').val(),
            latitude: $('#latitude').val(),
            longitude: $('#longitude').val(),
            vendor_id: $('#vendor_id').val(),
            vendor_name: $('#vendor_name').val(),
            bank_code: $('#bank_id').val(),
            vendor_bank_address: $('#vendor_bank_location').val(),
            vendor_bank_account_number: $('#vendor_bank_account_number').val(),
        };

        $.ajax({
            type: "POST",
            url: "{{ route('vch.submit') }}",
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
    $("#vch-code, #email_user, #password, #address, #latitude, #longitude, #vendor_id, #vendor_name, #vendor_bank_account_number, #vendor_bank_location").val('');
    $("#location_id, #bank_id").val('select').select2();
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
