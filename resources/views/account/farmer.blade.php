@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    <li class="breadcrumb-item active" aria-current="page">Farmer Account</li>
  </ol>
</nav>

<div class="accordion" id="accordionFilter">
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingTwo">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
        Filter
      </button>
    </h2>
    <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <div class="card">
          <div class="card-body">
              <div class="row">

                <div class="col-sm-3">
                  <div class="mb-3">
                    <label for="name" class="form-label">Farmer Name</label>
                    <input type="text" class="form-control" id="f_name" maxlength="255" placeholder="Farmer Name" onkeypress="return isAlphaNumericAndWhiteSpace(event);">
                  </div>
                </div>
                <div class="col-sm-3" style="display: none;">
                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="f_email_user" maxlength="255" autocomplete="off" placeholder="me@mail.co">
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="f_phone" maxlength="15" autocomplete="off" placeholder="08123456789">
                  </div>
                </div>

                <div class="col-sm-3">
                  <div class="mb-3">
                    <label for="id_number" class="form-label">Identity Number</label>
                    <input type="text" class="form-control" id="f_id_number" maxlength="255" placeholder="(SIM, KK, or KTP))" onkeypress="return isNumber(event);">
                  </div>
                </div>
                @include("layout.coverage-filter")
              </div>
          </div>
          <div class="card-footer">
              <div class="row">
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
  </div>
</div>

<div class="accordion pt-3" id="accordionNewFarmer">
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingOne">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        New Farmer
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <div class="card">
          <div class="card-body">
              <div class="row">

                <div class="col-sm-3">
                  <div class="mb-3">
                    <label for="name" class="form-label">Farmer Name</label>
                    <input type="text" class="form-control" id="name" maxlength="255" placeholder="Farmer Name" onkeypress="return isAlphaNumericAndWhiteSpace(event);">
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
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" maxlength="15" autocomplete="off" placeholder="08123456789">
                  </div>
                </div>
                <div class="col-sm-3" style="display: none;">
                  <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" maxlength="255" autocomplete="new-password" placeholder="Password" onkeypress="return isAlphaNumeric(event);">
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="mb-3">
                    <label for="id_number" class="form-label">Identity Number</label>
                    <input type="text" class="form-control" id="id_number" maxlength="255" placeholder="(SIM, KK, or KTP))" onkeypress="return isNumber(event);">
                  </div>
                </div>
                @include("layout.coverage")
                <div class="col-sm-6">
                  <div class="mb-3">
                    <label for="city" class="form-label">Address</label>
                    <textarea type="text" class="form-control" id="address" rows="3" placeholder="Address" onkeypress="return validateAddress(event);"></textarea>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="mb-3">
                    <div class="row col-sm-12">
                      <div class="col-md-6">
                        <div class="form-group" style="height: 40%;">
                            <label class="mandatory">Upload ID Photo</label>
                            <input type="file" id="id_file" name="id_file" style="opacity: 1; position: relative; height: 30px;" onchange="getBase64(this.id)">
                        </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <img id="img-preview-id_file" style="max-width: 100%;height: auto;">
                              <input type="hidden" id="img-base64-id_file">
                          </div>
                      </div>
                    </div>
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
  </div>
</div>

<div class="row pt-3">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">List of Farmer Account</h6>
        <div class="table-responsive">
          <table id="gridDataTable" class="table">
            <thead>
              <tr>
                <th>Name</th>
                <th>ID Number</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Sub District Code</th>
                <th>Latitude<br>Longitude</th>
                <th></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 900px; max-width: 1200px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Picture</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
      </div>
      <div class="modal-body">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-6">
              <img id="id_number_image" style="max-width: 80%;">
            </div>
            <div class="col-md-6">
              <img id="photo_image" style="max-width: 80%;">
            </div>
          </div>
        </div>
      </div> 

      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
@endsection

@section('javascript')
<script src="{{ asset('assets/js/coverage.js') }}"></script>
<script type="text/javascript">
  var start = 0;
  var limit = 10;
  var fileType = "";

  // add by faisal
  // used for coverage.js file
  var province = {!! json_encode($province) !!};
  var url_coverage_city = "{{ route('coverage.city') }}";
  var url_coverage_district = "{{ route('coverage.district') }}";
  var url_coverage_sub_district = "{{ route('coverage.sub_district') }}";

  $(document).ready(function() {
      
      $("#response_message").attr("style", 'display: none;');

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
            "url": "{{ route('farmer.grid-list') }}",
            "data": function ( d ) {
              var info = $('#gridDataTable').DataTable().page.info();
              d.start = info.start;
              d.limit = limit;
              d.eager = false;
              d.name = $("#f_name").val();
              d.email_user = $("#f_email_user").val();
              d.phone = $("#f_phone").val();
              d.id_number = $("#f_id_number").val();
              d.latitude = $("#f_latitude").val();
              d.longitude = $("#f_longitude").val();
              d.province_id = $("#f_province").val();
              d.city_id = $("#f_city").val();
              d.district_id = $("#f_district").val();
              d.sub_district_id = $("#f_sub_district").val();
            },
            "dataSrc": function(json){
              
              json.recordsTotal = json.count;
              json.recordsFiltered = json.count;
              
              return json.data;
            }
          },
                                
          "columnDefs" : [
            { "targets": 0, "data": function( data, type, row, meta ){
                return '<a href="javascript:void(0)" onclick=showDetail("'+data.image_id_number_name+'||'+data.image_photo_name+'")><label style="cursor: pointer;";>'+ucFirstWord(data.name)+'</label></a>';
              }
            },
            { "targets": 1, "data": function( data, type, row, meta ){
                return '<a onclick=$(this).detail("'+data.id_number+'","'+data.farmer_code+'") class="form-label" style="cursor: pointer;">'+data.id_number+'</a>';
              }
            },
            { "targets": 2, "data":  function(data, type, row, meta){
                  return data.phone ? data.phone : "-";
              }
            },
            { "targets": 3, "data":  function(data, type, row, meta){
                  return data.address+"<br>"+data.location;
              }
            },
            { "targets": 4, "data": "sub_district_code" },
            { "targets": 5, "data":  function(data, type, row, meta){
                  return data.latitude && data.longitude ? data.latitude+"<br>"+data.longitude : "-<br>-";
              }
            },
            { "targets": 6, "data": function(data, type, row, meta){
                  return '<a href="javascript:void(0)" onclick="confirmDelete('+data.id_number+')" style="cursor: pointer;"><i data-feather="trash-2"></i></a>';
//'<a href="#" onclick=$(this).delete("'+data.id_number+'") style="cursor: pointer;"><i data-feather="trash-2"></i>';
              }
            },
          ],
          "drawCallback": function(settings) {
              feather.replace(); // Initialize Feather icons
          }
      });

      $.fn.delete = function(idNumber) {
        $.ajax({
            type: "POST",
            url: "{{ route('farmer.remove') }}",
            data: {
              _token: "{{ csrf_token() }}",
              id_number: idNumber,
            },
            dataType: "json",
            timeout: 300000
        }).done(function(response){
            $('#gridDataTable').DataTable().ajax.reload();
        }).fail(function(response){
            
        });
      }; 

      $('#id_btn_filter').on('click',function(e) {
          e.stopImmediatePropagation();
          $('#gridDataTable').DataTable().ajax.reload();
      });

      $('#id_btn_clear').on('click',function(e) {
          $("#f_name, #f_email_user, #f_phone, #f_id_number").val("");
          resetFilter();
          e.stopImmediatePropagation();
          $('#gridDataTable').DataTable().ajax.reload();
      });

      $.fn.detail = function(id, code) {
        window.open(('{{ route('farmer.detail') }}?id=' + id +'&code=' + code), '_blank' );
      };
  });
  
  function validateInput(input){
      var re = /^[a-zA-Z0-9.,\s\/-]*$/;
      return re.test(input);
  }

  function submit(){
      $("#email_user, #phone, #name, #address, #latitude, #longitude").attr('style', '');
      $("#response_message").attr("style", 'display: none;');
      $(".submit-button").addClass("disabled");
      $(".spinner-border").attr("style", '');

      var pass = true;

      $("#name, #address, #latitude, #longitude, #name, #id_number").each(function(){
          $(this).attr('style', '');
          if($(this).val() == ''){
            $(this).attr('style', 'border: 1px solid #d57171 !important');
            pass = false;
            return;
          }
      });

      if(pass && ($("#email_user").val() !== '') && ($("#email_user").val() != null) && !validateEmail($("#email_user").val())){
        $('#response_message').removeClass('alert-success');
        $('#response_message').addClass('alert-danger');
        $("#response_message").attr("style", '');
        $("#success").html("Email is not valid");
        $('#response_message').fadeTo(3000, 500).slideUp(500, function() {
          $("#success-alert").slideUp(500);
        });
        pass = false;
        return;
      }

      if(pass){
        var submitData = {
            _token: "{{ csrf_token() }}",
            name: $('#name').val(),
            email: $('#email_user').val(),
            phone: $('#phone').val(),
            password: $('#password').val(),
            address: $('#address').val(),
            latitude: $('#latitude').val(),
            longitude: $('#longitude').val(),
            id_number: $('#id_number').val(),
            sub_district_id: $('#sub_district').val(),
            file: $('#img-base64-id_file').val(),
            file_type: fileType
        };

        $.ajax({
            type: "POST",
            url: "{{ route('farmer.submit') }}",
            data: submitData,
            dataType: "json",
            timeout: 300000
        }).done(function(response){
            if(response.code == 200){
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
            $("#success").html(response.message);
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
      $("#name, #address, #latitude, #longitude, #name, #id_number, #phone").val('');
      
      $("#img-preview-id_file").attr({'src':''});
      $('#img-base64-id_file').attr({'value':''});
      $('#id_file').val('');

      // add by faisal
      // used to reset coverage combo from coverage.js file
      $('#city, #district, #sub_district').empty();
      $('#city, #district, #sub_district').select2({ width: '100%', data: comboDefault });
      $('#province, #city, #district, #sub_district').val('select').select2();
  }

  function resetFilter(){
      $("#f_name, #f_latitude, #f_longitude").val('');
      // add by faisal
      // used to reset coverage combo from coverage.js file
      $('#f_city, #f_district, #f_sub_district').empty();
      $('#f_city, #f_district, #f_sub_district').select2({ width: '100%', data: comboDefault });
      $('#f_province, #f_city, #f_district, #f_sub_district').val('select').select2();
  }

  function getBase64(file) {
      var files = document.getElementById(file).files;
      previewImg = document.getElementById("img-preview-"+file);
      base64 = document.getElementById("img-base64-"+file);

      if (files[0].type != 'image/jpeg' && files[0].type != 'image/png'){
          previewImg.removeAttribute("src");
          base64.removeAttribute("value");
          $('#response_message').removeClass('alert-success');
          $('#response_message').addClass('alert-danger');  
          $("#success").html("File type must be JPEG or PNG");
          return false;

      } else if (files[0].size > 1000000){
          previewImg.removeAttribute("src");
          base64.removeAttribute("value");
          $('#response_message').removeClass('alert-success');
          $('#response_message').addClass('alert-danger');  
          $("#success").html("File size max. 1MB");
          return false;
      }

      if (files.length > 0 && (files[0].type == 'image/jpeg' || files[0].type == 'image/png')) {
          var reader = new FileReader();
          reader.readAsDataURL(files[0]);
          reader.onload = function () {
            previewImg.setAttribute("src",reader.result);
            base64.setAttribute("value",reader.result);
          };
          reader.onerror = function (error) {
              showErrorMessage(error);
              return false;
          };
          if(files[0].type == 'image/jpeg'){
            fileType = ".jpeg";
          }else if(files[0].type == 'image/png'){
            fileType = ".png";
          }
      }
  }

  function showDetail(image_names){
    var imageNames = image_names.split("||");
    $("#id_number_image").attr("src", "{{ url('/storage/farmer/id-number/') }}/"+imageNames[0]);
    $("#photo_image").attr("src", "{{ url('/storage/farmer/id-number/') }}/"+imageNames[1]);
    
    $('#previewModal').modal('show');
  }

  function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You can't undo this action!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            deleteRecord(id);
        }
    });

    function deleteRecord(idNumber){
      $.ajax({
        type: "POST",
        url: "{{ route('farmer.remove') }}",
        data: {
          _token: "{{ csrf_token() }}",
          id_number: idNumber,
        },
        dataType: "json",
        timeout: 300000
      }).done(function(response){
          $('#gridDataTable').DataTable().ajax.reload();
      }).fail(function(response){
          
      });
    }
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
