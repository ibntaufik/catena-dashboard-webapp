@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<style type="text/css">
  
</style>
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Transaction</a></li>
    <li class="breadcrumb-item">Farmer &amp; VCP Transaction</li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
  </ol>
</nav>

<div class="col-md-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h6 class="card-title">Detail of transaction</h6>
      <div class="col-md-12">
        
        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label class="form-label">Farmer ID</label>
            </div>
          </div>
          <div class="col-md-9">
            <div class="form-group">
              <label>:&nbsp;</label><label id="farmer_code" class="form-label"></label>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label class="form-label">Farmer Name</label>
            </div>
          </div>
          <div class="col-md-9">
            <div class="form-group">
              <label>:&nbsp;</label><label id="farmer_name" class="form-label"></label>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label class="form-label">VCP Code</label>
            </div>
          </div>
          <div class="col-md-9">
            <div class="form-group">
              <label>:&nbsp;</label><label id="vcp_code" class="form-label"></label>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label class="form-label">Pulper Name</label>
            </div>
          </div>
          <div class="col-md-9">
            <div class="form-group">
              <label>:&nbsp;</label><label id="pulper_name" class="form-label"></label>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label class="form-label">Transaction ID</label>
            </div>
          </div>
          <div class="col-md-9">
            <div class="form-group">
              <label>:&nbsp;</label><label id="transaction_id" class="form-label"></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label class="form-label">PO Number</label>
            </div>
          </div>
          <div class="col-md-9">
            <div class="form-group">
              <label>:&nbsp;</label><label id="po_number" class="form-label"></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label class="form-label">Receipt Number</label>
            </div>
          </div>
          <div class="col-md-9">
            <div class="form-group">
              <label>:&nbsp;</label><label id="receipt_number" class="form-label"></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label class="form-label">Transaction Date</label>
            </div>
          </div>
          <div class="col-md-9">
            <div class="form-group">
              <label>:&nbsp;</label><label id="transaction_date" class="form-label"></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label class="form-label">Item Type</label>
            </div>
          </div>
          <div class="col-md-9">
            <div class="form-group">
              <label>:&nbsp;</label><label id="item_type" class="form-label"></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label class="form-label">Floating Rate (Kg)</label>
            </div>
          </div>
          <div class="col-md-9">
            <div class="form-group">
              <label>:&nbsp;</label><label id="floating_rate" class="form-label"></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label class="form-label">Item Price (Rp)</label>
            </div>
          </div>
          <div class="col-md-9">
            <div class="form-group">
              <label>:&nbsp;</label><label id="item_price" class="form-label"></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label class="form-label">Item Quantity</label>
            </div>
          </div>
          <div class="col-md-9">
            <div class="form-group">
              <label>:&nbsp;</label><label id="item_quantity" class="form-label"></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label class="form-label">Total Price (Rp)</label>
            </div>
          </div>
          <div class="col-md-9">
            <div class="form-group">
              <label>:&nbsp;</label><label id="total_price" class="form-label"></label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('javascript')

<script src="{{ asset('assets/js/format-currency.js') }}"></script>
<script type="text/javascript">
    var result = {!! json_encode($result) !!};
    
    $("#farmer_code").html(result.farmer_code);
    $("#farmer_name").html(result.farmer_name);
    $("#vcp_code").html(result.evc_code+"-"+result.vch_code+"-"+result.vcp_code);
    $("#pulper_name").html(result.pulper_name);
    $("#transaction_id").html(result.transaction_id);
    $("#po_number").html(result.po_number);
    $("#receipt_number").html(result.receipt_number);
    $("#transaction_date").html(result.transaction_date);
    $("#item_type").html(result.item_type);
    $("#item_price").html(formatPrice(result.item_unit_price, 0)+",-");
    $("#item_quantity").html(formatPrice(result.item_quantity, 0));
    $("#floating_rate").html(formatPrice(result.floating_rate, 0));
    $("#total_price").html(formatPrice(result.total_item_price, 0)+",-");

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
