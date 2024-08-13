@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
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
    <li class="breadcrumb-item"><a href="#">Transaction</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create Purchase Order</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <h6 class="card-title">New Purchase Order</h6>
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
                <label for="field_coordirnator_id" class="form-label">Field Coordinator ID</label>
                <input type="text" class="form-control" id="field_coordirnator_id" maxlength="255" placeholder="Field Coordinator ID" onkeypress="return isAlphaNumericDash(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="po_number" class="form-label">PO Number</label>
                <input type="text" class="form-control" id="po_number" maxlength="255" placeholder="PO Number" onkeypress="return isAlphaNumericDashSlash(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="po_date" class="form-label">PO Date</label>
                <div class="input-group flatpickr me-2 mb-2 mb-md-0" id="po_date">
                  <span class="input-group-text input-group-addon bg-transparent" data-toggle><i data-feather="calendar"></i></span>
                  <input type="text" class="form-control bg-transparent" placeholder="Select date" data-input>
                </div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="expected_shipping_date" class="form-label">Expecting Shipping Date</label>
                <div class="input-group flatpickr me-2 mb-2 mb-md-0" id="expected_shipping_date">
                  <span class="input-group-text input-group-addon bg-transparent" data-toggle><i data-feather="calendar"></i></span>
                  <input type="text" class="form-control bg-transparent" placeholder="Select date" data-input>
                </div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="item_name" class="form-label">Item Name</label>
                <input type="text" class="form-control" id="item_name" maxlength="255" placeholder="Item Name" onkeypress="return isAlphaNumericAndWhiteSpace(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="item_type" class="form-label">Item Type</label>
                <input type="text" class="form-control" id="item_type" maxlength="255" placeholder="Item Type" onkeypress="return isAlphaNumericAndWhiteSpace(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="item_description" class="form-label">Item Description</label>
                <input type="text" class="form-control" id="item_description" maxlength="255" placeholder="Item Description" onkeypress="return validateDescription(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="item_quantity" class="form-label">Item Quantity</label>
                <input type="text" class="form-control" id="item_quantity" maxlength="255" placeholder="Item Quantity" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?" style="text-align:right;" onkeypress="return isNumber(event);" value="" data-type="currency">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="item_unit" class="form-label">Item Unit</label>
                <input type="text" class="form-control" id="item_unit" maxlength="255" placeholder="Item Unit" onkeypress="return validateDescription(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="item_unit_price" class="form-label">Item Unit Price</label>
                <input type="text" class="form-control" id="item_unit_price" maxlength="255" placeholder="Item Unit Price" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?" style="text-align:right;" onkeypress="return isNumber(event);" value="" data-type="currency">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="item_ax_quantity" class="form-label">Item Max. Quantity</label>
                <input type="text" class="form-control" id="item_ax_quantity" maxlength="255" placeholder="Item Max. Quantity" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?" style="text-align:right;" onkeypress="return isNumber(event);" value="" data-type="currency">
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

<script src="{{ asset('assets/js/format-currency.js') }}"></script>
<script type="text/javascript">
    var start = 0;
    var limit = 10;

    

  $(document).ready(function() {
      
      $("#response_message").attr("style", 'display: none;');
      $("#vch_code").select2();
      
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

  // Date Picker
  if($('#po_date').length) {
    flatpickr("#po_date", {
      wrap: true,
      dateFormat: "d-M-Y",
      defaultDate: "today",
      minDate: "today"
    });
  }

  if($('#expected_shipping_date').length) {
    flatpickr("#expected_shipping_date", {
      wrap: true,
      dateFormat: "d-M-Y",
      defaultDate: "today",
      minDate: "today"
    });
  }
  // Date Picker - END

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
  <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>

@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
