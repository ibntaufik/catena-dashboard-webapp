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
    <li class="breadcrumb-item active" aria-current="page">Location</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <h6 class="card-title">Create Location</h6>
          <div class="row">
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="id-location" class="form-label">ID Location</label>
                <input type="text" class="form-control" id="id-location" maxlength="255" autocomplete="off" placeholder="IDXYZ123" onkeypress="return isAlphaNumeric(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="province" class="form-label">Provinsi</label>
                <select id="province" class="form-control" name="province">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="city" class="form-label">Kota/Kab</label>
                <select id="city" class="form-control" name="city">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="district" class="form-label">Kecamatan</label>
                <select id="district" class="form-control" name="district">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="sub_district" class="form-label">Desa/Kelurahan</label>
                <input id="sub_district" class="form-control" name="sub_district" placeholder="Desa"></input>
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
        <h6 class="card-title">List of locations</h6>
        <div class="table-responsive">
          <table id="gridDataTable" class="table">
            <thead>
              <tr>
                <th>Provinsi</th>
                <th>Kabupaten</th>
                <th>Kecamatan</th>
                <th>Desa</th>
                <th>ID Location</th>
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

      $('#district').select2();
      $('#province').select2({
            width: '100%',
            data: province
      }).on("select2:select", function (e) {
        if(e.params.data.selected){
          $.ajax({
              type: "GET",
              url: url_coverage_city, // define from page
              data: {
                _token: "{{ csrf_token() }}",
                province_id: e.params.data.id,
              },
              dataType: "json",
              timeout: 300000
          }).done(function(response){
            $('#city').empty();
            $('#city').select2({ width: '100%', data: response.data });
          }).fail(function(data){
              
          });
        }
      });
      
      $('#city').select2().on("select2:select", function (e) {
        if(e.params.data.selected){
          $.ajax({
              type: "GET",
              url: url_coverage_district,
              data: {
                _token: "{{ csrf_token() }}",
                city_id: e.params.data.id,
              },
              dataType: "json",
              timeout: 300000
          }).done(function(response){
            $('#district').empty();
            $('#district').select2({ width: '100%', data: response.data });
          }).fail(function(data){
              
          });
        }
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
            "url": "{{ route('location.grid-list') }}",
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
            { "targets": 0, "data": "province" },
            { "targets": 1, "data": "city" },
            { "targets": 2, "data": "district" },
            { "targets": 3, "data": "sub_district" },
            { "targets": 4, "data": "code" },
            { "targets": 5, "data": "latitude" },
            { "targets": 6, "data": "longitude" },
            { "targets": 7, "data": function(data, type, row, meta){
                  return '<a href="#" onclick=$(this).deleteLocation("'+data.code+'") style="cursor: pointer;"><i data-feather="trash-2"></i>';
              }
            },
          ],
          "drawCallback": function(settings) {
              feather.replace(); // Initialize Feather icons
          }
      });

      $.fn.deleteLocation = function(locationId) {
        $.ajax({
            type: "POST",
            url: "{{ route('location.remove') }}",
            data: {
              _token: "{{ csrf_token() }}",
              location_id: locationId,
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
      $("#id-location, #sub-district").attr('style', '');
      $("#response_message").attr("style", 'display: none;');
      $(".submit-button").addClass("disabled");
      $(".spinner-border").attr("style", '');

      var pass = true;

      $("#id-location, #sub-district, #district, #city, #province, #latitude, #longitude").each(function(){
          $(this).attr('style', '');
          if($(this).val() == ''){
            $(this).attr('style', 'border: 1px solid #d57171 !important');
            pass = false;
          }
      });

      if(pass){
        var submitData = {
            _token: "{{ csrf_token() }}",
            code: $('#id-location').val(),
            district_id: $('#district').val(),
            name: $('#sub_district').val(),
            latitude: $('#latitude').val(),
            longitude: $('#longitude').val()
        };

        $.ajax({
            type: "POST",
            url: "{{ route('location.submit') }}",
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
            $("#success").html(data.message);

            $('#response_message').fadeTo(3000, 500).slideUp(500, function() {
              $("#success-alert").slideUp(500);
            });
            
            $('#gridDataTable').DataTable().ajax.reload();
            
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
    $("#id-location, #latitude, #longitude").val('');
    // add by faisal
    // used to reset coverage combo from coverage.js file
    $('#city, #district').empty();
    $('#city, #district').select2({ width: '100%', data: comboDefault });
    $('#province, #city, #district').val('select').select2();
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
