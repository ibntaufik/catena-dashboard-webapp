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
                <label for="account_vch_id" class="form-label">VCH Code</label>
                <select id="account_vch_id" class="form-control" name="account_vch_id">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="vendor_id" class="form-label">Vendor ID</label>
                <select id="vendor_id" class="form-control" name="vendor_id">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
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
                <div class="input-group flatpickr me-2 mb-2 mb-md-0" id="div_po_date">
                  <span class="input-group-text input-group-addon bg-transparent" data-toggle><i data-feather="calendar"></i></span>
                  <input type="text" class="form-control bg-transparent" id="po_date" placeholder="Select date" data-input>
                </div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="expected_shipping_date" class="form-label">Expected Shipping Date</label>
                <div class="input-group flatpickr me-2 mb-2 mb-md-0" id="div_expected_shipping_date">
                  <span class="input-group-text input-group-addon bg-transparent" data-toggle><i data-feather="calendar"></i></span>
                  <input type="text" class="form-control bg-transparent" id="expected_shipping_date" placeholder="Select date" data-input>
                </div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="item" class="form-label">Item Name</label>
                <select id="item" class="form-control" name="item">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="item_type" class="form-label">Item Type</label>
                <select id="item_type" class="form-control" name="item_type">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
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
                <select id="item_unit" class="form-control" name="item_unit">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
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
                <input type="text" class="form-control" id="item_max_quantity" maxlength="255" placeholder="Item Max. Quantity" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?" style="text-align:right;" onkeypress="return isNumber(event);" value="" data-type="currency">
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
        <h6 class="card-title">List of purchase order</h6>
        <div class="table-responsive">
          <table id="gridDataTable" class="table">
            <thead>
              <tr>
                <th>VCH Code</th>
                <th>Vendor</th>
                <th>PO Number</th>
                <th>Status</th>
                <th>PO Date</th>
                <th>Expected Shipping Date</th>
                <th>Item Name</th>
                <th>Item Type</th>
                <th>Item Description</th>
                <th>Item Quantity</th>
                <th>Item Unit</th>
                <th>Item Unit Price</th>
                <th>Item Max. Quantity</th>
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

    var vch = {!! json_encode($vch) !!};
    var item = {!! json_encode($item) !!};
    var itemUnit = {!! json_encode($itemUnit) !!};
    var inputForm = null;
    var selectForm = null;

    $(document).ready(function() {
      inputForm = $('.card-body input[type="text"]').map(function() {
          return this.id != '' ? "#"+this.id : null;
      }).get().join(', ');

      selectForm = $('.card-body select').map(function() {
          return this.id != '' ? "#"+this.id : null;
      }).get().join(', ');

      $("#response_message").attr("style", 'display: none;');

      $('#item_type, #vendor_id').select2();

      $("#account_vch_id").select2({
        width: "100%",
        data: vch
      }).on("select2:select", function (e) {
          $('#vendor_id').empty();
          $('#vendor_id').select2({ width: '100%', data: e.params.data.vendor });
      });

      $("#item").select2({
        width: "100%",
        data: item
      }).on("select2:select", function (e) {
        if(e.params.data.selected){
          $('#item_type').empty();
          $('#item_type').select2({ width: '100%', data: e.params.data.itemType });
        }
      });

      $("#item_unit").select2({
        width: "100%",
        data: itemUnit
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
            "url": "{{ route('purchase-order.grid-list') }}",
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
            { "targets": 0, "data": "vch_code", "className": "text-center" },
            { "targets": 1, "data": "vendor" },
            { "targets": 2, "data": "po_number", "className": "text-center" },
            { "targets": 3, "className": "text-center", "data": function( data, type, row, meta ){
                var classLabel = "fontColorWarning";
                if(data.status == "approved"){
                  classLabel = "fontColorApproved";
                } else if(data.status == "rejected"){
                  classLabel = "fontColorRejected";
                }
                selectedPurchaseOrder = data;
                return '<label class="'+classLabel+'" style="cursor: pointer;";>'+ucFirstWord(data.status)+'</label>';
              }
            },
            { "targets": 4, "data": "po_date", "className": "text-center" },
            { "targets": 5, "data": "expected_shipping_date", "className": "text-center" },
            { "targets": 6, "data": "item_name" },
            { "targets": 7, "data": "item_type", "className": "text-center" },
            { "targets": 8, "data": "item_description" },
            { "targets": 9, "data": "item_quantity", "className": "text-end",
              "render":function( data, type, row, meta ){
                var val = (data/1).toFixed(0).replace('.', ',');
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
              }
            },
            { "targets": 10, "data": "item_unit", "className": "text-center" },
            { "targets": 11, "data": "item_unit_price", "className": "text-end" },
            { "targets": 12, "data": "item_max_quantity", "className": "text-end",
              "render":function( data, type, row, meta ){
                var val = (data/1).toFixed(0).replace('.', ',');
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
              }
            },
            { "targets": 13, "data": function(data, type, row, meta){
                  return '<a href="#" onclick=$(this).delete("'+data.po_number+'") style="cursor: pointer;"><i data-feather="trash-2"></i>';
              }
            },
          ],
          "drawCallback": function(settings) {
              feather.replace(); // Initialize Feather icons
          }
      });

      $.fn.delete = function(poNumber) {
        $.ajax({
            type: "POST",
            url: "{{ route('purchase-order.remove') }}",
            data: {
              _token: "{{ csrf_token() }}",
              po_number: poNumber,
            },
            dataType: "json",
            timeout: 300000
        }).done(function(response){
            setTimeout(function() {
              $('#gridDataTable').DataTable().ajax.reload();
            }, 500);
            
        }).fail(function(response){
            
        });
      };
  });
  
  function validateInput(input){
      var re = /^[a-zA-Z0-9.,\s\/-]*$/;
      return re.test(input);
  }

  // Date Picker
  if($('#div_po_date').length) {
    flatpickr("#div_po_date", {
      wrap: true,
      dateFormat: "d-F-Y",
      defaultDate: "today",
      minDate: "today"
    });
  }

  if($('#div_expected_shipping_date').length) {
    flatpickr("#div_expected_shipping_date", {
      wrap: true,
      dateFormat: "d-F-Y",
      defaultDate: "today",
      minDate: "today"
    });
  }
  // Date Picker - END

  function submit()
  {
      $(inputForm).attr('style', '');
      $("#response_message").attr("style", 'display: none;');
      $(".submit-button").addClass("disabled");
      $(".spinner-border").attr("style", '');

      var pass = true;

      $(inputForm).each(function(){
          $(this).attr('style', '');
          if($(this).val() == ''){
            $(this).attr('style', 'border: 1px solid #d57171 !important');
            pass = false;
          }
      });

      $(selectForm).each(function(){
          $(this).next('.select2-container').find('.select2-selection').css('border-color', '')
          if($(this).val() == null){
            $(this).next('.select2-container').find('.select2-selection').css('border-color', 'red')
            pass = false;
          }
      });

      if(pass){
        var submitData = {
            _token: "{{ csrf_token() }}",
            account_vch_id: $("#vendor_id").val(),
            po_number: $("#po_number").val(),
            po_date: moment($("#po_date").val()).format("YYYY-MM-DD"),
            expected_shipping_date: moment($("#expected_shipping_date").val()).format("YYYY-MM-DD"),
            item_type_id: $("#item_type").val(),
            item_description: $("#item_description").val(),
            item_quantity: numeral($("#item_quantity").val()).format('0'),
            item_unit_id: $("#item_unit").val(),
            item_unit_price: numeral($("#item_unit_price").val()).format('0'),
            item_max_quantity: numeral($("#item_max_quantity").val()).format('0'),
            status: "waiting"
        };

        $.ajax({
            type: "POST",
            url: "{{ route('purchase-order.submit') }}",
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
            }
            $("#response_message").attr("style", '');
            $("#success").html(data.message);

            $('#response_message').fadeTo(3000, 500).slideUp(500, function() {
              $("#success-alert").slideUp(500);
            });
            
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
      }

      $(".submit-button").removeClass("disabled");
      $(".spinner-border").attr("style", "display: none;");
  }

  function reset(){
    $(inputForm).val('');
    $(selectForm).val('select').select2();
    $(".vendor_id").val('');
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
