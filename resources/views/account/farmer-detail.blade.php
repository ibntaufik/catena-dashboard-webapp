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
    <li class="breadcrumb-item"><a href="#">Farmer</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="card h-auto">
          <div class="card-body">
            <h6 class="card-title">Info Farmer</h6>
            <div class="row">
              <div class="col-md-6">

                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="category" class="form-label mb-0">Status</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <select id="supplier_status" class="form-control" name="supplier_status">
                        <option value="select" disabled selected>-- Select --</option>
                      </select>
                    </div>
                  </div>
                </div>
                
                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label mb-0">Farmer ID</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <input type="text" id="farmer_code" class="form-control width-100" disabled></input>
                    </div>
                  </div>
                </div>
                
                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label mb-0">Nama</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <input type="text" id="farmer_name" class="form-control width-100"></input>
                    </div>
                  </div>
                </div>
                
                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label mb-0">Alias</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <input type="text" id="alias" class="form-control width-100"></input>
                    </div>
                  </div>
                </div>

                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label mb-0">NIK</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <input type="text" id="id_number" class="form-control width-100"></input>
                    </div>
                  </div>
                </div>

                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label mb-0">Telp.</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <input type="text" id="phone" class="form-control width-100"></input>
                    </div>
                  </div>
                </div>

                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label mb-0">Email</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <input type="text" id="email" class="form-control width-100"></input>
                    </div>
                  </div>
                </div>

                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label mb-0">Npwp</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <input type="text" id="npwp" class="form-control width-100"></input>
                    </div>
                  </div>
                </div>

                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label mb-0">Unit Usaha</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <input type="text" id="business_name" class="form-control width-100"></input>
                    </div>
                  </div>
                </div>

                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="category" class="form-label mb-0">Jenis Usaha</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <select id="business_type" class="form-control" name="business_type">
                        <option value="select" disabled selected>-- Select --</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="category" class="form-label mb-0">Kategori</label>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="form-group">
                      <select id="category" class="form-control" name="category">
                        <option value="select" disabled selected>-- Select --</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <label class="form-label mb-0">Foto Identitas</label>
                  </div>
                  <div class="col-md-6">
                    @if(isset($supplierAsset))
                      @foreach($supplierAsset as $asset)
                        @if($asset->asset_type === 'identity')
                          <a href="{{ $asset->identity_image ?? '#' }}" target="_blank">
                            <img src="{{ $asset->identity_image ?? '#' }}"
                                 alt="Bank Info"
                                 class="img-thumbnail img-fluid"
                                 style="max-height: 400px; object-fit: contain;">
                          </a>
                        @endif
                      @endforeach
                    @else
                      <p class="text-muted">No asset available</p>
                    @endif
                  </div>
                </div>
              </div>
            
              <div class="col-md-6">
                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="province" class="form-label mb-0">Provinsi</label>
                    </div>
                  </div>
                  <div class="col-md-8">
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
                  <div class="col-md-8">
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
                  <div class="col-md-8">
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
                  <div class="col-md-8">
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
                  <div class="col-md-8">
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
                  <div class="col-md-8">
                    <div class="form-group">
                      <input type="text" class="form-control" id="longitude" maxlength="255" placeholder="Longitude" onkeypress="return isNumericAndDot(event);">
                    </div>
                  </div>
                </div>

                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label mb-0">Address</label>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <div class="form-group">
                      <textarea id="address" class="form-control width-100" rows="3"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <label class="form-label mb-0">Foto Unit Usaha</label>
                  </div>
                  <div class="col-md-6">
                    @if(isset($supplierAsset))
                      @foreach($supplierAsset as $asset)
                        @if($asset->asset_type === 'business_unit')
                          <a href="{{ $asset->business_unit_image ?? '#' }}" target="_blank">
                            <img src="{{ $asset->business_unit_image ?? '#' }}"
                                 alt="Bank Info"
                                 class="img-thumbnail img-fluid"
                                 style="max-height: 400px; object-fit: contain;">
                          </a>
                        @endif
                      @endforeach
                    @else
                      <p class="text-muted">No asset available</p>
                    @endif
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                
              </div>
              <div class="col-md-6">
                
              </div>
            </div>  
          </div>
        </div>
      
        <div class="card">
          <div class="card-body">
            <h6 class="card-title">Info Bank</h6>
            <div class="row">
              <div class="col-md-12">
                
                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label mb-0">Bank</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <select id="bank" class="form-control" name="bank">
                        <option value="select" disabled selected>-- Select --</option>
                      </select>
                    </div>
                  </div>
                </div>
                
                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label mb-0">Nama Pemilik Rekening</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input type="text" id="account_name" class="form-control width-100"></input>
                    </div>
                  </div>
                </div>
                
                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-label mb-0">No. Rekening</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input id="account_number" class="form-control width-100"></input>
                    </div>
                  </div>
                </div>

                <div class="row mt-2 align-items-center">
                  <div class="col-md-3">
                    <label class="form-label mb-0">Foto Buku Rekening</label>
                  </div>
                  <div class="col-md-6">
                    @if(isset($supplierAsset))
                      @foreach($supplierAsset as $asset)
                        @if($asset->asset_type === 'bank_info')
                          <a href="{{ $asset->bank_info_image ?? '#' }}" target="_blank">
                            <img src="{{ $asset->bank_info_image ?? '#' }}"
                                 alt="Bank Info"
                                 class="img-thumbnail img-fluid"
                                 style="max-height: 400px; object-fit: contain;">
                          </a>
                        @endif
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
              <button id="btnSaveFarmer" class="btn btn-primary" onclick="confirm('farmer')">Simpan</button>
            </div>
          </div>
        </div>
      </div>
    
      <div class="col-md-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <h6 class="card-title">Info Kebun</h6>
            <div class="row">
              <div class="col-md-12">
                <!-- Info Kebun Section (Accordion) -->
                <div class="accordion mt-4" id="farmAccordion">
                  @foreach($farms as $index => $farm)
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="heading{{ $index }}">
                        <button
                          class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}"
                          type="button"
                          data-bs-toggle="collapse"
                          data-bs-target="#collapse{{ $index }}"
                          aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                          aria-controls="collapse{{ $index }}">
                          {{ $farm->farm_name ?? 'Kebun ' . ($index + 1) }}
                        </button>
                      </h2>

                      <div
                        id="collapse{{ $index }}"
                        class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                        aria-labelledby="heading{{ $index }}"
                        data-bs-parent="#farmAccordion">
                        <div class="accordion-body">
                          <div class="row mb-3">
                            <div class="col-md-4">
                              <label class="form-label">Luas Lahan</label>
                              <input type="text" class="form-control" name="land_measurement[{{ $index }}]" value="{{ $farm->land_measurement_display ?? $farm->land_measurement }}">
                            </div>

                            <div class="col-md-4">
                              <label class="form-label">Jumlah/Populasi Pohon</label>
                              <input type="text" class="form-control" name="tree_population[{{ $index }}]" value="{{ $farm->tree_population }}">
                            </div>

                            <div class="col-md-4">
                              <label class="form-label">Status Lahan</label>
                              <select name="land_status[{{ $index }}]" class="form-control select2-land-status" data-index="{{ $index }}">
                                @foreach($landStatus as $option)
                                  <option value="{{ $option['id'] }}" {{ $option['id'] == $farm->land_status_id ? 'selected' : '' }}>
                                    {{ $option['text'] ?? $option['name'] }}
                                  </option>
                                @endforeach
                              </select>
                            </div>
                          </div>

                          <div class="row mb-3">
                            <div class="col-md-4">
                              <label class="form-label">Latitude</label>
                              <input type="text" class="form-control" id="farm_latitude[{{ $index }}]" name="farm_latitude[{{ $index }}]" value="{{ $farm->farm_latitude }}">
                            </div>

                            <div class="col-md-4">
                              <label class="form-label">Longitude</label>
                              <input type="text" class="form-control" id="farm_longitude[{{ $index }}]" name="farm_longitude[{{ $index }}]" value="{{ $farm->farm_longitude }}">
                            </div>

                            <div class="col-md-4">
                              <label class="form-label">Ketinggian</label>
                              <input type="text" class="form-control" id="farm_altitude[{{ $index }}]" name="farm_altitude[{{ $index }}]" value="{{ $farm->farm_altitude }}">
                            </div>
                          </div>

                          <div class="row mb-3">
                            <div class="col-md-4">
                              <label class="form-label">Kopi</label>
                              <select name="coffee[{{ $index }}]" class="form-control select2-coffee" data-index="{{ $index }}">
                                @foreach($coffee as $option)
                                  <option value="{{ $option['id'] }}" {{ $option['id'] == $farm->coffee_id ? 'selected' : '' }}>
                                    {{ $option['text'] ?? $option['name'] }}
                                  </option>
                                @endforeach
                              </select>
                            </div>

                            <div class="col-md-4">
                              <label class="form-label">Varietas Kopi</label>
                              <select name="coffee_variety[{{ $index }}]" class="form-control select2-coffee-variety" data-index="{{ $index }}">
                                @foreach($coffeeVariety as $option)
                                  <option value="{{ $option['id'] }}" {{ $option['id'] == $farm->coffee_variety_id ? 'selected' : '' }}>
                                    {{ $option['text'] ?? $option['name'] }}
                                  </option>
                                @endforeach
                              </select>
                            </div>

                            <div class="col-md-4">
                              <label class="form-label">Pohon Peneduh</label>
                              <select name="shade_tree[{{ $index }}]" class="form-control select2-shade-tree" data-index="{{ $index }}">
                                @foreach($shadeTree as $option)
                                  <option value="{{ $option['id'] }}" {{ $option['id'] == $farm->shade_tree_id ? 'selected' : '' }}>
                                    {{ $option['text'] ?? $option['name'] }}
                                  </option>
                                @endforeach
                              </select>
                            </div>
                          </div>

                          <div class="row mb-3">
                            <div class="col-md-4">
                              <label class="form-label">No. Sertifikat Lahan</label>
                              <input type="text" class="form-control" id="land_certificate[{{ $index }}]" name="land_certificate[{{ $index }}]" value="{{ $farm->land_certificate }}">
                            </div>
                            <div class="col-md-8">
                              <label for="address" class="form-label mb-0">Alamat</label>
                              <textarea rows="3" type="text" class="form-control" id="address[{{ $index }}]" name="address[{{ $index }}]" maxlength="255" placeholder="Alamat" onkeypress="return isAlphaNumericAndWhiteSpaceAndComma(event);">{{ $farm->address }}</textarea>
                            </div>
                          </div>

                          {{-- Photos --}}
                          @if(!empty($farm->photos) && count($farm->photos) > 0)
                            <div class="mb-3">
                              <h6>Foto Kebun</h6>
                              <div class="d-flex flex-wrap gap-3">
                                @foreach($farm->photos as $photo)
                                  {{-- photos are arrays (['url' => ...]) per your controller --}}
                                  <a href="{{ $photo['url'] ?? '#' }}" target="_blank">
                                    <img src="{{ $photo['url'] ?? '#' }}" class="img-thumbnail" style="max-width: 180px;">
                                  </a>
                                @endforeach
                              </div>
                            </div>
                          @else
                            <p class="text-muted mb-3">Belum ada foto untuk kebun ini.</p>
                          @endif

                          <div class="d-flex justify-content-end gap-2">
                            {{-- per-farm update button (wire to JS) --}}
                            <button type="button" class="btn btn-sm btn-secondary btn-reset-farm" data-index="{{ $index }}">Reset</button>
                            <button type="button" class="btn btn-sm btn-primary btn-save-farm" data-index="{{ $index }}">Perbarui Kebun</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>

              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="col-md-12 text-end mb-3">
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
  var result = {!! json_encode($result) !!};
  var category = {!! json_encode($category) !!};
  var province = {!! json_encode($province) !!};
  var bank = {!! json_encode($bank) !!};
  var coffee = {!! json_encode($coffee) !!};
  var coffeeVariety = {!! json_encode($coffeeVariety) !!};
  var shadeTree = {!! json_encode($shadeTree) !!};
  var landStatus = {!! json_encode($landStatus) !!};
  var businessType = {!! json_encode($businessType) !!};
  var supplierStatus = {!! json_encode($supplierStatus) !!};
  var url_coverage_city = "{{ route('coverage.city') }}";
  var url_coverage_district = "{{ route('coverage.district') }}";
  var url_coverage_sub_district = "{{ route('coverage.sub_district') }}";

  $(document).ready(function() {
    // Fill basic input fields
    $("#farmer_code").val(result?.farmer_code || '');
    $("#farmer_name").val(result?.name || '');
    $("#alias").val(result?.alias || '');
    $("#phone").val(result?.phone || '');
    $("#email").val(result?.email || '');
    $("#npwp").val(result?.npwp || '');
    $("#latitude").val(result?.latitude || '');
    $("#longitude").val(result?.longitude || '');
    $("#id_number").val(result?.id_number || '');
    $("#address").val(result?.address || '');
    $("#business_name").val(result?.business_name || '');
    $("#account_name").val(result?.account_name || '');
    $("#account_number").val(result?.account_number || '');

    // Load initial category list
    $('#category').select2({ width: '100%', data: category });
    $('#category').val(result.supply_categories_id).trigger('change');

    // Load initial bank list
    $('#bank').select2({ width: '100%', data: bank });
    $('#bank').val(result.bank_id).trigger('change');

    $('#supplier_status').select2({ width: '100%', data: supplierStatus });
    $('#supplier_status').val(result.verification_status).trigger('change');

    // Load initial business status list
    $('#business_type').select2({ width: '100%', data: businessType });
    $('#business_type').val(result.business_type_id).trigger('change');

    // Initialize all select2 dropdowns
    $('#province, #city, #district, #sub_district').select2({ width: '100%' });

    // Load initial province list
    $('#province').select2({ width: '100%', data: province });

    // Farm
    $('.select2-coffee').select2({ width: '100%' });
    $('.select2-coffee-variety').select2({ width: '100%' });
    $('.select2-land-status').select2({ width: '100%' });
    $('.select2-shade-tree').select2({ width: '100%' });

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

  function confirm(section){
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
          if (section == "farmer") {
              farmerInfo();
          }
        }
    });
  }

  function farmerInfo(){
    
    const data = {
      _token: "{{ csrf_token() }}",
      farmer_code: $('#farmer_code').val(),
      name: $('#farmer_name').val(),
      alias: $('#alias').val(),
      phone: $('#phone').val(),
      id_number: $('#id_number').val(),
      email: $('#email').val(),
      npwp: $('#npwp').val(),
      address: $('#address').val(),
      business_name: $('#business_name').val(),
      category_id: $('#category').val(),
      business_type_id: $('#business_type').val(),
      sub_district_id: $('#sub_district').val(),
      latitude: $('#latitude').val(),
      longitude: $('#longitude').val(),
      bank_id: $('#bank').val(),
      account_name: $('#account_name').val(),
      account_number: $('#account_number').val(),
      verification_status: $('#supplier_status').val(),
    };

    $.ajax({
      type: "POST",
      url: "{{ route('farmer.update') }}",
      data: data,
      dataType: "json",
      timeout: 300000
    }).done(function(response){
        Swal.fire({
          title: "Success",
          text: "Data farmer telah diperbaharui",
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
