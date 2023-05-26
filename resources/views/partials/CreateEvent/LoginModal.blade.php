<div class="modal nox" id="modalLogin">
    <div class="modal-body" style="width: 30%;">
        <form action="#" class="modal-content" onsubmit="login(event)">
            {{ csrf_field() }}
            <div class="flex row item-center wrap mb-4">
                <div class="flex row grow-1 item-center">
                    <h2 class="m-0">Login</h2>
                </div>
                <div>
                    <span class="text primary bold pointer" onclick="navigateModal('#modalRegister', 'register')">
                        Daftar
                    </span>
                </div>
            </div>

            <div class="group">
                <input type="text" name="email" id="email" required>
                <label for="email">Email :</label>
            </div>
            <div class="group">
                <input type="password" name="password" id="password" required>
                <label for="password">Password :</label>
            </div>
            <div class="text right">
                <a href="#" class="text primary small">
                    Lupa Password?
                </a>
            </div>
            <button class="primary mt-2 w-100">Login</button>

            <div class="separator-area mt-1 mb-1">
                <div class="separator-text">atau</div>
            </div>

            {{-- <div class="social-login-button mt-2 flex row item-center justify-center pointer" onclick="loginWithGoogle()">
                <img src="{{ asset('riyan/images/icon-google.png') }}">
                Google
            </div> --}}
            <a href="{{route('user.loginGooglePage2', ['google', 'create-event'])}}">
                <div class="social-login-button mt-2 flex row item-center justify-center">
                    <img src="{{ asset('riyan/images/icon-google.png') }}">
                    Google
                </div>
            </a>
        </form>
    </div>
</div>