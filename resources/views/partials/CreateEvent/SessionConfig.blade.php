<form id="form-add-single-session" action="#" onsubmit="save(event)">
    {{ csrf_field() }}
    <input id="isEdit" type="hidden" name="isEdit" value="-1">
    <div>
        <div class="group">
            <textarea id="session-desc" name="desc" oninput="setDesc(this)" required></textarea>
            <label for="session-desc" class="active">Deskripsi</label>
        </div>
        <div class="group">
            <select id="stream-option" name="sOption" onchange="setMediaStream(this)" required>
                <option value="0">-- Pilih Media --</option>
                <option value="rtmp-stream">Streaming satu arah (RTMP)</option>
                <option value="video-conference">Video Conference</option>
                <!-- <option value="zoom-embed">Zoom Embed</option> -->
                <!-- <option value="youtube-embed">YouTube Embed</option> -->
            </select>
            <label for="stream-option" class="active">Media Streaming :</label>
        </div>
        <div class="group d-none" id="url-streaming">
            <input type="text" id="url-streaming-input" name="sUrl">
            <label for="url-streaming-input" class="active">Stream URL :</label>
        </div>
    </div>
    <button id="edit" type="button" class="mt-2 w-100 primary d-none" onclick="editSession()">Edit</button>
    <button id="submit" type="submit" class="mt-2 w-100 primary">Submit</button>
</form>
