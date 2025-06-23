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
    <li class="breadcrumb-item"><a href="#">Farmer</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Farmer</h6>
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
                <label class="form-label">Name</label>
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
                <label class="form-label">NIK</label>
              </div>
            </div>
            <div class="col-md-9">
              <div class="form-group">
                <label>:&nbsp;</label><label id="id_number" class="form-label"></label>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                <label class="form-label">Location</label>
              </div>
            </div>
            <div class="col-md-9">
              <div class="form-group">
                <label>:&nbsp;</label><label id="location" class="form-label"></label>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                <label class="form-label">Latitude</label>
              </div>
            </div>
            <div class="col-md-9">
              <div class="form-group">
                <label>:&nbsp;</label><label id="latitude" class="form-label"></label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                <label class="form-label">Longitude</label>
              </div>
            </div>
            <div class="col-md-9">
              <div class="form-group">
                <label>:&nbsp;</label><label id="longitude" class="form-label"></label>
              </div>
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
    $("#farmer_name").html(result.name);
    $("#latitude").html(result.latitude);
    $("#longitude").html(result.longitude);
    $("#id_number").html(result.id_number);
    $("#location").html(result.location);

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
