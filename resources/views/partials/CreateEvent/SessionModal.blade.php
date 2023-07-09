<div class="modal" id="modal-add-session-multiple">
    <div class="modal-body" style="width: 50%;">
        <form action="#" class="modal-content" onsubmit="save(event)">
            {{ csrf_field() }}
            <input id="isEdit" type="hidden" name="isEdit" value="-1">
            <input type="hidden" name="ticket_type" id="ticket_type">
            <div class="flex row item-center wrap mb-2">
                <div class="flex row grow-1 item-center">
                    <h3 id="title" class="m-0">Tambah Sesi</h3>
                    <div class="flex ml-1">
                        <div class="text primary small bordered p-05 pl-1 pr-1 rounded" id="ticket_type_display"></div>
                    </div>
                </div>
                <div>
                    <div class="h-40 squarize use-height rounded-max bg-grey flex row item-center justify-center pointer close"
                        onclick="closePopUpEdit()">
                        <i class="bx bx-x"></i>
                    </div>
                </div>
            </div>


            <div>
                <div class="group">
                    <input type="text" id="session-title" name="title" oninput="setTitle(this)" required>
                    <label for="session-title" class="active">Nama / Judul Sesi :</label>
                </div>
                <div class="text bold mt-1">Pilih Tanggal & Waktu</div>
                <div class="flex row ticket-sales-dates">
                    <div class="group flex column grow-1 pr-1">
                        <input type="text" id="start-date" name="sDate" required>
                        <label class="active" for="start-date">Mulai Tanggal</label>
                    </div>
                    <div class="group flex column grow-1 pl-1">
                        <input type="text" id="end-date" name="eDate" required>
                        <label class="active" for="end-date">Berakhir</label>
                    </div>
                </div>
                <div class="flex row ticket-sales-dates">
                    <div class="group flex column grow-1 pr-1">
                        <input type="text" id="start-time" name="sTime" required>
                        <label class="active" for="start-time">Dari Jam</label>
                    </div>
                    <div class="group flex column grow-1 pl-1">
                        <input type="text" id="end-time" name="eTime" required>
                        <label class="active" for="end-time">Sampai</label>
                    </div>
                </div>
                <div class="group">
                    <textarea id="session-desc" name="desc" oninput="setDesc(this)" required></textarea>
                    <label class="active" for="session-desc">Deskripsi</label>
                </div>
                <div class="group">
                    <select id="stream-option" name="sOption" onchange="setMediaStream(this)" required>
                        <option value="0">-- Pilih Media --</option>
                        <option value="rtmp-stream">Streaming satu arah (RTMP)</option>
                        <option value="video-conference">Video Conference</option>
                        <!-- <option value="zoom-embed">Zoom Embed</option> -->
                        <!-- <option value="youtube-embed">YouTube Embed</option> -->
                    </select>
                    <label class="active" for="stream-option">Media Streaming :</label>
                </div>
                <div class="group d-none" id="url-streaming">
                    <input type="text" id="url-streaming-input" name="sUrl">
                    <label class="active" for="modal-add-session-multiple url-streaming-input">Stream URL :</label>
                </div>
            </div>

            <button class="mt-2 w-100 primary">Submit</button>
        </form>
    </div>
</div>
