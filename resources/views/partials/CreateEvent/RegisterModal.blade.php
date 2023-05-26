<div class="modal" id="modalRegister">
    <div class="modal-body" style="width: 30%;">
        <form action="#" class="modal-content" onsubmit="register(event)">
            {{ csrf_field() }}
            <div class="flex row item-center wrap mb-4">
                <div class="flex row grow-1 item-center">
                    <h2 class="m-0">Register</h2>
                </div>
                <div>
                    <span class="text primary bold pointer" onclick="navigateModal('#modalLogin', 'login')">
                        Login
                    </span>
                </div>
            </div>

            <div class="group">
                <input type="text" name="name" id="name" required>
                <label for="name">Nama :</label>
            </div>
            <div class="group">
                <input type="text" name="email" id="email" required>
                <label for="email">Email :</label>
            </div>
            <div class="group">
                <input type="password" name="password" id="password" required>
                <label for="password">Password :</label>
            </div>

            <button class="primary mt-2 w-100">Register</button>

            <div class="separator-area mt-1 mb-1">
                <div class="separator-text">atau</div>
            </div>

            <div class="social-login-button mt-2 flex row item-center justify-center" onclick="loginWithGoogle()">
                <img src="{{ asset('riyan/images/icon-google.png') }}">
                Google
            </div>
        </form>
    </div>
</div>