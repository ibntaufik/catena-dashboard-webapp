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
    <li class="breadcrumb-item active" aria-current="page">HO Approval</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
          <h6 class="card-title">Create New HO Approval</h6>
          <div class="row">
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="user" class="form-label">User name</label>
                <select id="user" class="form-control" name="user"></select>
              </div>
            </div>
            <div class="col-sm-9">
            </div>
          </div>
      </div>
      <div class="card-footer">
          <div class="row">
            <div class="col-sm-3">
              <div id="response_message" class="alert alert-danger alert-dismissible fade show mt-10" role="alert" style="">
                  <strong id="success">Message</strong>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            </div>
            <div class="col-sm-6">
              
            </div>
            <div class="col-sm-3">
              <button type="submit" class="btn btn-primary submit-button" style="" onclick="submit();">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>Submit
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
          <table id="dataGrid" class="table">
            <thead>
              <tr>
                <th>User Name</th>
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
    var candidate = {!! json_encode($candidate) !!}
  $(document).ready(function() {
      
      $("#response_message").attr("style", 'display: none;');

      $('#user').select2({
            width: '100%',
            data: candidate
      });

      $('#dataGrid').DataTable( {
          'paging'        : true,
          'lengthChange'  : false,
          'ordering'      : false,
          'info'          : true,
          'autoWidth'     : false,
          "processing"    : true,
          "searching"     : true,
          "pageLength"    : limit,
          "ajax": {
            "url": "{{ route('approval.grid-list') }}",
            "data": function ( d ) {
              var info = $('#dataGrid').DataTable().page.info();
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
            { "targets": 0, "data": "name" },
            { "targets": 1, "data": function(data, type, row, meta){
                  return '<a href="#" onclick=$(this).deleteUser("'+data.id+'") style="cursor: pointer;"><i data-feather="trash-2"></i>';
              }
            },
          ],
          "drawCallback": function(settings) {
              feather.replace(); // Initialize Feather icons
          }
      });

      $.fn.deleteUser = function(id) {
        $.ajax({
            type: "POST",
            url: "{{ route('approval.remove') }}",
            data: {
              _token: "{{ csrf_token() }}",
              user_id: id,
            },
            dataType: "json",
            timeout: 300000
        }).done(function(response){
            $('#dataGrid').DataTable().ajax.reload();
            $('#user').empty();
            $('#user').select2({data: response.data});
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
      $("#user").attr('style', '');
      $("#response_message").attr("style", 'display: none;');
      $(".submit-button").addClass("disabled");
      $(".spinner-border").attr("style", '');

      var pass = true;

      $("#user").each(function(){
          $(this).attr('style', '');
          if($(this).val() == 'select'){
            $('#response_message').removeClass('alert-success');
            $('#response_message').addClass('alert-danger');
            $("#response_message").attr("style", '');
            $("#success").html("Silakan pilih user terlebih dahulu.");
            pass = false;
          }
      });

      if(pass){
        var submitData = {
            _token: "{{ csrf_token() }}",
            user_id: $('#user').val(),
        };

        $.ajax({
            type: "POST",
            url: "{{ route('approval.submit') }}",
            data: submitData,
            dataType: "json",
            timeout: 300000
        }).done(function(data){
            if(data.code == 200){
              $('#response_message').removeClass('alert-danger');
              $('#response_message').addClass('alert-success');
            } else {
              $('#response_message').removeClass('alert-success');
              $('#response_message').addClass('alert-danger');
            }
            $("#response_message").attr("style", '');
            $("#success").html(data.message);
            $('#response_message').fadeTo(3000, 500).slideUp(500, function() {
              $("#success-alert").slideUp(500);
            });
            // refresh grid after submit
            $('#dataGrid').DataTable().ajax.reload();

            // change combo select2 after submit
            $("#user option[value='"+$('#user').val()+"']").remove();
            $("#user").val('select').select2();
        }).fail(function(data){
            $('#response_message').removeClass('alert-success');
            $('#response_message').addClass('alert-danger');
            $("#success").html("Koneksi ke server terkendala. Silakan coba lagi.");
        });
      }

      $(".submit-button").removeClass("disabled");
      $(".spinner-border").attr("style", "display: none;");
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
