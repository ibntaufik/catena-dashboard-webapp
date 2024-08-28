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
    <li class="breadcrumb-item active" aria-current="page">VCH Account</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <h6 class="card-title">Create VCH Account</h6>
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
                <label for="account_code" class="form-label">Account</label>
                <select id="account_code" class="form-control" name="account_code">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="vendor_name" class="form-label">Vendor Bank Name</label>
                <select id="bank_id" class="form-control" name="bank_id">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="vendor_name" class="form-label">Vendor Account Number</label>
                <input type="text" class="form-control" id="vendor_bank_account_number" maxlength="255" placeholder="Vendor Account Number" onkeypress="return isNumber(event);">
              </div>
            </div>
            <div class="col-sm-12">
              <div class="mb-3">
                <label for="city" class="form-label">Vendor Bank Address</label>
                <textarea type="text" class="form-control" id="vendor_bank_location" rows="2" placeholder="Address" onkeypress="return validateAddress(event);"></textarea>
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
        <h6 class="card-title">List of VCH Account</h6>
        <div class="table-responsive">
          <table id="gridDataTable" class="table">
            <thead>
              <tr>
                <th>VCH Code</th>
                <th>Email</th>
                <th>Location</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Vendor ID</th>
                <th>Vendor Name</th>
                <th>Vendor Bank Name</th>
                <th>Vendor Bank Location</th>
                <th>Vendor Account Number</th>
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
    var bank = {!! json_encode($bank) !!};
    var account = {!! json_encode($account) !!};
    var vch = {!! json_encode($vch) !!};

  $(document).ready(function() {
      
      $("#response_message").attr("style", 'display: none;');

      $('#bank_id').select2({
            width: '100%',
            data: bank
      });

      $('#account_code').select2({
            width: '100%',
            data: account
      });

      $('#vch_code').select2({
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
            "url": "{{ route('vch-account.grid-list') }}",
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
            { "targets": 0, "data": "vch_code" },
            { "targets": 1, "data": "email" },
            { "targets": 2, "data":  function(data, type, row, meta){
                  return data.address+', '+data.location;
              }
            },
            { "targets": 3, "data": "latitude" },
            { "targets": 4, "data": "longitude" },
            { "targets": 5, "data": "vendor_code" },
            { "targets": 6, "data": "vendor_name" },
            { "targets": 7, "data": "vendor_bank_name" },
            { "targets": 8, "data": "vendor_bank_address" },
            { "targets": 9, "data": "vendor_bank_account_number" },
            { "targets": 10, "data": function(data, type, row, meta){
                  return '<a href="#" onclick=$(this).delete("'+data.vch_code+'|'+data.vendor_code+'") style="cursor: pointer;"><i data-feather="trash-2"></i>';
              }
            },
          ],
          "drawCallback": function(settings) {
              feather.replace(); // Initialize Feather icons
          }
      });

      $.fn.delete = function(concatCode) {

        codes = concatCode.split("|");
        $.ajax({
            type: "POST",
            url: "{{ route('vch-account.remove') }}",
            data: {
              _token: "{{ csrf_token() }}",
              vch_code: codes[0],
              vendor_code: codes[1],
            },
            dataType: "json",
            timeout: 300000
        }).done(function(response){
            $('#gridDataTable').DataTable().ajax.reload();
        }).fail(function(response){
            
        });
      };
  });
  
  function validateInput(input){
      var re = /^[a-zA-Z0-9.,\s\/-]*$/;
      return re.test(input);
  }

  function submit()
  {
      $("#vendor_bank_location, #vendor_bank_account_number").attr('style', '');
      $("#response_message").attr("style", 'display: none;');
      $(".submit-button").addClass("disabled");
      $(".spinner-border").attr("style", '');

      var pass = true;

      $("#vendor_bank_location, #vendor_bank_account_number").each(function(){
          $(this).attr('style', '');
          if($(this).val() == ''){
            $(this).attr('style', 'border: 1px solid #d57171 !important');
            pass = false;
          }
      });

      if(pass){
        var submitData = {
            _token: "{{ csrf_token() }}",
            vch_code: $('#vch_code').val(),
            account_code: $('#account_code').val(),
            bank_code: $('#bank_id').val(),
            vendor_bank_account_number: $('#vendor_bank_account_number').val(),
            vendor_bank_address: $('#vendor_bank_location').val(),
        };

        $.ajax({
            type: "POST",
            url: "{{ route('vch-account.submit') }}",
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
      $("#vendor_bank_account_number, #vendor_bank_location").val('');
      $("#vch_code, #account_code, #bank_id").val('select').select2();
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
