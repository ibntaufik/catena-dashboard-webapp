            
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="f_province" class="form-label">Provinsi</label>
                <select id="f_province" class="form-control" name="f_province">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="f_city" class="form-label">Kota/Kab</label>
                <select id="f_city" class="form-control" name="f_city">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="f_district" class="form-label">Kecamatan</label>
                <select id="f_district" class="form-control" name="f_district">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="f_sub_district" class="form-label">Desa/Kelurahan</label>
                <select id="f_sub_district" class="form-control" name="f_sub_district">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="f_latitude" class="form-label">Latitude</label>
                <input type="text" class="form-control" id="f_latitude" maxlength="255" placeholder="Latitude" onkeypress="return latitude(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="f_longitude" class="form-label">Longitude</label>
                <input type="text" class="form-control" id="f_longitude" maxlength="255" placeholder="Longitude" onkeypress="return isNumericAndDot(event);">
              </div>
            </div>