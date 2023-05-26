<div class="modal help" id="modalBreakdown">
    <div class="modal-body" style="width: 50%;">
        <form action="#" class="modal-content">
            <div class="flex row item-center wrap mb-4">
                <div class="flex row grow-1 item-center">
                    <h2 class="m-0">Breakdown</h2>
                </div>
                <div>
                    <span class="text primary bold pointer small" onclick="modal('#modalBreakdown').hide()">
                        tutup
                    </span>
                </div>
            </div>

            <p>
                Ketika kamu memilih untuk menyelenggarakan event secara online atau hybrid, kamu akan dapat mengisi fitur dan
                informasi tambahan yang mungkin kamu butuhkan.
            </p>

            <div class="flex row item-center mt-4">
                <div class="text bold flex column grow-1 item-center justify-center">
                    <img src="{{ asset('images/session.png') }}" alt="Event Offline" class="h-100 mb-2">
                    STAGE & SESSION
                </div>
                <div class="flex w-60">
                    <p>
                        Detail rundown untuk eventmu yang dinamis, punya lebih dari 1 sesi yang berjalan bersamaan. Kamu juga bisa membuat tiket
                        untuk setiap sesi yang kamu buat.
                    </p>
                </div>
            </div>
            <div class="flex row mobile-column item-center mt-2">
                <div class="flex w-60">
                    <p>
                        Kamu bisa menambahkan exhibitor dan mereka dapat berjualan di event yang kamu selenggarakan. Mereka akan memiliki
                        dashboard sendiri untuk mengatur konten mereka, jadi kamu tidak perlu direpotkan dengan hal ini.
                    </p>
                </div>
                <div class="text bold flex column grow-1 item-center justify-center">
                    <img src="{{ asset('images/booth.png') }}" alt="Event Online" class="h-100 mb-2">
                    EXHIBITOR
                </div>
            </div>
            <div class="flex row item-center mt-2">
                <div class="text bold flex column grow-1 item-center justify-center">
                    <img src="{{ asset('images/sponsor.png') }}" alt="Sponsor" class="h-100 mb-2">
                    SPONSOR
                </div>
                <div class="flex w-60">
                    <p>
                        Tambahkan pihak-pihak yang membantu menyukseskan eventmu. Logo mereka akan ditampilkan di halaman detail event dan
                        stage yang kamu buat. Ketika diklik, akan diarahkan ke website atau link produk mereka.
                    </p>
                </div>
            </div>
            <div class="flex row item-center mt-2">
                <div class="flex w-60">
                    <p>
                        Ada space khusus untuk rekanan media yang membantu mempublikasikan eventmu dan logo mereka dapat terpasang di sini.
                        Sama seperti sponsor, ketika diklik pengunjung akan diarahkan ke tautan yang sudah kamu tentukan.
                    </p>
                </div>
                <div class="text bold flex column grow-1 item-center justify-center">
                    <img src="{{ asset('images/media-partner.png') }}" alt="Media Partner" class="h-100 mb-2">
                    MEDIA PARTNER
                </div>
            </div>

            <div class="text bold primary center pointer mt-2" onclick="modal('#modalBreakdown').hide()">
                Tutup
            </div>
        </form>
    </div>
</div>