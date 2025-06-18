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

  .accordion-button{
    background-color: #FFFFFF;
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
    <li class="breadcrumb-item active" aria-current="page">Farmer &amp; VCP Transaction</li>
  </ol>
</nav>

<div class="col-md-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h6 class="card-title">List of transaction</h6>

      <div data-tabs class="tabs mt-5" style="display: none;">
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

      <div class="accordion" id="accordionFilter">
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              Filter
            </button>
          </h2>
          <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              <div class="card">
                <div class="card-body">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Farmer Name</label>
                          <input id="farmer_name" class="form-control" name="farmer_name"/>
                        </div>
                        <div class="form-group pt-2">
                          <label class="form-label">VCP Code</label>
                          <input id="vcp_code" class="form-control" name="vcp_code"/>
                        </div>
                        <div class="form-group pt-2">
                          <label class="form-label">Transaction ID</label>
                          <input id="transaction_id" class="form-control" name="transaction_id"/>
                        </div>
                      </div>

                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">PO Number</label>
                          <input id="po_number" class="form-control" name="po_number"/>
                        </div>
                        <div class="form-group pt-2">
                          <label class="form-label">Receipt Number</label>
                          <input id="receipt_number" class="form-control" name="receipt_number"/>
                        </div>
                        <div class="form-group pt-2">
                          <label for="daterange_transaction" class="form-label">Transaction Date</label>
                          <div class="input-group flatpickr " id="div_daterange_transaction">
                            <span class="input-group-text input-group-addon bg-transparent" data-toggle><i data-feather="calendar"></i></span>
                            <input type="text" class="form-control bg-transparent" id="daterange_transaction" placeholder="Select date range" data-input>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Item Type</label>
                          <input id="item_type" class="form-control" name="item_type"/>
                        </div>
                        <div class="form-group pt-2">
                          <label class="form-label">Floating Rate (Kg)</label>
                          <input id="floating_rate" class="form-control" name="floating_rate"/>
                        </div>
                        <div class="form-group pt-2">
                          <label class="form-label">Item Price (Rp)</label>
                          <input id="item_price" class="form-control" name="item_price"/>
                        </div>
                      </div>

                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Item Quantity</label>
                          <input id="item_quantity" class="form-control" name="item_quantity"/>
                        </div>
                        <div class="form-group pt-2">
                          <label class="form-label">Total Price (Rp)</label>
                          <input id="total_price" class="form-control" name="total_price"/>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-9">
                  </div>
                  <div class="col-sm-3">
                    <button id="id_btn_filter" class="btn btn-flat btn-primary" style="width: 45% !important;">Filter</button>
                    <button id="id_btn_clear" class="btn btn-secondary me-2 submit-button" style="width: 45% !important;">Cancel</button>
                  </div>
                </div>
                
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table id="gridDataTable" class="table">
          <thead>
            <tr>
              <th class="text-center">Transaction No.</th>
              <th class="text-center">Transaction Date</th>
              <th>PO Number</th>
              <th>VCP Code</th>
              <th>Farmer</th>
              <th>Total Qty</th>
              <th>Item Price</th>
              <th>Item Type</th>
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
          "serverSide"    : true,
          "searching"     : false,
          "pageLength"    : limit,
          "ajax": {
            "url": "{{ route('purchase-order.transaction.grid-list') }}",
            "data": function ( d ) {
              var info = $('#gridDataTable').DataTable().page.info();
              d.start = info.start;
              d.limit = limit;
              d.status = $("#status").val();;
              d.farmer_name = $("#farmer_name").val();
              d.vcp_code = $("#vcp_code").val();
              d.transaction_id = $("#transaction_id").val();
              d.daterange_transaction = $("#daterange_transaction").val();
              d.po_number = $("#po_number").val();
              d.receipt_number = $("#receipt_number").val();
              d.item_type = $("#item_type").val();
              d.floating_rate = $("#floating_rate").val();
              d.item_price = $("#item_price").val();
              d.item_quantity = $("#item_quantity").val();
              d.total_price = $("#total_price").val();
            },
            "dataSrc": function(json){
              
              json.recordsTotal = json.count;
              json.recordsFiltered = json.count;
              
              return json.data;
            }
          },
                                
          "columnDefs" : [
            { "targets": 0, "className": "text-center", "data": function( data, type, row, meta ){
                return '<a onclick=$(this).detailTransaction("'+data.transaction_id+'","'+data.farmer_code+'") class="form-label" style="cursor: pointer;">'+data.transaction_id+'</a>';
              }
            },
            { "targets": 1, "className": "text-center", "data": function(data, type, row, meta){
                  return data.transaction_date;
              }
            },
            { "targets": 2, "className": "text-center", "data": function(data, type, row, meta){
                  return data.po_number;
              }
            },
            { "targets": 3, "data": "vcp_code", "className": "text-center" },
            { "targets": 4, "data": "farmer_name", "className": "text-center" },
            { "targets": 5, "data": "item_quantity", "className": "text-center" },
            { "targets": 6, "className": "text-end", "data": function(data, type, row, meta){
                  return formatPrice(data.item_price, 0);
              }
            },
            { "targets": 7, "data": "item_type", "className": "text-center" }
            
          ],
          "drawCallback": function(settings) {
              feather.replace(); // Initialize Feather icons
          }
      });

      $('#id_btn_filter').on('click',function(e) {
          e.stopImmediatePropagation();
          $('#gridDataTable').DataTable().ajax.reload();
      });

      $('#id_btn_clear').on('click',function(e) {
        $("#farmer_code, #vcp_code, #transaction_id, #daterange_transaction, #po_number, #receipt_number, #item_type, #floating_rate, #item_price, #item_quantity, #total_price").val("");
          e.stopImmediatePropagation();
          $('#gridDataTable').DataTable().ajax.reload();
      });
    });

  // Date Picker
  if($('#div_daterange_transaction').length) {
    flatpickr("#div_daterange_transaction", {
      wrap: true,
      dateFormat: "d-m-Y",
      mode: "range"
    });
  }
            
  $.fn.detailTransaction = function(id, code) {           
    //window.open(('{{ route('purchase-order.transaction.detail', ['id' => '']) }}/' + id), '_blank' );
    window.open(('{{ route('purchase-order.transaction.detail') }}?trx-id=' + id +'&farmer-code='+code), '_blank' );
  };

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
