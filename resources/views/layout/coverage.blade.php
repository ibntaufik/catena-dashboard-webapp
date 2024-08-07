            
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="province" class="form-label">Provinsi</label>
                <select id="province" class="form-control" name="province">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="city" class="form-label">Kota/Kab</label>
                <select id="city" class="form-control" name="city">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="district" class="form-label">Kecamatan</label>
                <select id="district" class="form-control" name="district">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="sub_district" class="form-label">Desa/Kelurahan</label>
                <select id="sub_district" class="form-control" name="sub_district">
                  <option value="select" disabled selected>-- Select --</option>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="latitude" class="form-label">Latitude</label>
                <input type="text" class="form-control" id="latitude" maxlength="255" placeholder="Latitude" onkeypress="return isNumericAndDot(event);">
              </div>
            </div>
            <div class="col-sm-3">
              <div class="mb-3">
                <label for="longitude" class="form-label">Longitude</label>
                <input type="text" class="form-control" id="longitude" maxlength="255" placeholder="Longitude" onkeypress="return isNumericAndDot(event);">
              </div>
            </div>