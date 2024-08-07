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
    <li class="breadcrumb-item active" aria-current="page">VCP Account</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <h6 class="card-title">Create VCP Account</h6>
          <div class="row">
            
            <input type="text" name="username_fake" id="username_fake" autocomplete="off" style="display:none;">
            <input type="password" name="password_fake" autocomplete="off" style="display:none;">

            <div class="col-sm-3">
              <div class="mb-3">
                <label for="vcp-code" class="form-label">VCP Code</label>
                <input type="text" class="form-control" id="vcp-code" maxlength="255" autocomplete="off" placeholder="ID-XYZ-123" onkeypress="return isAlphaNumericDash(event);">
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
                <label for="field_coordinator_id" class="form-label">Field Coordinator ID</label>
                <input type="text" class="form-control" id="field_coordinator_id" maxlength="255" placeholder="Field Coordinator ID" onkeypress="return isAlphaNumericDash(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="field_coordinator_name" class="form-label">Field Coordinator Name</label>
                <input type="text" class="form-control" id="field_coordinator_name" maxlength="255" placeholder="Field Coordinator Name" onkeypress="return isAlphaNumericAndWhiteSpace(event);">
              </div>
            </div>

            @include("layout.coverage")

            <div class="col-sm-6">
              <div class="mb-3">
                <label for="city" class="form-label">Address</label>
                <textarea type="text" class="form-control" id="address" rows="3" placeholder="Address" onkeypress="return validateAddress(event);"></textarea>
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
        <h6 class="card-title">List of VCP Account</h6>
        <div class="table-responsive">
          <table id="gridDataTable" class="table">
            <thead>
              <tr>
                <th>VCP Code</th>
                <th>Email</th>
                <th>Location</th>
                <th>Address</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Field Coord. ID</th>
                <th>Field Coord. Name</th>
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
<script src="{{ asset('assets/js/coverage.js') }}"></script>
<script type="text/javascript">
    var start = 0;
    var limit = 10;

    // add by faisal
    // used for coverage.js file
    var comboDefault = [{
      id: "select",
      text: '-- Select --',
      disabled: true
    }];

    var province = {!! json_encode($province) !!};
    var url_coverage_city = "{{ route('coverage.city') }}";
    var url_coverage_district = "{{ route('coverage.district') }}";
    var url_coverage_sub_district = "{{ route('coverage.sub_district') }}";

  $(document).ready(function() {
      
      $("#response_message").attr("style", 'display: none;');

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
            "url": "{{ route('vcp.grid-list') }}",
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
            { "targets": 0, "data": "vcp_code" },
            { "targets": 1, "data": "email" },
            { "targets": 2, "data": "location" },
            { "targets": 3, "data": "address" },
            { "targets": 4, "data": "latitude" },
            { "targets": 5, "data": "longitude" },
            { "targets": 6, "data": "field_coordinator_id" },
            { "targets": 7, "data": "field_coordinator_name" },
            { "targets": 8, "data": function(data, type, row, meta){
                  return '<a href="#" onclick=$(this).delete("'+data.vcp_code+'") style="cursor: pointer;"><i data-feather="trash-2"></i>';
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
            url: "{{ route('vcp.remove') }}",
            data: {
              _token: "{{ csrf_token() }}",
              vcp_code: code,
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
      $("#vcp-code, #address, #email_user, #password, #field_coordinator_id, #field_coordinator_name, #latitude, #longitude").attr('style', '');
      $("#response_message").attr("style", 'display: none;');
      $(".submit-button").addClass("disabled");
      $(".spinner-border").attr("style", '');

      var pass = true;

      $("#vcp-code, #email_user, #password, #address, #field_coordinator_id, #field_coordinator_name, #latitude, #longitude").each(function(){
          $(this).attr('style', '');
          if($(this).val() == ''){
            $(this).attr('style', 'border: 1px solid #d57171 !important');
            pass = false;
          }
      });

      if(pass){
        var submitData = {
            _token: "{{ csrf_token() }}",
            vcp_code: $('#vcp-code').val(),
            email: $('#email_user').val(),
            password: $('#password').val(),
            sub_district_id: $('#sub_district').val(),
            address: $('#address').val(),
            latitude: $('#latitude').val(),
            longitude: $('#longitude').val(),
            field_coordinator_id: $('#field_coordinator_id').val(),
            field_coordinator_name: $('#field_coordinator_name').val()
        };

        $.ajax({
            type: "POST",
            url: "{{ route('vcp.submit') }}",
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

              if(data.message.includes("Email already registered")){
                $("#email_user").attr('style', 'border: 1px solid #d57171 !important');
              }
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
      $("#vcp-code, #email_user, #password, #address, #latitude, #longitude, #field_coordinator_id, #field_coordinator_name").val('');
    
      // add by faisal
      // used to reset coverage combo from coverage.js file
      $('#city, #district, #sub_district').empty();
      $('#city, #district, #sub_district').select2({ width: '100%', data: comboDefault });
      $('#province, #city, #district, #sub_district').val('select').select2();
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
