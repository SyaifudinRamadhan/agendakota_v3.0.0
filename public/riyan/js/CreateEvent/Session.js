let sessionState = {
    id: 0,
    type: "single",
    field: {
        startSession: {
            date: null,
            time: null
        },
        endSession: {
            date: null,
            time: null
        },
        title: null,
        streamUrl: null,
        streamOption: null,
        description: null,
        key: null
    }
};

const templateCard = () => {};

const chooseTime = () => {
    let sDate = select(
        `${
            sessionState.type === "single"
                ? "#form-add-single-session"
                : "#modal-add-session-multiple"
        } #start-date`
    ).value;
    let eDate = select(
        `${
            sessionState.type === "single"
                ? "#form-add-single-session"
                : "#modal-add-session-multiple"
        } #end-date`
    ).value;
    let sTime = select(
        `${
            sessionState.type === "single"
                ? "#form-add-single-session"
                : "#modal-add-session-multiple"
        } #start-time`
    ).value;
    let eTime = select(
        `${
            sessionState.type === "single"
                ? "#form-add-single-session"
                : "#modal-add-session-multiple"
        } #end-time`
    ).value;

    if (
        sDate == "" ||
        sDate == undefined ||
        sTime == "" ||
        sTime == undefined ||
        eDate == "" ||
        eDate == undefined ||
        eTime == "" ||
        eTime == undefined
    ) {
        return false;
    } else if (
        new Date(`${sDate} ${sTime}`) >=
            new Date(`${state.field.start_date} ${state.field.start_time}`) &&
        new Date(`${eDate} ${eTime}`) <=
            new Date(`${state.field.end_date} ${state.field.end_time}`)
    ) {
        sessionState.field.startSession.date = sDate;
        sessionState.field.startSession.time = sTime;
        sessionState.field.endSession.date = eDate;
        sessionState.field.endSession.time = eTime;

        return true;
    } else {
        return false;
    }
};

const setTitle = el => {
    if (el.value != undefined || el.value != "") {
        sessionState.field.title = el.value;
    }
};

const setMediaStream = el => {
    let res = el.value;
    const setUrl = el => {
        if (el.value != "" && el.value != undefined) {
            sessionState.field.streamUrl = el.value;
        }
    };
    if (res == "youtube-embed" || res == "zoom-embed") {
        select(
            `${
                sessionState.type === "single"
                    ? "#form-add-single-session"
                    : "#modal-add-session-multiple"
            } #url-streaming`
        ).classList.remove("d-none");
        select(
            `${
                sessionState.type === "single"
                    ? "#form-add-single-session"
                    : "#modal-add-session-multiple"
            } #url-streaming-input`
        ).oninput = setUrl(this);
        sessionState.field.streamOption = res;
    } else if (res != "rtmp-stream" && res != "video-conference") {
        console.log(res);
        select(
            `${
                sessionState.type === "single"
                    ? "#form-add-single-session"
                    : "#modal-add-session-multiple"
            } #url-streaming`
        ).classList.add("d-none");
        if (sessionState.type == "multiple") {
            state.error_context = "err_add_session";
        }
        writeError(
            "Maaf, Kami tidak menyediakan media streaming diluar opsi tersebut"
        );
        modal("#modal-add-session-multiple").hide();
    } else {
        select(
            `${
                sessionState.type === "single"
                    ? "#form-add-single-session"
                    : "#modal-add-session-multiple"
            } #url-streaming`
        ).classList.add("d-none");
        sessionState.field.streamOption = res;
    }
};

const setDesc = el => {
    if (el.value != undefined || el.value != "") {
        sessionState.field.description = el.value;
    }
};

const clearForm = () => {
    // form input time stamp
    try {
        select(
            `${
                sessionState.type === "single"
                    ? "#form-add-single-session"
                    : "#modal-add-session-multiple"
            } #end-date`
        ).value = "";
        select(
            `${
                sessionState.type === "single"
                    ? "#form-add-single-session"
                    : "#modal-add-session-multiple"
            } #start-time`
        ).value = "";
        select(
            `${
                sessionState.type === "single"
                    ? "#form-add-single-session"
                    : "#modal-add-session-multiple"
            } #end-time`
        ).value = "";
        select(
            `${
                sessionState.type === "single"
                    ? "#form-add-single-session"
                    : "#modal-add-session-multiple"
            } #start-date`
        ).value = "";

        select(
            `${
                sessionState.type === "single"
                    ? "#form-add-single-session"
                    : "#modal-add-session-multiple"
            } #session-title`
        ).value = "";
    } catch (error) {
        console.log(error);
    }
    select(
        `${
            sessionState.type === "single"
                ? "#form-add-single-session"
                : "#modal-add-session-multiple"
        } #stream-option`
    ).value = "";
    select(
        `${
            sessionState.type === "single"
                ? "#form-add-single-session"
                : "#modal-add-session-multiple"
        } #url-streaming`
    ).value = "";
    select(
        `${
            sessionState.type === "single"
                ? "#form-add-single-session"
                : "#modal-add-session-multiple"
        } #session-desc`
    ).value = "";

    sessionState.field.startSession.date = null;
    sessionState.field.endSession.date = null;

    sessionState.field.startSession.time = null;
    sessionState.field.endSession.time = null;

    sessionState.field.title = null;
    sessionState.field.streamUrl = null;
    sessionState.field.streamOption = null;
    sessionState.field.description = null;
};

const makeid = length => {
    let result = "";
    const characters =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    const charactersLength = characters.length;
    let counter = 0;
    while (counter < length) {
        result += characters.charAt(
            Math.floor(Math.random() * charactersLength)
        );
        counter += 1;
    }
    return result;
};

const save = evt => {
    evt.preventDefault();
    if (sessionState.type == "single") {
        sessionState.field.startSession.date = state.field.start_date;
        sessionState.field.endSession.date = state.field.end_date;

        sessionState.field.startSession.time = state.field.start_time;
        sessionState.field.endSession.time = state.field.end_time;

        sessionState.field.title = state.field.event_name;
    } else if (sessionState.type == "multiple") {
        let dateTime = chooseTime();
        if (!dateTime) {
            state.error_context = "err_add_session";
            writeError("Masa waktu sesi event diluar waktu eventmu !");
            modal("#modal-add-session-multiple").hide();
            return;
        }

        if (sessionState.field.title == null) {
            state.error_context = "err_add_session";
            writeError("Masa sesi eventmu ga ada judulnya ?");
            modal("#modal-add-session-multiple").hide();
            return;
        }
    }

    if (
        (sessionState.field.streamOption == "zoom-embed" ||
            sessionState.field.streamOption == "youtube-embed") &&
        sessionState.field.streamUrl == null
    ) {
        if (sessionState.type == "multiple") {
            state.error_context = "err_add_session";
        }
        writeError(
            "Setidaknya, berikan URL untuk media streaming yang kamu pakai"
        );
        modal("#modal-add-session-multiple").hide();
        return;
    } else if (sessionState.field.streamOption == null) {
        if (sessionState.type == "multiple") {
            state.error_context = "err_add_session";
        }
        writeError(
            "Kamu wajib memilih opsi streaming untuk sesi dari event online / hybrid"
        );
        modal("#modal-add-session-multiple").hide();
        return;
    }

    if (sessionState.field.description == null) {
        if (sessionState.type == "multiple") {
            state.error_context = "err_add_session";
        }
        writeError("Setidaknya berikan deskripsi untuk sesi acaramu");
        modal("#modal-add-session-multiple").hide();
        return;
    }

    if (
        select(
            `${
                sessionState.type === "single"
                    ? "#form-add-single-session"
                    : "#modal-add-session-multiple"
            } #isEdit`
        ).value == -1
    ) {
        sessionState.field.key = makeid(10);
        state.field.sessions.push(
            JSON.parse(JSON.stringify(sessionState.field))
        );
    } else {
        state.field.sessions[
            select(
                `${
                    sessionState.type === "single"
                        ? "#form-add-single-session"
                        : "#modal-add-session-multiple"
                } #isEdit`
            ).value
        ] = JSON.parse(JSON.stringify(sessionState.field));
    }
    localStorage.setItem("event_data", JSON.stringify(state.field));

    sessionState.id = sessionState.id + 1;

    if (sessionState.type == "multiple") {
        modal("#modal-add-session-multiple").hide();
    }
    clearForm();
    renderViewSession();
};

const createSession = () => {
    select("#modal-add-session-multiple #isEdit").value = -1;
    modal("#modal-add-session-multiple").show();
    select("#modal-add-session-multiple #title").innerHTML = "Tambah Sesi";
};

const autoOpenEdit = id => {
    modal("#modal-add-session-multiple").show();
    select("#modal-add-session-multiple #title").innerHTML = "Edit Sesi";
    let data = state.field.sessions[id];
    try {
        select(
            `${
                sessionState.type === "single"
                    ? "#form-add-single-session"
                    : "#modal-add-session-multiple"
            } #end-date`
        ).value = data.endSession.date;
        select(
            `${
                sessionState.type === "single"
                    ? "#form-add-single-session"
                    : "#modal-add-session-multiple"
            } #start-time`
        ).value = data.startSession.time;
        select(
            `${
                sessionState.type === "single"
                    ? "#form-add-single-session"
                    : "#modal-add-session-multiple"
            } #end-time`
        ).value = data.endSession.time;
        select(
            `${
                sessionState.type === "single"
                    ? "#form-add-single-session"
                    : "#modal-add-session-multiple"
            } #start-date`
        ).value = data.startSession.date;

        select(
            `${
                sessionState.type === "single"
                    ? "#form-add-single-session"
                    : "#modal-add-session-multiple"
            } #session-title`
        ).value = data.title;
    } catch (error) {
        console.log(error);
    }

    select(
        `${
            sessionState.type === "single"
                ? "#form-add-single-session"
                : "#modal-add-session-multiple"
        } #stream-option`
    ).value = data.streamOption;
    select(
        `${
            sessionState.type === "single"
                ? "#form-add-single-session"
                : "#modal-add-session-multiple"
        } #url-streaming`
    ).value = data.streamUrl;
    select(
        `${
            sessionState.type === "single"
                ? "#form-add-single-session"
                : "#modal-add-session-multiple"
        } #session-desc`
    ).value = data.description;

    select(
        `${
            sessionState.type === "single"
                ? "#form-add-single-session"
                : "#modal-add-session-multiple"
        } #isEdit`
    ).value = id;

    sessionState.field.title = data.title;
    sessionState.field.streamUrl = data.streamUrl;
    sessionState.field.streamOption = data.streamOption;
    sessionState.field.description = data.description;
    sessionState.field.key = data.key;
};

const editSession = target => {
    if (sessionState.type == "multiple") {
        modal("#modal-add-session-multiple").show();
        select("#modal-add-session-multiple #title").innerHTML = "Edit Sesi";
    } else {
        let desc = select("#form-add-single-session #session-desc");
        let streamOpt = select("#form-add-single-session #stream-option");
        let urlStream = select("#form-add-single-session #url-streaming-input");
        console.log("set tombo");

        desc.readOnly = false;
        streamOpt.disabled = false;
        urlStream.readOnly = false;

        console.log(desc, streamOpt, urlStream);

        select("#form-add-single-session #submit").classList.remove("d-none");
        select("#form-add-single-session #edit").classList.add("d-none");
    }

    let id = 0;
    if (target !== undefined) {
        id = target.id;
    }
    let data = state.field.sessions[id];
    console.log(data, id);
    try {
        select(
            `${
                sessionState.type === "single"
                    ? "#form-add-single-session"
                    : "#modal-add-session-multiple"
            } #end-date`
        ).value = data.endSession.date;
        select(
            `${
                sessionState.type === "single"
                    ? "#form-add-single-session"
                    : "#modal-add-session-multiple"
            } #start-time`
        ).value = data.startSession.time;
        select(
            `${
                sessionState.type === "single"
                    ? "#form-add-single-session"
                    : "#modal-add-session-multiple"
            } #end-time`
        ).value = data.endSession.time;
        select(
            `${
                sessionState.type === "single"
                    ? "#form-add-single-session"
                    : "#modal-add-session-multiple"
            } #start-date`
        ).value = data.startSession.date;

        select(
            `${
                sessionState.type === "single"
                    ? "#form-add-single-session"
                    : "#modal-add-session-multiple"
            } #session-title`
        ).value = data.title;
    } catch (error) {
        console.log(error);
    }

    select(
        `${
            sessionState.type === "single"
                ? "#form-add-single-session"
                : "#modal-add-session-multiple"
        } #stream-option`
    ).value = data.streamOption;
    select(
        `${
            sessionState.type === "single"
                ? "#form-add-single-session"
                : "#modal-add-session-multiple"
        } #url-streaming`
    ).value = data.streamUrl;
    select(
        `${
            sessionState.type === "single"
                ? "#form-add-single-session"
                : "#modal-add-session-multiple"
        } #session-desc`
    ).value = data.description;

    select(
        `${
            sessionState.type === "single"
                ? "#form-add-single-session"
                : "#modal-add-session-multiple"
        } #isEdit`
    ).value = id;

    sessionState.field.title = data.title;
    sessionState.field.streamUrl = data.streamUrl;
    sessionState.field.streamOption = data.streamOption;
    sessionState.field.description = data.description;
    sessionState.field.key = data.key;
};

const closeEdit = () => {
    if (sessionState.type == "multiple") {
        modal("#modal-add-session-multiple").hide();
    }
    clearForm();
};

const closePopUpEdit = () => {
    modal("#modal-add-session-multiple").hide();
    console.log("menutupu pop up");
    if (select("#modal-add-session-multiple #isEdit").value != -1) {
        closeEdit();
    }
};

const removeSession = el => {
    let id = el.id;
    let session = state.field.sessions[id];

    // Menghapus tiket dengan key sesuai sesi yang dihapus
    let tickets = state.field.tickets;
    let newTickets = [];
    for (let i = 0; i < tickets.length; i++) {
        if (tickets[i].session_key != session.key) {
            newTickets.push(JSON.parse(JSON.stringify(tickets[i])));
        }
    }
    state.field.tickets = newTickets;

    state.field.sessions.splice(id, 1);
    renderViewSession();
    localStorage.setItem("event_data", JSON.stringify(state.field));
};

const renderViewSession = () => {
    let data = state;
    if (data.field.sessions.length > 0) {
        if (data.field.breakdowns.includes("Stage and Session")) {
            select("#rendered-session-card").classList.remove("d-none");
            select("#renderer-session-area").childNodes.forEach(child => {
                child.remove();
            });
            let sessions = state.field.sessions;
            for (let i = 0; i < sessions.length; i++) {
                let el = Element("div", {
                    class: "TicketDisplay"
                }).render(
                    "#renderer-session-area",
                    `<div>
                        <div id="${i}" class="info pointer" onclick="editSession(this)">
                            <h4 class="m-0">${
                                sessions[i].title == ""
                                    ? data.field.event_name
                                    : sessions[i].title
                            }</h4>
                            <div class="text muted small mt-1">${
                                sessions[i].description
                            }</div>
                        </div>
                        <div class="flex row item-center mt-2">
                            <div class="flex column grow-1">
                                <div class="price text small bold primary">${
                                    sessions[i].streamOption == null
                                        ? "Belum Diatur"
                                        : sessions[i].streamOption.replaceAll(
                                              "-",
                                              " "
                                          )
                                }</div>
                                <div class="text small muted mt-05">${moment(
                                    sessions[i].startSession.date
                                ).format("D MMM")} - ${moment(
                        sessions[i].endSession.date
                    ).format("D MMM YYYY")}</div>
                            </div>
                            <div id="${i}" class="pointer text primary" onclick="removeSession(this)"><i class="bx bx-trash"></i></div>
                        </div>
                    </div>`
                );
            }
        } else {
            select("#rendered-session-card").classList.add("d-none");
            select("#renderer-session-area").childNodes.forEach(child => {
                child.remove();
            });

            let desc = select("#form-add-single-session #session-desc");
            let streamOpt = select("#form-add-single-session #stream-option");
            let urlStream = select(
                "#form-add-single-session #url-streaming-input"
            );
            console.log(data);
            desc.readOnly = true;
            streamOpt.disabled = true;
            urlStream.readOnly = true;

            desc.value = data.field.sessions[0].description;
            streamOpt.value = data.field.sessions[0].streamOption;
            if (
                data.field.sessions[0] === "zoom-embed" ||
                data.field.sessions[0] === "youtube-embed"
            ) {
                urlStream.value = data.field.sessions[0].streamUrl;
                select(
                    "#form-add-single-session #url-streaming"
                ).classList.remove(d - none);
            }

            select("#form-add-single-session #submit").classList.add("d-none");
            select("#form-add-single-session #edit").classList.remove("d-none");
        }
    } else {
        select("#rendered-session-card").classList.add("d-none");
        select("#renderer-session-area").childNodes.forEach(child => {
            child.remove();
        });
    }
};

const setNavigatorSession = (breakdowns, executionType) => {
    if (
        (executionType === "online" || executionType === "hybrid") &&
        breakdowns.includes("Stage and Session")
    ) {
        select("#multi-session-nav").classList.remove("d-none");
        select("#single-session-nav").classList.add("d-none");
        // select('#add-session-multiple').onclick = createSession
        sessionState.type = "multiple";
    } else if (executionType === "online" || executionType === "hybrid") {
        select("#multi-session-nav").classList.add("d-none");
        select("#single-session-nav").classList.remove("d-none");
        sessionState.type = "single";
        if (state.field.sessions.length > 1) {
            state.field.sessions = [
                JSON.parse(JSON.stringify(state.field.sessions[0]))
            ];
            localStorage.setItem("event_data", JSON.stringify(state.field));
        }
    }
    renderViewSession();
};

const setAutoOfflineSession = () => {
    if (state.field.sessions.length > 1) {
        let session = JSON.parse(JSON.stringify(state.field.sessions[0]));
        let tickets = state.field.tickets;
        let newTickets = [];
        for (let i = 0; i < tickets.length; i++) {
            if (tickets[i].session_key == session.key) {
                newTickets.push(JSON.parse(JSON.stringify(tickets[i])));
            }
        }
        state.field.tickets = newTickets;
        state.field.sessions = [session];
        localStorage.setItem("event_data", JSON.stringify(state.field));
        clearForm();
    }else if (state.field.sessions.length == 0){
        sessionState.field.startSession.date = state.field.start_date;
        sessionState.field.endSession.date = state.field.end_date;

        sessionState.field.startSession.time = state.field.start_time;
        sessionState.field.endSession.time = state.field.end_time;

        sessionState.field.title = state.field.event_name;
        sessionState.field.streamUrl = null;
        sessionState.field.streamOption = null;
        sessionState.field.description = "-";

        sessionState.field.key = makeid(10);

        state.field.sessions.push(JSON.parse(JSON.stringify(sessionState.field)));
        localStorage.setItem("event_data", JSON.stringify(state.field));
        clearForm();
    }
};

flatpickr("#modal-add-session-multiple #start-date", {
    dateFormat: "Y-m-d",
    minDate: Date.now(),
    maxDate: new Date(state.field.end_date),
    onChange: (selectedDate, dateStr) => {
        select("#modal-add-session-multiple #end-date").value = "";
        flatpickr("#modal-add-session-multiple #end-date", {
            dateFormat: "Y-m-d",
            minDate: dateStr,
            maxDate: new Date(state.field.end_date)
        });
    }
});

flatpickr("#modal-add-session-multiple #start-time", {
    dateFormat: "H:i",
    noCalendar: true,
    enableTime: true,
    time_24hr: true,
    onChange: (selectedDate, dateStr) => {
        select("#modal-add-session-multiple #end-time").value = "";
        flatpickr("#modal-add-session-multiple #end-time", {
            dateFormat: "H:i",
            noCalendar: true,
            enableTime: true,
            time_24hr: true
        });
    }
});
