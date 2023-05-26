const login = (e, payload = null, callback = null) => {
    if (payload == null) {
        let email = select("#email");
        let password = select("#password");

        payload = {
            email: email.value,
            password: password.value,
        };
    }

    post("/api/user/login", payload)
    .then(res => {
        if (res.status == 200) {
            let user = res.user;
            if (callback == null) {
                localStorage.setItem('user_data', JSON.stringify(user));
                state.myData = user;
                getOrganizers();
                if (payload == null) {
                    if (user.is_active == 1) {
                        modal("#modalLogin").hide();
                    } else {
                        navigateModal("#modalOtp");
                    }
                } else {
                    modal("#modalLogin").hide();
                }
            } else {
                callback(user);
            }
        } else {
            writeError(res.message);
        }
    });

    if (e !== null) {
        e.preventDefault();
    }
}
const register = (e, payload = null, callback = null) => {
    if (payload == null) {
        let name = select("#modalRegister #name");
        let email = select("#modalRegister #email");
        let password = select("#modalRegister #password");

        payload = {
            name: name.value,
            email: email.value,
            password: password.value,
        };
    }

    post("api/user/register", payload).then(res => {
        if (res.status == 200) {
            let user = res.user;
            if (callback == null) {
                localStorage.setItem('user_data', JSON.stringify(user));
                state.myData = user;
                getOrganizers();

                if (payload == null) {
                    if (user.is_active == 0) {
                        navigateModal("#modalOtp");
                        select("#modalOtp #email").innerText = email.value;
                    }
                } else {
                    modal("#modalRegister").hide();
                }
            } else {
                callback(user);
            }
        } else {
            writeError(res.message);
        }
    });

    if (e !== null) {
        e.preventDefault();
    }
}

const handleCredentialResponse = (response) => {
    let user = parseJwt(response.credential);
    if (state.google_action == 'login') {
        login(null, {
            email: user.email,
            with_google: 1,
        });
    } else {
        register(null, {
            name: user.name,
            email: user.email,
            with_google: 1,
        })
    }
}

const loginWithGoogle = () => {
    google.accounts.id.initialize({
        client_id: select("#gcid").value,
        callback: handleCredentialResponse
    });
    google.accounts.id.prompt(notification => {
        if (notification.isNotDisplayed() || notification.isSkippedMoment()) {
            document.cookie =  `g_state=;path=/;expires=Thu, 01 Jan 1970 00:00:01 GMT`;
            google.accounts.id.prompt()
        }
    });
}