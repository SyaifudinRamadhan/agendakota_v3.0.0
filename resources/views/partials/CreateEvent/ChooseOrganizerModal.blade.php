<div class="modal" id="modalOrganizer">
    <div class="modal-body" style="width: 50%;">
        <div class="modal-content">
            <div class="flex row item-center wrap mb-4">
                <div class="flex row grow-1 item-center">
                    <h2 class="m-0">Pilih Organizer</h2>
                </div>
                <div>
                    <span class="text primary bold pointer" onclick="modal('#modalOrganizer').hide()">
                        tutup
                    </span>
                </div>
            </div>

            <div id="renderOrganizers"></div>

            <div id="CreateOrganizerArea">
                <div class="text small muted mt-2 mb-2">atau buat organizer baru</div>
                
                <form class="flex row item-center" method="POST" id="createOrganizer" onsubmit="createOrganizer(event)">
                    {{ csrf_field() }}
                    <div class="group">
                        <input type="file" name="logo" id="create_organizer_logo" onchange="inputFile(this, {
                            preview: '#organizerLogoPreview'
                        })">
                        <div class="h-60 squarize rounded-max bordered pointer use-height flex row item-center justify-center" id="organizerLogoPreview">
                            <i class="bx bx-image-add"></i>
                        </div>
                    </div>
                    <div class="ml-2 flex column grow-1">
                        <input type="text" class="m-0" name="create_organizer_name" id="createOrganizerName" placeholder="Nama Organizer">
                    </div>
                    <button class="text bold primary pointer ml-1 bg-none">
                        Buat
                    </button>
                </form>
            </div>

            <div class="text center mt-2" id="ErrorOrganizerArea">
                <a href="#" class="text primary">
                    Upgrade paketmu
                </a> untuk membuat organizer baru
            </div>
        </div>
    </div>
</div>