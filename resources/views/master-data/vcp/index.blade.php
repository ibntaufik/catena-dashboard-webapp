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
    <li class="breadcrumb-item active" aria-current="page">VCP</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <h6 class="card-title">Create VCP</h6>
          <div class="row">

            <div class="col-sm-3">
              <div class="mb-3">
                <label for="vch_code" class="form-label">VCH Code</label>
                <select id="vch_code" class="form-control" name="vch_code">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
              </div>
            </div>

            <div class="col-sm-3">
              <div class="mb-3">
                <label for="name" class="form-label">Code</label>
                <input type="text" class="form-control" id="code" maxlength="255" placeholder="VCP Code" onkeypress="return isAlphaNumericDash(event);">
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
        <h6 class="card-title">List of VCP</h6>
        <div class="table-responsive">
          <table id="gridDataTable" class="table">
            <thead>
              <tr>
                <th>Code</th>
                <th>Alamat</th>
                <th>Latitude</th>
                <th>Longitude</th>
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
    var fileType = "";
    var vch = {!! json_encode($vch) !!};

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
      $("#vch_code").select2({
        width: '100%',
        data: vch
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
            { "targets": 0, "data": function(data, type, row, meta){
                  return data.evc_code+'-'+data.vch_code+'-'+data.code;
              }
            },
            { "targets": 1, "data": function(data, type, row, meta){
                  return data.address+',<br>'+data.sub_district+',<br>'+data.district+',<br>'+data.city+',<br>'+data.province;
              }
            },
            { "targets": 2, "data": "latitude" },
            { "targets": 3, "data": "longitude" },
            { "targets": 4, "data": function(data, type, row, meta){
                  return '<a href="#" onclick=$(this).delete("'+data.code+'") style="cursor: pointer;"><i data-feather="trash-2"></i>';
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
        }).done(function(response){
            $('#gridDataTable').DataTable().ajax.reload();
        }).fail(function(response){
            var message = "";

            if(response.status == 422){
              message = parseErrorMessage(response);
            } else {
              message = "Koneksi ke server terkendala. Silakan coba lagi.";
            }

            $("#success").html(message);
            $("#response_message").attr("style", '');
            $('#response_message').fadeTo(3000, 500).slideUp(500, function() {
              $("#success-alert").slideUp(500);
            });

            $('#response_message').removeClass('alert-success');
            $('#response_message').addClass('alert-danger');
        });
      };
  });
  
  function validateInput(input){
      var re = /^[a-zA-Z0-9.,\s\/-]*$/;
      return re.test(input);
  }

  function submit()
  {
      $("#code, #address, #latitude, #longitude").attr('style', '');
      $("#response_message").attr("style", 'display: none;');
      $(".submit-button").addClass("disabled");
      $(".spinner-border").attr("style", '');

      var pass = true;

      $("#code, #address, #latitude, #longitude").each(function(){
          $(this).attr('style', '');
          if($(this).val() == ''){
            $(this).attr('style', 'border: 1px solid #d57171 !important');
            pass = false;
            return;
          }
      });

      if(pass){
        var submitData = {
            _token: "{{ csrf_token() }}",
            code: $('#code').val(),
            vch_code: $('#vch_code').val(),
            sub_district_id: $('#sub_district').val(),
            latitude: $('#latitude').val(),
            longitude: $('#longitude').val(),
            address: $('#address').val(),
        };

        $.ajax({
            type: "POST",
            url: "{{ route('vcp.submit') }}",
            data: submitData,
            dataType: "json",
            timeout: 300000
        }).done(function(response){
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
        }).fail(function(response){
            var message = "";

            if(response.status == 422){
              message = parseErrorMessage(response);
            } else {
              message = "Koneksi ke server terkendala. Silakan coba lagi.";
            }

            $("#success").html(message);
            $("#response_message").attr("style", '');
            $('#response_message').fadeTo(3000, 500).slideUp(500, function() {
              $("#success-alert").slideUp(500);
            });

            $('#response_message').removeClass('alert-success');
            $('#response_message').addClass('alert-danger');
        });
      }

      $(".submit-button").removeClass("disabled");
      $(".spinner-border").attr("style", "display: none;");
  }

  function reset(){

      $('#gridDataTable').DataTable().ajax.reload();
      $("#code, #address, #latitude, #longitude").val('');
      $('#vch_code').val('select').select2();

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
