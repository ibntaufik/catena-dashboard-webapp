@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

  [type=radio]:checked ~ label {
    border-bottom: 2px solid #B91202;
    color: #B91202;
    z-index: 2;
  }

</style>
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Transaction</a></li>
    <li class="breadcrumb-item active" aria-current="page">Approval Purchase Order</li>
  </ol>
</nav>

<div class="col-md-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h6 class="card-title">List of purchase order</h6>

      <div data-tabs class="tabs mt-5" style="display: none;">    
         <div class="col-md-2 tab">
            <input type="radio" name="tabgroup" id="waiting" checked onclick="search('waiting')">
            <label for="waiting">Waiting</label>
         </div>
         <div class="col-md-2 tab">
            <input type="radio" name="tabgroup" id="approved" onclick="search('approved')">
            <label for="approved">Approved</label>
         </div>
         <div class="col-md-2 tab">
            <input type="radio" name="tabgroup" id="rejected" onclick="search('rejected')">
            <label for="rejected">Rejected</label>
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
                          <label class="form-label">Status</label>
                          <input id="farmer_code" class="form-control" name="status"/>
                        </div>
                        <div class="form-group pt-2">
                          <label class="form-label">VCH Code</label>
                          <input id="vcp_code" class="form-control" name="vch_code"/>
                        </div>
                        <div class="form-group pt-2">
                          <label class="form-label">Vendor</label>
                          <input id="transaction_id" class="form-control" name="vendor"/>
                        </div>
                        <div class="form-group pt-2">
                          <label class="form-label">Item Max. Quantity</label>
                          <input id="item_quantity" class="form-control" name="item_quantity"/>
                        </div>
                      </div>

                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">PO Number</label>
                          <input id="po_number" class="form-control" name="po_number"/>
                        </div>
                        <div class="form-group pt-2">
                          <label for="daterange_po" class="form-label">PO Date</label>
                          <div class="input-group flatpickr" id="div_daterange_po">
                            <span class="input-group-text input-group-addon bg-transparent" data-toggle><i data-feather="calendar"></i></span>
                            <input type="text" class="form-control bg-transparent" id="daterange_po" placeholder="Select date range" data-input>
                          </div>
                        </div>
                        <div class="form-group pt-2">
                          <label for="daterange_expected_shipping" class="form-label">Expected Shipping Date</label>
                          <div class="input-group flatpickr" id="div_daterange_expected_shipping">
                            <span class="input-group-text input-group-addon bg-transparent" data-toggle><i data-feather="calendar"></i></span>
                            <input type="text" class="form-control bg-transparent" id="daterange_expected_shipping" placeholder="Select date range" data-input>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Item Type</label>
                          <input id="item_type" class="form-control" name="item_type"/>
                        </div>
                        <div class="form-group pt-2">
                          <label class="form-label">Item Name</label>
                          <input id="item_name" class="form-control" name="item_name"/>
                        </div>
                        <div class="form-group pt-2">
                          <label class="form-label">Item Description</label>
                          <input id="item_description" class="form-control" name="item_description"/>
                        </div>
                      </div>

                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="form-label">Item Quantity</label>
                          <input id="item_quantity" class="form-control" name="item_quantity"/>
                        </div>
                        <div class="form-group pt-2">
                          <label class="form-label">Item Unit</label>
                          <input id="floating_rate" class="form-control" name="floating_rate"/>
                        </div>
                        <div class="form-group pt-2">
                          <label class="form-label">Item Unit Price (Rp)</label>
                          <input id="item_price" class="form-control" name="item_price"/>
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
              <th>Status</th>
              <th>VCH Code</th>
              <th class="text-center">Vendor</th>
              <th>PO Number</th>
              <th>PO Date</th>
              <th>Expected Shipping Date</th>
              <th>Item Name</th>
              <th>Item Type</th>
              <th>Item Description</th>
              <th>Item Quantity</th>
              <th>Item Unit</th>
              <th>Item Unit Price</th>
              <th>Item Max. Quantity</th>
              <th></th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="waitingModal" tabindex="-1" aria-labelledby="waitingModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 900px; max-width: 1200px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Approval Process</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
      </div>
      <div class="modal-body">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-8">
              <p>Status: <strong id="labelWarning"></strong></p>
              <table id="detailPo" class="w-100 table table-bordered mt-2">
                <tr>
                  <td class="w-50">
                    <label>VCH Code</label><br>
                    <label class="fw-bolder" id="labelVchCode"></label>
                  </td>
                  <td class="w-50">
                    <label>Vendor</label><br>
                    <label class="fw-bolder" id="labelVendor"></label>
                  </td>
                </tr> 
                <tr> 
                  <td class="w-50">
                    <label>PO Number</label><br>
                    <label class="fw-bolder" id="labelPoNumber"></label>
                  </td>
                  <td class="w-50">
                    <label>Item</label><br>
                    <label class="fw-bolder" id="labelItem"></label>
                  </td>
                </tr> 
                <tr>
                  <td class="w-50">
                    <label>PO Date</label><br>
                    <label class="fw-bolder" id="labelPoDate"></label>
                  </td>
                  <td class="w-50">
                    <label>Expected Shipping Date</label><br>
                    <label class="fw-bolder" id="labelExpectedShippingDate"></label>
                  </td>
                </tr>
                <tr>
                  <td class="w-50">
                    <label>Item Unit</label><br>
                    <label class="fw-bolder" id="labelItemUnit"></label>
                  </td>
                  <td class="w-50">
                    <label>Item Unit Price</label><br>
                    <label class="fw-bolder" id="labelItemUnitPrice"></label>
                  </td>
                </tr>
                <tr>
                  <td class="w-50">
                    <label>Item Type</label><br>
                    <label class="fw-bolder" id="labelItemType"></label>
                  </td>
                  <td class="w-50">
                    <label>Item Description</label><br>
                    <label class="fw-bolder" id="labelItemDescription"></label>
                  </td>
                </tr>
                <tr>
                  <td class="w-50">
                    <label>Item Quantity</label><br>
                    <label class="fw-bolder" id="labelItemQuantity"></label>
                  </td>
                  <td class="w-50">
                    <label>Item Max. Quantity</label><br>
                    <label class="fw-bolder" id="labelItemMaxQuantity"></label>
                  </td>
                </tr>
              </table>
              <br>
              <br>
              <strong>Reason for rejection</strong>
              <br>
              <textarea id="reason" class="w-100" rows="3"></textarea>
            </div>
            <div class="col-md-4">
              <strong>List Approval</strong>
              <table id="historyApproval" class="w-100 table mt-2" style="background-color: #F7F7F7;">
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div> 

      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="reject" onclick="submit('rejected')" style="display: none;">Reject</button>
        <button type="button" class="btn btn-success" id="approve" onclick="submit('approved')" style="display: none;">Approve</button>
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
    var poStatus = "";
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
            "url": "{{ route('purchase-order.grid-list') }}",
            "data": function ( d ) {
              var info = $('#gridDataTable').DataTable().page.info();
              d.start = info.start;
              d.limit = limit;
              d.status = poStatus;
              d.vch_code = $("#vch_code").val();
              d.vendor = $("#vendor").val();
              d.item_quantity = $("i#tem_quantity").val();
              d.po_number = $("#po_number").val();
              d.daterange_po = $("#daterange_po").val();
              d.daterange_expected_shipping = $("#daterange_expected_shipping").val();
              d.item_type = $("#item_type").val();
              d.item_name = $("#item_name").val();
              d.item_description = $("#item_description").val();
              d.item_quantity = $("#item_quantity").val();
              d.floating_rate = $("#floating_rate").val();
              d.item_price = $("#item_price").val();
              d.item_max_quantity = $("#item_max_quantity").val();
            },
            "dataSrc": function(json){
              
              json.recordsTotal = json.count;
              json.recordsFiltered = json.count;
              
              return json.data;
            }
          },
                                
          "columnDefs" : [
            { "targets": 0, "className": "text-center", "data": function( data, type, row, meta ){
                var classLabel = "fontColorWarning";
                if(data.status == "approved"){
                  classLabel = "fontColorApproved";
                } else if(data.status == "rejected"){
                  classLabel = "fontColorRejected";
                }
                selectedPurchaseOrder = data;
                return '<a href="#" onclick=showDetail("'+data.status+'")><label class="'+classLabel+'" style="cursor: pointer;";>'+ucFirstWord(data.status)+'</label></a>';
              }
            },
            { "targets": 1, "className": "text-center", "data": function(data, type, row, meta){
                  return data.evc_code+' - '+data.vch_code;
              }
            },
            { "targets": 2, "data": function(data, type, row, meta){
                  return '('+data.vendor_code+') '+data.vendor;
              }
            },
            { "targets": 3, "data": "po_number", "className": "text-center" },
            { "targets": 4, "data": "po_date", "className": "text-center" },
            { "targets": 5, "data": "expected_shipping_date", "className": "text-center" },
            { "targets": 6, "data": "item_name" },
            { "targets": 7, "data": "item_type", "className": "text-center" },
            { "targets": 8, "data": "item_description" },
            { "targets": 9, "data": "item_quantity", "className": "text-end",
              "render":function( data, type, row, meta ){
                var val = (data/1).toFixed(0).replace('.', ',');
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
              }
            },
            { "targets": 10, "data": "item_unit", "className": "text-center" },
            { "targets": 11, "data": "item_unit_price", "className": "text-end" },
            { "targets": 12, "data": "item_max_quantity", "className": "text-end",
              "render":function( data, type, row, meta ){
                var val = (data/1).toFixed(0).replace('.', ',');
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
              }
            },
            { "targets": 13, "data": function(data, type, row, meta){
                return '<a href="javascript:void(0)" onclick=confirmDelete("'+data.po_number+'") style="cursor: pointer;"><i data-feather="trash-2"></i></a>';
              }
            },
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
        $("#vch_code, #vendor, #item_quantity, #po_number, #daterange_po, #daterange_expected_shipping, #item_type, #item_name, #item_description, #item_quantity, #floating_rate, #item_price, #item_quantity").val("");
          e.stopImmediatePropagation();
          $('#gridDataTable').DataTable().ajax.reload();
      });

      $.fn.delete = function(poNumber) {
        $.ajax({
            type: "POST",
            url: "{{ route('purchase-order.remove') }}",
            data: {
              _token: "{{ csrf_token() }}",
              po_number: poNumber,
            },
            dataType: "json",
            timeout: 300000
        }).done(function(response){
            setTimeout(function() {
              $('#gridDataTable').DataTable().ajax.reload();
            }, 500);
            
        }).fail(function(response){
            
        });
      };

      // Date Picker
      if($('#div_daterange_po').length) {
        flatpickr("#div_daterange_po", {
          wrap: true,
          dateFormat: "d-m-Y",
          mode: "range"
        });
      }

      // Date Picker
      if($('#div_daterange_expected_shipping').length) {
        flatpickr("#div_daterange_expected_shipping", {
          wrap: true,
          dateFormat: "d-m-Y",
          mode: "range"
        });
      }
    });

    function submit(status){
      $("#reason").attr('style', '');
      if(status == "rejected" && $("#reason").val().length == 0){
        $("#reason").attr('style', 'border: 1px solid #d57171 !important');
        alert("Silakan isi alasan terlebih dahulu");
      } else {
        
        $.ajax({
            type: "POST",
            url: "{{ route('purchase-order.update') }}",
            data: {
              _token: "{{ csrf_token() }}",
              po_number: $("#labelPoNumber").text(),
              po_status: status,
              reason: $("#reason").val()
            },
            dataType: "json",
            timeout: 300000
        }).done(function(response){
            if(response.code == 200){
              $("#reason").val("");
              $('#waitingModal').modal('hide');
              setTimeout(function() {
                $('#gridDataTable').DataTable().ajax.reload();
              }, 500);
            }
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
      };
    }

    function confirmDelete(poNumber) {
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
                deleteRecord(poNumber);
            }
        });
    }
    
    function deleteRecord(poNumber){
        $.ajax({
            type: "POST",
            url: "{{ route('purchase-order.remove') }}",
            data: {
              _token: "{{ csrf_token() }}",
              po_number: poNumber,
            },
            dataType: "json",
            timeout: 300000
        }).done(function(response){
            setTimeout(function() {
              $('#gridDataTable').DataTable().ajax.reload();
            }, 500);
            
        }).fail(function(response){
            
        });
    }

    function showDetail(status){
      $("#labelWarning").html(ucFirstWord(status));

      var classLabel = "fontColorWarning";
      $("#reason").removeAttr("readonly");
      $("#reject, #approve").hide();
      latestHistory(status);

      if(status == "approved"){
        classLabel = "fontColorApproved";
      } else if(status == "rejected"){
        classLabel = "fontColorRejected";
      }

      if(["waiting"].includes(status)){
        $("#reason").val('');
      } else {
        $("#reject, #approve").hide();
        $("#reason").attr("readonly", "readonly");
      }

      $('#historyApproval tbody').empty();
      if(listApprover.length > 0){
        renderApproval(listApprover);
      }
      $("#labelVchCode").html(selectedPurchaseOrder.evc_code+' - '+selectedPurchaseOrder.vch_code);
      $("#labelVendor").html('('+selectedPurchaseOrder.vendor_code+') '+selectedPurchaseOrder.vendor);
      $("#labelPoNumber").html(selectedPurchaseOrder.po_number);
      $("#labelItem").html(selectedPurchaseOrder.item_name);
      $("#labelPoDate").html(selectedPurchaseOrder.po_date);
      $("#labelExpectedShippingDate").html(selectedPurchaseOrder.po_date);
      $("#labelItemUnit").html(selectedPurchaseOrder.item_unit);
      $("#labelItemUnitPrice").html(formatPrice(selectedPurchaseOrder.item_unit_price, 0));
      $("#labelItemType").html(selectedPurchaseOrder.item_type);
      $("#labelItemDescription").html(selectedPurchaseOrder.item_description);
      $("#labelItemQuantity").html(formatPrice(selectedPurchaseOrder.item_quantity, 0));
      $("#labelItemMaxQuantity").html(formatPrice(selectedPurchaseOrder.item_max_quantity, 0));
      $("#labelWarning").addClass(classLabel);
      $('#waitingModal').modal('show');
    }

    function latestHistory(status){
        $.ajax({
          type: "GET",
          url: "{{ route('purchase-order.latest-history') }}",
          data: {
            _token: "{{ csrf_token() }}",
            po_number: selectedPurchaseOrder.po_number,
            po_status: status
          },
          dataType: "json",
          timeout: 300000,
          async:false,
      }).done(function(response){
          $("#reason").val(response.data.reason);
          if((response.data.show_approval_buttons)){
            $("#reject, #approve").show();
          } else {
            $("#reject, #approve").hide();
          }
          listApprover = response.data.approver;

      }).fail(function(response){
          
      });
    }

    function renderApproval(data){
      $.each(data, function(key, value){

        var label = '<span class="label label-waiting">Waiting</span>';
        if(value.status == "approved"){
          label = '<span class="label label-approved">Approved</span>';
        } else if(value.status == "rejected"){
          label = '<span class="label label-rejected">Reject</span>';
        }

        $('#historyApproval > tbody:last-child').append('<tr>'+
                  '<td style="text-align: center;padding: 5px;vertical-align: middle;">'+value.text+'</td>'+
                  '<td style="text-align: center;padding: 5px;">'
                  +label+
                  '<br>'+(value.updated_at == null ? "-" : moment(value.updated_at).format("DD/MM/YYYY HH:mm:ss"))+
                  '</td>'+
        '</tr>');
      });
    }

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
