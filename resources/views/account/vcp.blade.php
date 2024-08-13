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
    <li class="breadcrumb-item active" aria-current="page">VCP Account</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <h6 class="card-title">Create VCP Account</h6>
          <div class="row">
            
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="vcp_code" class="form-label">VCP Code</label>
                <select id="vcp_code" class="form-control" name="vcp_code">
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
        <h6 class="card-title">List of VCP Account</h6>
        <div class="table-responsive">
          <table id="gridDataTable" class="table">
            <thead>
              <tr>
                <th>VCP Code</th>
                <th>Email</th>
                <th>Address</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Field Coord. ID</th>
                <th>Field Coord. Name</th>
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
    var account = {!! json_encode($account) !!};
    var vcp = {!! json_encode($vcp) !!};

  $(document).ready(function() {
      
      $("#response_message").attr("style", 'display: none;');

      $('#account_code').select2({
            width: '100%',
            data: account
      });

      $('#vcp_code').select2({
            width: '100%',
            data: vcp
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
            "url": "{{ route('vcp-account.grid-list') }}",
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
            { "targets": 0, "data": "vcp_code" },
            { "targets": 1, "data": "email" },
            { "targets": 2, "data": function(data, type, row, meta){
                  return data.address+', <br>'+data.location;
              }
            },
            { "targets": 3, "data": "latitude" },
            { "targets": 4, "data": "longitude" },
            { "targets": 5, "data": "field_coordinator_id" },
            { "targets": 6, "data": "field_coordinator_name" },
            { "targets": 7, "data": function(data, type, row, meta){
                  return '<a href="#" onclick=$(this).delete("'+data.vcp_code+'") style="cursor: pointer;"><i data-feather="trash-2"></i>';
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
            url: "{{ route('vcp-account.remove') }}",
            data: {
              _token: "{{ csrf_token() }}",
              vcp_code: code,
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
      $("#response_message").attr("style", 'display: none;');
      $(".submit-button").addClass("disabled");
      $(".spinner-border").attr("style", '');

      var pass = true;

      if(pass){
        var submitData = {
            _token: "{{ csrf_token() }}",
            vcp_code: $('#vcp_code').val(),
            account_code: $('#account_code').val()
        };

        $.ajax({
            type: "POST",
            url: "{{ route('vcp-account.submit') }}",
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
            $('#response_message').fadeTo(3000, 500).slideUp(500, function() {
              $("#success-alert").slideUp(500);
            });
            $("#success").html(data.message);
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
      $('#gridDataTable').DataTable().ajax.reload();

      $("#vcp_code, #account_code").val('select').select2();
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
