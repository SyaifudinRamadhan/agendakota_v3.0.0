<div class="modal help" id="modalExecutionType">
    <div class="modal-body" style="width: 50%;">
        <form action="#" class="modal-content">
            <div class="flex row item-center wrap mb-4">
                <div class="flex row grow-1 item-center">
                    <h2 class="m-0">Penyelenggaraan Event</h2>
                </div>
                <div>
                    <span class="text primary bold pointer small" onclick="modal('#modalExecutionType').hide()">
                        tutup
                    </span>
                </div>
            </div>

            <p>
                Kamu bisa mengatur bagaimana eventmu akan diselenggarakan. Kami sudah menentukan 3 cara penyelenggaran event yang umum dipakai,
                yaitu secara Offline, Hybrid, dan Online.
            </p>

            <div class="flex row item-center">
                <div class="text bold flex column grow-1 item-center justify-center">
                    <img src="{{ asset('images/stage.png') }}" alt="Event Offline" class="h-100 mb-1">
                    OFFLINE
                </div>
                <div class="flex w-60">
                    <p>
                        Kamu memiliki venue, sound system, lighting, crew, dan segala persiapan lainnya. Agendakota hadir
                        untuk membantu kamu menampilkan informasi seperti jadwal dan pembicara, mengorganisir peserta, ticketing, hingga membuatkan
                        sertifikat untuk peserta secara otomatis.
                    </p>
                </div>
            </div>
            <div class="flex row item-center mt-2">
                <div class="flex w-60">
                    <p>
                        Semua serba virtual. Peserta bisa mengikuti eventmu dari layar komputer atau ponsel mereka. Juga ada tambahan fitur virtual booth
                        dan video conference untuk interaksi dengan pengunjung.
                    </p>
                </div>
                <div class="text bold flex column grow-1 item-center justify-center">
                    <img src="{{ asset('images/live-streaming.png') }}" alt="Event Online" class="h-100 mb-1">
                    ONLINE
                </div>
            </div>

            <div class="mt-2 flex column item-center">
                <div class="flex row wrap item-center">
                    <img src="{{ asset('images/stage.png') }}" alt="Event Offline" class="h-100 mb-1">
                    <div class="pl-4 pr-4"><i class="bx bx-plus" style="font-size: 36px;"></i></div>
                    <img src="{{ asset('images/live-streaming.png') }}" alt="Event Online" class="h-100 mb-1">
                </div>
                <div class="w-100 text bold center mt-1">HYBRID</div>
                <p>
                    Kamu akan menyelenggarakan sebuah offline, tapi juga ingin disiarkan langsung, sehingga peserta punya pilihan apakah mereka
                    akan hadir di venue atau mau menonton siaran eventmu.
                </p>
            </div>

            <div class="text bold primary center pointer" onclick="modal('#modalExecutionType').hide()">
                Tutup
            </div>
        </form>
    </div>
</div>