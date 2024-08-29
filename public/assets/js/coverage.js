    
    var comboDefault = [{
      id: "select",
      text: '-- Select --',
      disabled: true,
      selected: true,
    }];

    $(document).ready(function() {

      $('#district, #sub_district').select2();
      $('#province').select2({
            width: '100%',
            data: province
      }).on("select2:select", function (e) {
        if(e.params.data.selected){
          $.ajax({
              type: "GET",
              url: url_coverage_city, // define from page
              data: {
                _token: "{{ csrf_token() }}",
                province_id: e.params.data.id,
              },
              dataType: "json",
              timeout: 300000
          }).done(function(response){
            $('#city').empty();
            $('#district').empty();
            $('#sub_district').empty();
            
            $('#city').select2({ width: '100%', data: response.data });
            $('#district').select2({ width: '100%', data: comboDefault });
            $('#sub_district').select2({ width: '100%', data: comboDefault });
          }).fail(function(data){
              
          });
        }
      });
      
      $('#city').select2().on("select2:select", function (e) {
        if(e.params.data.selected){
          $.ajax({
              type: "GET",
              url: url_coverage_district,
              data: {
                _token: "{{ csrf_token() }}",
                city_id: e.params.data.id,
              },
              dataType: "json",
              timeout: 300000
          }).done(function(response){
            $('#district').empty();
            $('#sub_district').empty();
            $('#district').select2({ width: '100%', data: response.data });
            $('#sub_district').select2({ width: '100%', data: comboDefault });
          }).fail(function(data){
              
          });
        }
      });
      
      $('#district').select2().on("select2:select", function (e) {
        if(e.params.data.selected){
          $.ajax({
              type: "GET",
              url: url_coverage_sub_district,
              data: {
                _token: "{{ csrf_token() }}",
                district_id: e.params.data.id,
              },
              dataType: "json",
              timeout: 300000
          }).done(function(response){
            $('#sub_district').empty();
            $('#sub_district').select2({ width: '100%', data: response.data });
          }).fail(function(data){
              
          });
        }
      });
    });