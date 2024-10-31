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

  .tabs {
    clear: both;
    position: relative;
    margin: 0 auto;
    margin-left: 100px;
     /* you can either manually set a min-height here or do it via JS ---> */
  }

  .tab {
    float: left;
  }

  .tab label {
    margin-right: 20px;
    position: relative;
    top: 0;
    cursor: pointer;
    color: #000000;
    text-transform: uppercase;
  }

  .tab [type=radio] {
    display: none;   
  }

  [type=radio]:checked ~ label {
    border-bottom: 2px solid #B91202;
    color: #B91202;
    z-index: 2;
  }

</style>
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Transaction</a></li>
    <li class="breadcrumb-item active" aria-current="page">Approval Purchase Order</li>
  </ol>
</nav>

<div class="col-md-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h6 class="card-title">List of transaction</h6>

      <div data-tabs class="tabs mt-5" style="">
         <div class="col-md-2 tab">
            <input type="radio" name="tabgroup" id="created" checked onclick="search('created')">
            <label for="created">Created</label>
         </div>
         <div class="col-md-2 tab">
            <input type="radio" name="tabgroup" id="onprocess" onclick="search('on_process')">
            <label for="onprocess">On Process</label>
         </div>
         <div class="col-md-2 tab">
            <input type="radio" name="tabgroup" id="blockchained" onclick="search('blockchained')">
            <label for="blockchained">Blockchained</label>
         </div>
      </div>

      <div class="table-responsive">
        <table id="gridDataTable" class="table">
          <thead>
            <tr>
              <th>Farmer ID</th>
              <th>VCP Code</th>
              <th class="text-center">Transaction ID</th>
              <th>Transaction Date</th>
              <th>PO Number</th>
              <th>Receipt Number</th>
              <th>Item Type</th>
              <th>Floating Rate (Kg)</th>
              <th>Item Price (Rp)</th>
              <th>Item Qty</th>
              <th>Total Price (Rp)</th>
            </tr>
          </thead>
        </table>
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
    var poStatus = "created";
    var selectedPurchaseOrder = null;
    var listApprover = null;

    $(document).ready(function() {
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
            "url": "{{ route('purchase-order.transaction.grid-list') }}",
            "data": function ( d ) {
              var info = $('#gridDataTable').DataTable().page.info();
              d.start = info.start;
              d.limit = limit;
              d.status = poStatus;
            },
            "dataSrc": function(json){
              
              json.recordsTotal = json.data.length;
              json.recordsFiltered = json.data.length;

              return json.data;
            }
          },
                                
          "columnDefs" : [
            { "targets": 0, "className": "text-center", "data": function( data, type, row, meta ){
                return data.farmer_code;
              }
            },
            { "targets": 1, "className": "text-center", "data": function(data, type, row, meta){
                  return data.vcp_code;
              }
            },
            { "targets": 2, "className": "text-center", "data": function(data, type, row, meta){
                  return data.transaction_id;
              }
            },
            { "targets": 3, "data": "transaction_date", "className": "text-center" },
            { "targets": 4, "data": "po_number", "className": "text-center" },
            { "targets": 5, "data": "receipt_number", "className": "text-center" },
            { "targets": 6, "data": "item_type", "className": "text-center" },
            { "targets": 7, "data": "floating_rate", "className": "text-center",
              "render":function( data, type, row, meta ){
                var val = (data/1).toFixed(0).replace('.', ',');
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
              }
            }, { "targets": 8, "data": "item_price", "className": "text-center",
              "render":function( data, type, row, meta ){
                var val = (data/1).toFixed(0).replace('.', ',');
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
              }
            }, { "targets": 9, "data": "item_quantity", "className": "text-center",
              "render":function( data, type, row, meta ){
                var val = (data/1).toFixed(0).replace('.', ',');
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
              }
            }, { "targets": 10, "data": "total_price", "className": "text-end",
              "render":function( data, type, row, meta ){
                var val = (data/1).toFixed(0).replace('.', ',');
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
              }
            }
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

    function submit(status){
      $("#reason").attr('style', '');
      if(status == "rejected" && $("#reason").val().length == 0){
        $("#reason").attr('style', 'border: 1px solid #d57171 !important');
        alert("Silakan isi alasan terlebih dahulu");
      } else {
        
        $.ajax({
            type: "POST",
            url: "{{ route('purchase-order.update') }}",
            data: {
              _token: "{{ csrf_token() }}",
              po_number: $("#labelPoNumber").text(),
              po_status: status,
              reason: $("#reason").val()
            },
            dataType: "json",
            timeout: 300000
        }).done(function(response){
            if(response.code == 200){
              $("#reason").val("");
              $('#waitingModal').modal('hide');
              setTimeout(function() {
                $('#gridDataTable').DataTable().ajax.reload();
              }, 500);
            }
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
    }

    function showDetail(status){
      $("#labelWarning").html(ucFirstWord(status));

      var classLabel = "fontColorWarning";
      $("#reason").removeAttr("readonly");
      $("#reject, #approve").hide();
      latestHistory(status);

      if(status == "approved"){
        classLabel = "fontColorApproved";
      } else if(status == "rejected"){
        classLabel = "fontColorRejected";
      }

      if(["waiting"].includes(status)){
        $("#reason").val('');
      } else {
        $("#reject, #approve").hide();
        $("#reason").attr("readonly", "readonly");
      }

      $('#historyApproval tbody').empty();
      if(listApprover.length > 0){
        renderApproval(listApprover);
      }
      $("#labelVchCode").html(selectedPurchaseOrder.evc_code+' - '+selectedPurchaseOrder.vch_code);
      $("#labelVendor").html('('+selectedPurchaseOrder.vendor_code+') '+selectedPurchaseOrder.vendor);
      $("#labelPoNumber").html(selectedPurchaseOrder.po_number);
      $("#labelItem").html(selectedPurchaseOrder.item_name);
      $("#labelPoDate").html(selectedPurchaseOrder.po_date);
      $("#labelExpectedShippingDate").html(selectedPurchaseOrder.po_date);
      $("#labelItemUnit").html(selectedPurchaseOrder.item_unit);
      $("#labelItemUnitPrice").html(formatPrice(selectedPurchaseOrder.item_unit_price, 0));
      $("#labelItemType").html(selectedPurchaseOrder.item_type);
      $("#labelItemDescription").html(selectedPurchaseOrder.item_description);
      $("#labelItemQuantity").html(formatPrice(selectedPurchaseOrder.item_quantity, 0));
      $("#labelItemMaxQuantity").html(formatPrice(selectedPurchaseOrder.item_max_quantity, 0));
      $("#labelWarning").addClass(classLabel);
      $('#waitingModal').modal('show');
    }

    function latestHistory(status){
        $.ajax({
          type: "GET",
          url: "{{ route('purchase-order.latest-history') }}",
          data: {
            _token: "{{ csrf_token() }}",
            po_number: selectedPurchaseOrder.po_number,
            po_status: status
          },
          dataType: "json",
          timeout: 300000,
          async:false,
      }).done(function(response){
          $("#reason").val(response.data.reason);
          if((response.data.show_approval_buttons)){
            $("#reject, #approve").show();
          } else {
            $("#reject, #approve").hide();
          }
          listApprover = response.data.approver;

      }).fail(function(response){
          
      });
    }

    function renderApproval(data){
      $.each(data, function(key, value){

        var label = '<span class="label label-waiting">Waiting</span>';
        if(value.status == "approved"){
          label = '<span class="label label-approved">Approved</span>';
        } else if(value.status == "rejected"){
          label = '<span class="label label-rejected">Reject</span>';
        }

        $('#historyApproval > tbody:last-child').append('<tr>'+
                  '<td style="text-align: center;padding: 5px;vertical-align: middle;">'+value.text+'</td>'+
                  '<td style="text-align: center;padding: 5px;">'
                  +label+
                  '<br>'+(value.updated_at == null ? "-" : moment(value.updated_at).format("DD/MM/YYYY HH:mm:ss"))+
                  '</td>'+
        '</tr>');
      });
    }

    function search(status){
        poStatus = status;
        $('#gridDataTable').DataTable().ajax.reload();
    }

    (function($, document) {
    
      // get tallest tab__content element
      let height = -1;
        
        // set height of tabs + top offset
      $('[data-tabs]').css('min-height', height + 40 + 'px');
   
    }(jQuery, document));


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
