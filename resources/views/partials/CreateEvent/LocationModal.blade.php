<div class="modal" id="modalLocation">
    <div class="modal-body" style="width: 60%;">
        <form action="#" class="modal-content" onsubmit="submitLocation(event)">
            {{ csrf_field() }}
            <div class="flex row item-center wrap mb-4">
                <div class="flex row grow-1 item-center">
                    <h2 class="m-0">Pilih Lokasi</h2>
                </div>
                <div>
                    <span class="text primary bold pointer" onclick="modal('#modalLocation').hide()">
                        tutup
                    </span>
                </div>
            </div>

            <div class="flex row item-center mobile-column">
                <div class="group flex column grow-1 pr-1 mobile-full-width">
                    <select name="province" id="province" onchange="chooseProvince(this.value)" required>
                        <option value="">-- Pilih Provinsi --</option>
                    </select>
                    <label class="active" for="province">Provinsi</label>
                </div>
                <div class="group flex column grow-1 pl-1 mobile-full-width">
                    <select name="city" id="city" onchange="chooseCity(this.value)" required>
                        <option value="">-- Pilih Kota --</option>
                    </select>
                    <label class="active" for="city">Kota</label>
                </div>
            </div>

            <div class="group">
                <textarea name="address" id="address" oninput="typing('address', this)" required></textarea>
                <label for="address">Alamat Lengkap</label>
            </div>

            <div id="mapContainer" class="d-none">
                <div id="map" class="h-300 w-100"></div>
                <div id="location_display_on_modal" class="mt-2">Klik pada peta untuk menentukan lokasi eventmu</div>
            </div>

            <div class="text right mt-2">
                <button class="primary transparent">Simpan</button>
            </div>
        </form>
    </div>
</div>