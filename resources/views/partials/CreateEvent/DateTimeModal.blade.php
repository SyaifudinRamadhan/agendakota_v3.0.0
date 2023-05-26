<div class="modal" id="modalDateTime">
    <div class="modal-body" style="width: 40%;">
        <form action="#" class="modal-content" onsubmit="saveDateTime(event)">
            {{ csrf_field() }}
            <div class="flex row item-center wrap mb-4">
                <div class="flex row grow-1 item-center">
                    <h2 class="m-0">Pilih Tanggal dan Waktu</h2>
                </div>
            </div>

            <div class="flex row row-sm">
                <div class="group flex column grow-1 pr-1">
                    <input type="text" name="event_start_date" id="event_start_date" required readonly>
                    <label class="active" for="event_start_date">Mulai Tanggal :</label>
                </div>
                <div class="group flex column grow-1 pl-1">
                    <input type="text" name="event_end_date" id="event_end_date" required readonly>
                    <label class="active" for="event_end_date">Berakhir :</label>
                </div>
            </div>

            <div class="flex row">
                <div class="group flex column grow-1 pr-1">
                    <input type="text" name="event_start_time" id="event_start_time" required readonly>
                    <label class="active" for="event_start_time">Dari Jam :</label>
                </div>
                <div class="group flex column grow-1 pl-1">
                    <input type="text" name="event_end_time" id="event_end_time" required readonly>
                    <label class="active" for="event_end_time">Sampai :</label>
                </div>
            </div>

            <div class="bg-primary transparent text small p-1 pl-2 pr-2 rounded mt-1 d-none" id="modalMessage">
                Lorem ipsum dolor sit amet
            </div>

            <button class="primary bold pointer w-100 mt-2">
                Simpan
            </button>
            
        </form>
    </div>
</div>