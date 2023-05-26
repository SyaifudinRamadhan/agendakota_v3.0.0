<div class="modal nox" id="modalOtp">
    <div class="modal-body" style="width: 30%;">
        <form action="#" class="modal-content">
            {{ csrf_field() }}
            <div class="flex row item-center wrap mb-4">
                <div class="flex row grow-1 item-center">
                    <h2 class="m-0">Verifikasi</h2>
                </div>
                <div>
                    <span class="text primary bold pointer small" onclick="switchAccount()">
                        Ganti Akun
                    </span>
                </div>
            </div>

            <div class="text muted small">
                Kami telah mengirimkan 4 digit kode OTP ke email <span id="email"></span>, mohon masukkan pada box berikut ini
            </div>

            <div class="flex row item-center justify-center mt-2 mb-2">
                <input type="text" class="otp-input" maxlength="1">
                <input type="text" class="otp-input" maxlength="1">
                <input type="text" class="otp-input" maxlength="1">
                <input type="text" class="otp-input" maxlength="1">
            </div>

            <div class="text small bg-primary transparent rounded p-1 pl-2 pr-2 d-none" id="message">
                asds
            </div>

            <div class="text center mt-4">
                <span id="resendOtp" class="text primary bold">
                    Kirim Ulang
                </span>
            </div>
        </form>
    </div>
</div>