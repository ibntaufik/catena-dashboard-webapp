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
    <li class="breadcrumb-item active" aria-current="page">Account</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <h6 class="card-title">Create Account</h6>
          <div class="row">
            
            <input type="text" name="username_fake" id="username_fake" autocomplete="off" style="display:none;">
            <input type="password" name="password_fake" autocomplete="off" style="display:none;">

            <div class="col-sm-3">
              <div class="mb-3">
                <label for="name" class="form-label">ID</label>
                <input type="text" class="form-control" id="user_id" maxlength="255" autocomplete="off" placeholder="ID" onkeypress="return isAlphaNumericDash(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" maxlength="255" autocomplete="off" placeholder="Name" onkeypress="return isAlphaNumericAndWhiteSpace(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email_user" maxlength="255" autocomplete="off" placeholder="me@mail.co">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" maxlength="255" autocomplete="new-password" placeholder="Password" onkeypress="return isAlphaNumeric(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" maxlength="255" placeholder="Phone" onkeypress="return isNumber(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="status_account" class="form-label">Status</label>
                <select id="status_account" class="form-control" name="status_account">
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
        <h6 class="card-title">List of User Account</h6>
        <div class="table-responsive">
          <table id="gridDataTable" class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
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
    var statusAccount = {!! json_encode($statusAccount) !!};

  $(document).ready(function() {
      console.log(statusAccount);
      $("#response_message").hide();

      $("#role").select2().on("change", function(e){
        if($("#role option:selected").val() == "vcp"){
          $("#selection-vcp").show();
        } else {
          $("#selection-vcp").hide();
        }
      });

      $("#status_account").select2({
            width: '100%',
            data: statusAccount
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
            "url": "{{ route('accounts.grid-list') }}",
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
            { "targets": 0, "data": "code" },
            { "targets": 1, "data": "name" },
            { "targets": 2, "data": "email" },
            { "targets": 3, "data": "phone" },
            { "targets": 4, "data": "status" },
            { "targets": 5, "data": function(data, type, row, meta){
                  return '<a href="#" onclick=$(this).delete("'+data.code+'") style="cursor: pointer;"><i data-feather="trash-2"></i>';
              }
            }
          ],
          "drawCallback": function(settings) {
              feather.replace(); // Initialize Feather icons
          }
      });

      $.fn.delete = function(code) {
        $.ajax({
            type: "POST",
            url: "{{ route('accounts.remove') }}",
            data: {
              _token: "{{ csrf_token() }}",
              code: code,
            },
            dataType: "json",
            timeout: 300000
        }).done(function(data){
            $('#gridDataTable').DataTable().ajax.reload();
        }).fail(function(data){
            
        });
      };
  });

  function submit()
  {
      $("#email_user, #username, #password").attr('style', '');
      $("#response_message").attr("style", 'display: none;');
      $(".submit-button").addClass("disabled");
      $(".spinner-border").attr("style", '');

      var pass = true;

      $("#email_user, #name, #password").each(function(){
          $(this).attr('style', '');
          if($(this).val() == ''){
            $(this).attr('style', 'border: 1px solid #d57171 !important');
            pass = false;
          }
      });

      if(!validateEmail($("#email_user").val())){
        $('#response_message').removeClass('alert-success');
        $('#response_message').addClass('alert-danger');
        $("#response_message").attr("style", '');
        $("#success").html("Email is not valid");
        $('#response_message').fadeTo(3000, 500).slideUp(500, function() {
          $("#success-alert").slideUp(500);
        });
        pass = false;
      }

      if(pass){
        var submitData = {
            _token: "{{ csrf_token() }}",
            user_id: $('#user_id').val(),
            name: $('#name').val(),
            phone: $('#phone').val(),
            status_account: $('#status_account').val(),
            email: $('#email_user').val(),
            password: $('#password').val()
        };

        $.ajax({
            type: "POST",
            url: "{{ route('accounts.submit') }}",
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
    $("#email_user, #password, #phone, #user_id, #name").val('');
    $('#status_account').val('select').select2();
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