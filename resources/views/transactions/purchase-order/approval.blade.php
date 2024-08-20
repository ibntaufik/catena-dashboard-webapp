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
        <h6 class="card-title">List of purchase order</h6>

        <div data-tabs class="tabs mt-5" style="">    
           <div class="col-md-2 tab">
              <input type="radio" name="tabgroup" id="waiting" checked onclick="search('waiting')">
              <label for="waiting">Waiting</label>
           </div>
           <div class="col-md-2 tab">
              <input type="radio" name="tabgroup" id="approved" onclick="search('approved')">
              <label for="approved">Approved</label>
           </div>
           <div class="col-md-2 tab">
              <input type="radio" name="tabgroup" id="rejected" onclick="search('rejected')">
              <label for="rejected">Rejected</label>
           </div>
        </div>

        <div class="table-responsive">
          <table id="gridDataTable" class="table">
            <thead>
              <tr>
                <th>Status</th>
                <th>VCH Code</th>
                <th>Vendor</th>
                <th>PO Number</th>
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
    var poStatus = "waiting";

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
            "url": "{{ route('purchase-order.grid-list') }}",
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
            { "targets": 0, "data": "status", "className": "text-center",
              "render":function( data, type, row, meta ){
                var fontColor = "#D88E00";
                if(data == "approved"){
                  fontColor = "#148F00";
                } else if(data == "rejected"){
                  fontColor = "#FF0909";
                }
                return '<label style="color: '+fontColor+'";>'+ucFirstWord(data)+'</label>';
              }
            },
            { "targets": 1, "data": "vch_code", "className": "text-center" },
            { "targets": 2, "data": "vendor" },
            { "targets": 3, "data": "po_number", "className": "text-center" },
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
        }).done(function(data){
            setTimeout(function() {
              $('#gridDataTable').DataTable().ajax.reload();
            }, 500);
            
        }).fail(function(data){
            
        });
      };
    });

    function submit(){
        
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
