@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<style type="text/css">
  .width-100{
    width: 100%;
  }
  .img-thumbnail {
    border-radius: 8px;
    object-fit: cover;
  }

</style>
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Locality</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="card h-auto">
          <div class="card-body">
            <h6 class="card-title">Info</h6>
            <div class="row">
              <div class="col-md-12">
                <input type="hidden" name="locality_id" id="locality_id">
                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="category" class="form-label mb-0">Status</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <select id="locality_status" class="form-control" name="locality_status">
                        <option value="select" disabled selected>-- Select --</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label mb-0">Keterangan Status</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <textarea id="status_description" class="form-control width-100" rows="2"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="province" class="form-label mb-0">Provinsi</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <select id="province" class="form-control" name="province">
                        <option value="select" disabled selected>-- Select --</option>
                      </select>
                    </div>
                  </div>
                </div>
                
                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="city" class="form-label mb-0">Kota/Kab</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <select id="city" class="form-control" name="city">
                        <option value="select" disabled selected>-- Select --</option>
                      </select>
                    </div>
                  </div>
                </div>
                
                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="district" class="form-label mb-0">Kecamatan</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <select id="district" class="form-control" name="district">
                        <option value="select" disabled selected>-- Select --</option>
                      </select>
                    </div>
                  </div>
                </div>
                
                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="sub_district" class="form-label mb-0">Kelurahan</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <select id="sub_district" class="form-control" name="sub_district">
                        <option value="select" disabled selected>-- Select --</option>
                      </select>
                    </div>
                  </div>
                </div>
                
                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="latitude" class="form-label mb-0">Latitude</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <input type="text" class="form-control" id="latitude" maxlength="255" placeholder="Latitude" onkeypress="return latitude(event);">
                    </div>
                  </div>
                </div>
                
                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="longitude" class="form-label mb-0">Longitude</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <input type="text" class="form-control" id="longitude" maxlength="255" placeholder="Longitude" onkeypress="return isNumericAndDot(event);">
                    </div>
                  </div>
                </div>

                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label mb-0">Verifikasi Ketidakadaan Kopi</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <textarea id="field_verification" class="form-control width-100" rows="3"></textarea>
                    </div>
                  </div>
                </div>

                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label mb-0">Keterangan</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <textarea id="additional_information" class="form-control width-100" rows="3"></textarea>
                    </div>
                  </div>
                </div>

                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <label class="form-label mb-0">Foto</label>
                  </div>
                  <div class="col-md-6">
                    @if(isset($localityAsset))
                      @foreach($localityAsset as $asset)
                        <a href="{{ $asset->image_url ?? '#' }}" target="_blank">
                          <img src="{{ $asset->image_url ?? '#' }}"
                               alt="Locality Info"
                               class="img-thumbnail img-fluid"
                               style="max-height: 400px; object-fit: contain;">
                        </a>
                      @endforeach
                    @else
                      <p class="text-muted">No asset available</p>
                    @endif
                  </div>
                </div>
              </div>
            </div> 
          </div>

          <div class="card-footer">
            <div class="col-md-12 text-end mb-3">
              <button id="btnSaveFarmer" class="btn btn-primary" onclick="confirmUpdate()">Simpan</button>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('javascript')
<script src="{{ asset('assets/js/coverage.js') }}"></script>
<script src="{{ asset('assets/js/format-currency.js') }}"></script>
<script src="{{ asset('assets/js/validation-regex.js') }}"></script>

<script type="text/javascript">
  var result = {!! json_encode($result) !!};console.log(result);
  var province = {!! json_encode($province) !!};
  var localityStatus = {!! json_encode($localityStatus) !!};
  var url_coverage_city = "{{ route('coverage.city') }}";
  var url_coverage_district = "{{ route('coverage.district') }}";
  var url_coverage_sub_district = "{{ route('coverage.sub_district') }}";

  $(document).ready(function() {
    // Fill basic input fields
    $("#locality_id").val(result?.locality_id);
    $("#field_verification").val(result?.field_verification || '');
    $("#additional_information").val(result?.additional_information || '');
    $("#latitude").val(result?.latitude || '');
    $("#longitude").val(result?.longitude || '');
    $("#status_description").val(result?.status_description || '');
    $('#locality_status').select2({ width: '100%', data: localityStatus });
    $('#locality_status').val(result.locality_status).trigger('change');

    // Initialize all select2 dropdowns
    $('#province, #city, #district, #sub_district').select2({ width: '100%' });

    // Load initial province list
    $('#province').select2({ width: '100%', data: province });

    // Run the preload after select2 setup
    preloadLocation();

    // --- Helper to call AJAX with CSRF ---
    async function fetchData(url, params) {
      return $.ajax({
        type: "GET",
        url,
        data: Object.assign(params, { _token: "{{ csrf_token() }}" }),
        dataType: "json",
        timeout: 300000,
      });
    }

    // --- Auto-select location chain ---
    async function preloadLocation() {
      if (!result || !result.province_id) return;

      try {
        // Province
        $('#province').val(result.province_id).trigger('change');

        // City
        const cityRes = await fetchData(url_coverage_city, { province_id: result.province_id });
        $('#city').empty().select2({ width: '100%', data: cityRes.data });
        if (result.city_id) {
          $('#city').val(result.city_id).trigger('change');
        }

        // District
        const districtRes = await fetchData(url_coverage_district, { city_id: result.city_id });
        $('#district').empty().select2({ width: '100%', data: districtRes.data });
        if (result.district_id) {
          $('#district').val(result.district_id).trigger('change');
        }

        // Sub-district
        const subRes = await fetchData(url_coverage_sub_district, { district_id: result.district_id });
        $('#sub_district').empty().select2({ width: '100%', data: subRes.data });
        if (result.sub_district_id) {
          $('#sub_district').val(result.sub_district_id).trigger('change');
        }

      } catch (error) {
        console.error("Failed to preload location:", error);
      }
    }

    // --- UI adjustment (optional) ---
    (function($, document) {
      let height = -1;
      $('[data-tabs]').css('min-height', height + 40 + 'px');
    }(jQuery, document));
  });

  function confirmUpdate(){
    Swal.fire({
        title: 'Konfirmasi perubahan data',
        text: "Pastikan data sudah sesuai.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya',
        reverseButtons: true,
    }).then((result) => {
        if(result.isConfirmed){
          doUpdate();
        }
    });
  }

  function doUpdate(){
    
    const data = {
      _token: "{{ csrf_token() }}",
      locality_id: result.locality_id,
      field_verification: $("#field_verification").val(),
      additional_information: $("#additional_information").val(),
      latitude: $("#latitude").val(),
      longitude: $("#longitude").val(),
      sub_district_id: $('#sub_district').val(),
      status_description: $('#status_description').val(),
      locality_status: $('#locality_status').val(),
    };

    $.ajax({
      type: "POST",
      url: "{{ route('locality.update') }}",
      data: data,
      dataType: "json",
      timeout: 300000
    }).done(function(response){
        Swal.fire({
          title: "Success",
          text: "Data locality telah diperbaharui",
          icon: "success"
        }).then(() => {
          window.location.reload();
        });
    }).fail(function(response){
       Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Something went wrong!",
        }); 
    });
  }
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
