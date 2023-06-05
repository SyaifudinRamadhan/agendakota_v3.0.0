let state = {
    ableToResendOtp: true,
    error_context: "",
    myData: JSON.parse(localStorage.getItem('user_data')),
    google_action: 'login',
    event_types: JSON.parse(select("input#event_types").value),
    currentScreen: 0,
    historyScreen: 0,
    mapboxAccessToken: 'pk.eyJ1IjoiaGFsb3JpeWFuIiwiYSI6ImNrdzBiMmFqNjR4amkzMG8wanBjNXFheGQifQ.ED3-QJwwgbEvOb1ZBsBC0w',
    gmapsToken: 'AIzaSyAiX4DuzinqgbzRWhn60HEPLdFmBNmpp2E',
    execution_type_description: {
        offline: "Kamu memiliki venue dan segala persiapannya dan peserta harus datang ke eventmu",
        hybrid: "Kamu punya venue tapi juga disiarkan di Youtube, jadi peserta bisa memilih datang atau streaming",
        online: "Peserta duduk santai di mana saja untuk menyaksikan siaran eventmu",
    },
    visibility_description: {
        public: "Event kamu akan ditampilkan untuk umum. Semua pengguna Agendakota dapat mengikuti eventmu",
        private: "Hanya orang yang kamu bagi link saja yang bisa mengikuti eventmu"
    },
    screenWidth: screen.width,
    
    ticket_price: "0",
    ticket_quantity: 1,

    provinces: [],
    cities: [],
    organizers: [],

    // form field
    field: {
        organizer: null,
        organizer_id: null,
        selected_event_types: [],
        selected_event_types_data: [],
        event_name: "",
        event_description: "",
        tagline: "",
        snk: "",
        breakdowns: [],
        execution_type: 'offline',
        province: "",
        city: "",
        address: "",
        visibility: 'public',
        tickets: [],
        topics: [],
        topic_str : '',
        start_date: "",
        end_date: "",
        start_time: "",
        end_time: "",
        sessions: []
    },
}; 

const renderCreatedTickets = () => {
    select("#renderTicketArea").innerHTML = '';
    state.field.tickets.forEach(ticket => {
        let priceDisplay = "";
        if (ticket.type == "gratis") {
            priceDisplay = "Gratis";
        } else if (ticket.type == "suka-suka") {
            priceDisplay = "Bayar Sesukamu";
        } else {
            priceDisplay = Currency(ticket.price).encode();
        }
        Element("div", {
            class: "TicketDisplay"
        }).render("#renderTicketArea", `<div class="HalfCircle LeftCircle"></div>
        <div class="HalfCircle RightCircle"></div>
        <div class="info">
            <h4 class="m-0">${ticket.name}</h4>
            <div class="text muted small mt-1">${ticket.description}</div>
        </div>
        <div class="flex row item-center mt-2">
            <div class="flex column grow-1">
                <div class="price text small bold primary">${priceDisplay}</div>
                <div class="text small muted mt-05">${moment(ticket.start_date).format('D MMM')} - ${moment(ticket.end_date).format('D MMM YYYY')}</div>
            </div>
            <div class="pointer text primary" onclick="removeTicket('${ticket.key}')"><i class="bx bx-trash"></i></div>
        </div>`);
    })
}

if (localStorage.getItem('event_data') != null) {
    let data = JSON.parse(localStorage.getItem('event_data'));

    data.selected_event_types_data.forEach(type => {
        let selector = `.event-type-item[event-type='${btoa(type.name)}']`;
        select(selector).classList.add('active');
        select(`${selector} img`).setAttribute('src', `images/event_types/${type.image_active}`);
    })

    state.field = data;
    if (data.event_name != "") {
        select("#EventTitle").innerText = data.event_name;
        select("#EventTitle").style.opacity = "1";
    }
    if (data.event_description) {
        select("#EventDescription").value = data.event_description;
    }
    
    if (data.start_date != "" || data.end_date != "" || data.start_time != "" || data.end_time != "") {
        select(`#date_display`).innerHTML = `${moment(data.start_date).format('D MMMM')} - ${moment(data.end_date).format('D MMMM Y')}`;
        select(`#time_display`).innerHTML = `${data.start_time} - ${data.end_time}`;
        select("#modalDateTime #event_start_date").value = data.start_date;
        select("#modalDateTime #event_end_date").value = data.end_date;
        flatpickr("#modalDateTime #event_end_date", {
            dateFormat: 'Y-m-d',
            minDate: data.start_date
        });
        select("#modalDateTime #event_start_time").value = data.start_time;
        select("#modalDateTime #event_end_time").value = data.end_time;
        flatpickr("#event_end_time", {
            dateFormat: "H:i",
            noCalendar: true,
            enableTime: true,
            time_24hr: true,
        });
    }

    if (data.topics.length > 0) {
        data.topics.forEach(topic => {
            select(`.topic-item[topic='${btoa(topic)}']`).classList.add('active');
        });
    }

    if (data.address != "") {
        select("#location_display").innerHTML = `${data.address}`;
        select("#modalLocation #address").value = data.address;
    }

    if (data.organizer != null) {
        let organizerLogo = data.organizer.logo == "" ? 'default_logo.png' : data.organizer.logo;
        select("#organizer_name_display").innerHTML = data.organizer.name;
        select("#organizer_logo_display").setAttribute('bg-image', `/storage/organization_logo/${organizerLogo}`);
    }

    selectAll(".visibility_type").forEach(item => item.classList.remove('active'));
    select(`.visibility_type[visibility='${data.visibility}']`).classList.add('active');
    select("#visibilityDescription").innerText = state.visibility_description[data.visibility];

    if (data.execution_type != null) {
        selectAll(".execution_type").forEach(item => item.classList.remove('active'));
        select(`.execution_type[execution-type='${data.execution_type}']`).classList.add('active');
        select("#executionTypeDescription").innerText = state.execution_type_description[data.execution_type];
        if (data.execution_type.toLowerCase() == 'offline') {
            select(".breakdown-section").classList.add('d-none');
            select(".breakdown-section").classList.remove('flex');
        } else {
            select(".breakdown-section").classList.remove('d-none');
            select(".breakdown-section").classList.add('flex');
        }

        if (data.execution_type != "offline") {
            data.breakdowns.forEach(breakdown => {
                select(`.breakdown-item[breakdown='${breakdown}']`).classList.add('active');
            })
        }
    }

    bindDivWithImage();
}

if (state.myData == "" || state.myData == null) {
    modal("#modalLogin").show();
} else {
    if (state.myData.is_active == 0) {
        modal("#modalOtp").show();
        select("#modalOtp #email").innerText = state.myData.email;
    }
}

let screens = [
    {
        id: "tipeEvent",
        footer: ["Beda Tipe Beda Kebutuhan", "Kami membantumu menyesuaikan apa yang eventmu butuhkan"],
        callback: () => {
            writeFooter('Beda Tipe Beda Kebutuhan', 'Kami membantumu menyesuaikan apa yang eventmu butuhkan');
        },
        validation: () => {
            if (state.field.selected_event_types.length == 0) {
                return writeError("Kamu harus memilih setidaknya satu tipe event")
            }
            if (state.field.topics.length == 0) {
                return writeError("Kamu harus memilih setidaknya satu topik")
            }
            return true;
        }
    },
    {
        id: "basicInfo",
        callback: () => {
            writeFooter('Ada Apa di Eventmu?', 'Berikan informasi tentang eventmu dengan jelas');
        },
        validation: () => {
            let field = state.field;
            if (field.event_name == "") {
                return writeError('Masa eventmu ga ada judulnya?');
            }
            if (field.event_description == "") {
                return writeError('Paling tidak jelasin eventmu akan membahas apa pada deskripsi event');
            }
            if (select("input#cover").files.length == 0) {
                return writeError('Kamu belum mengupload gambar banner');
            }
            if (field.city == "" || field.province == "") {
                state.error_context = "location";
                return writeError('Di mana eventmu akan diselenggarakan?');
            }
            if (field.topics.length == 0) {
                state.error_context = "topic";
                return writeError('Kamu harus memilih topik apa yang akan dibahas pada eventmu nanti');
            }
            if (field.start_date == "" || field.end_date == "" || field.start_time == "" || field.end_time == "") {
                state.error_context = "datetime";
                return writeError('Kapan eventmu akan dilaksanakan?');
            }
            if (field.organizer_id == null) {
                state.error_context = "organizer";
                return writeError('Kamu harus memilih atau membuat organizer dulu');
            }
            // if (field.breakdowns.length == 0) {
            //     state.field.breakdowns.push('Stage and Session');
            // }
            return true;
        }
    },
    {
        id: "session-config",
        footer: [state.field.execution_type === 'offline' ? '' : 'Media Streaming yang Digunakan dalam Eventmmu'],
        callback: () => {
            if(state.field.execution_type === 'online' || state.field.execution_type == 'hybrid'){
                setNavigatorSession(state.field.breakdowns, state.field.execution_type)
                writeFooter('Media Streaming yang Digunakan dalam Eventmmu', 'Sesuaikan sesi - sesi dalam event online / hybrid beserta dengan media streaming yang ingin kamu gunakan');
            }else{
                setAutoOfflineSession();
                console.log(screens[state.historyScreen].id );
                if(screens[state.historyScreen].id == 'ticketing'){
                    previousScreen()
                }else{
                    nextScreen()
                }
            }
        },
        validation: () => {
            if((state.field.execution_type === 'online' || state.field.execution_type == 'hybrid') && state.field.sessions.length === 0){
                state.error_context = null
                return writeError('Eventmu harus memiliki setidaknya 1 sesi')
            }else if((state.field.execution_type === 'online' || state.field.execution_type == 'hybrid') && state.field.sessions.length > 0){
                let sessions  = state.field.sessions
                for (let i = 0; i < sessions.length; i++) {
                    if(sessions[i].streamOption == null){
                        if(state.field.breakdowns.includes("Stage and Session")){
                            state.error_context = `session_err_~!@!~${i}`
                        }else{
                            state.error_context = null;
                        }
                        return writeError(`Sesi eventmu dengan judul ${sessions[i].title} belum kamu atur media streamingnnya nih !`)   
                    }
                }
            }
            return true;
        }
    },
    {
        id: "ticketing",
        footer: ["Cara untuk Berpartisipasi dalam Eventmu", "Sesuaikan cara orang-orang untuk bisa hadir pada eventmu"],
        callback: () => {
            writeFooter('Cara untuk Berpartisipasi dalam Eventmu', 'Sesuaikan cara orang-orang untuk bisa hadir pada eventmu');
            select("#renderedTicketTitle").classList.remove('d-none');
            renderCreatedTickets();
        },
        validation: () => {
            if (state.field.tickets.length == 0) {
                return writeError('Eventmu harus memiliki setidaknya 1 tiket');
            }
            return true;
        }
    },
    {
        id: "loading",
        callback: () => {},
    }
]

const typing = (type, input) => {
    let tagName = input.tagName;
    if (tagName.toLowerCase() == "div") {
        state.field[type] = input.innerText;
    } else {
        state.field[type] = input.value;
    }
    
    localStorage.setItem('event_data', JSON.stringify(state.field));
}
const clickEventName = dom => {
    if (dom.innerText == "Nama Event") {
        dom.innerText = "";
        dom.style.opacity = "1";
    }
}

const switchAccount = () => {
    localStorage.removeItem('user_data');
    state.myData = null;
    navigateModal('#modalLogin', this);
}

const previousScreen = () => {
    if (state.currentScreen == 0) {
        return false;
    }
    state.currentScreen--;

    if (state.currentScreen == 0) {
        select("button.prev").classList.add('d-none');
    }

    if(screens[state.currentScreen].id != "ticketing"){
        select('.footer #next').innerHTML = "Selanjutnya"
    }

    let theScreen = screens[state.currentScreen];
    let screenDom = select(`.screen-item#${theScreen.id}`);
    selectAll(".screen-item").forEach(screen => {
        screen.classList.add('d-none');
        screen.classList.remove('flex');
    });
    screenDom.classList.remove('d-none');
    screenDom.classList.add('flex');
    screens[state.currentScreen].callback();
    if(state.historyScreen != 0){
        state.historyScreen--
    }
}
const nextScreen = () => {
    if (!screens[state.currentScreen].validation()) {
        return false;
    }
    select("button.prev").classList.remove('d-none');

    if (state.currentScreen + 1 != screens.length) {
        state.currentScreen++;
    } 

    if (screens[state.currentScreen].id == "loading") {
        select(".footer").style.display = "none";
        submit();
    } else if (screens[state.currentScreen].id == "ticketing") {
        select(".footer #next").innerHTML = "Publish";
    }
    
    let theScreen = screens[state.currentScreen];
    let screenDom = select(`.screen-item#${theScreen.id}`);
    selectAll(".screen-item").forEach(screen => {
        screen.classList.add('d-none');
        screen.classList.remove('flex');
    });
    screenDom.classList.remove('d-none');
    screenDom.classList.add('flex');
    screens[state.currentScreen].callback();

    if (state.historyScreen + 1 != screens.length) {
        state.historyScreen++;
    }
}

const writeFooter = (title, description) => {
    select(".footer h3").innerText = title;
    select(".footer p").innerText = description;
}
const writeError = (message, context = null) => {
    modal("#modalError").show();
    select("#modalError #message").innerText = message;
    if (context != null) {
        state.error_context = context;
    }
    return false;
}
const help = (sel) => modal(sel).show();
// help("#modalBreakdown")
writeFooter('Beda Tipe Beda Kebutuhan', 'Kami membantumu menyesuaikan apa yang eventmu butuhkan');
const selectType = (button, key, data) => {
    data = JSON.parse(data);
    let selector = `.event-type-item[event-type='${btoa(data.name)}']`;
    
    if (button.classList.contains('active')) {
        button.classList.remove('active');
        select(`${selector} img`).setAttribute('src', `images/event_types/${data.image}`);
        removeArray(data.name, state.field.selected_event_types);
        state.field.selected_event_types_data.forEach((iteration, i) => {
            if (iteration.name == data.name) {
                state.field.selected_event_types_data.splice(i, 1);
            }
        });
    } else {
        if (state.field.selected_event_types.length < 2) {
            button.classList.add('active');
            select(`${selector} img`).setAttribute('src', `images/event_types/${data.image_active}`);
            state.field.selected_event_types.push(data.name);
            state.field.selected_event_types_data.push(data);
        }
    }
    localStorage.setItem('event_data', JSON.stringify(state.field));
}
const selectBreakdown = (data, button) => {
    let selector = `.breakdown-item[breakdown='${data}']`;
    if (button.classList.contains('active')) {
        button.classList.remove('active');
        removeArray(data, state.field.breakdowns);
    } else {
        button.classList.add('active');
        state.field.breakdowns.push(data);
    }
    localStorage.setItem('event_data', JSON.stringify(state.field));
}

// handle otp input
let otpInputs = selectAll(".otp-input");
otpInputs.forEach((input, i) => {
    input.addEventListener("input", e => {
        let value = e.currentTarget.value;
        let toType = parseInt(value);
        select("#modalOtp #message").classList.add('d-none');
        select("#modalOtp #message").innerText = "...";
        if (isNaN(toType)) {
            input.value = "";
        } else {
            if (i == otpInputs.length - 1) {
                verifyOtp()
            } else {
                input.nextElementSibling.focus();
            }
        }
    });
});

const verifyOtp = () => {
    state.ableToResendOtp = false;
    select("#modalOtp #resendOtp").innerText = "verifying...";
    let code = "";
    otpInputs.forEach(input => {
        code += input.value;
    });

    post("api/user/otp", {
        code: code,
        user_id: state.myData.id
    })
    .then(res => {
        state.ableToResendOtp = false;
        let message = select("#modalOtp #message");
        message.classList.remove('d-none', 'bg-primary');
        if (res.status == 200) {
            message.classList.add('bg-green');
            modal("#modalOtp").hide(1);
            localStorage.setItem('user_data', JSON.stringify(res.user));
            state.myData = res.user;
        } else {
            message.classList.add('bg-red');
        }
        message.innerText = res.message;
        select("#modalOtp #resendOtp").innerText = "Kirim Ulang";
    })
}
const resendOtp = () => {
    if (state.ableToResendOtp) {
        // 
    }
}

const navigateModal = (destination, action = null) => {
    modal().hideAll(1);
    modal(destination).show();
    state.google_action = action;
}

const getProvinces = () => {
    let req = post("api/rajaongkir/province")
    .then(provinces => {
        state.provinces = provinces;
        provinces.forEach(province => {
            Element("option", {
                value: province.province
            })
            .render("#modalLocation #province", province.province);
        });

        if (state.field.province != "") {
            select(`#modalLocation #province option[value='${state.field.province}']`).selected = true;
            chooseProvince(state.field.province);
        }
    });
}

const getCities = (provinceID) => {
    selectAll("#modalLocation #city option")[0].innerHTML = "loading...";
    let req = post("api/rajaongkir/city", {
        province_id: provinceID
    })
    .then(cities => {
        state.cities = cities;
        select("#modalLocation #city").innerHTML = '';
        state.field.city = cities[0].city_name;
        Element("option", {
            value: ''
        })
        .render("#modalLocation #city", '-- Pilih Kota --');
        cities.forEach(city => {
            Element("option", {
                value: city.city_name
            })
            .render("#modalLocation #city", `${city.type} ${city.city_name}`);
        });

        if (state.field.city != "") {
            select(`#modalLocation #city option[value='${state.field.city}']`).selected = true;
            chooseCity(state.field.city);
        }
    })
}
const chooseProvince = province => {
    state.provinces.forEach(prov => {
        if (province == prov.province) {
            getCities(prov.province_id);
        }
    });
    state.field.province = province;
}
const chooseCity = city => {
    state.field.city = city;
    // getCenter(`${city} ${state.field.province}`);
}
getProvinces();

const submitLocation = e => {
    if (state.field.address == "") {
        writeError("Kamu belum memilih alamat untuk eventmu", "location");
        e.preventDefault();
    }
    select("#location_display").innerHTML = `${state.field.address} <br /> ${state.field.city}, ${state.field.province}`;
    modal("#modalLocation").hide();
    e.preventDefault();
}

const getOrganizers = () => {
    if (state.myData != null && state.myData != "") {
        post("api/user/organization", {
            token: state.myData.token
        })
        .then(res => {
            state.organizers = res.user.organizations;
            if (res.user.can_create_organizer) {
                select("#ErrorOrganizerArea").classList.add('d-none');
            } else {
                select("#CreateOrganizerArea").classList.add('d-none');
            }
            renderOrganizers();
        });
    }
}
const renderOrganizers = () => {
    select("#renderOrganizers").innerHTML = '';
    state.organizers.forEach(organizer => {
        let organizerLogo = organizer.logo == "" ? "default_logo.png" : organizer.logo;
        Element("div", {
            class: "flex row item-center h-80 pointer",
            onclick: `chooseOrganizer('${JSON.stringify(organizer)}')`,
        })
        .render("#renderOrganizers", `
        <div class="rounded-max h-50 squarize use-height" bg-image="storage/organization_logo/${organizerLogo}"></div>
        <div class="ml-2">${organizer.name}</div>
        `);
        squarize();
        bindDivWithImage();
    })
}
getOrganizers();

const canCreateOrganizer = () => {
    // 
}

const createOrganizer = (e) => {
    let name = select("#createOrganizerName");
    let formData = new FormData();
    formData.append('name', name.value);
    formData.append('token', state.myData.token);
    formData.append('logo', select("#create_organizer_logo").files[0]);
    let req = fetch("api/organization/create", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        let organizer = res.organizer;
        state.field.organizer_id = organizer.id;
        name.value = "";
        let organizerLogo = organizer.logo;
        if (organizer.logo == undefined || organizer.logo == null || organizer.logo == "") {
            organizerLogo = "default_logo.png";
        }
        select("#organizer_name_display").innerText = organizer.name;
        select("#organizer_logo_display").style.backgroundColor = "#fff";
        select("#organizer_logo_display").setAttribute('bg-image', `storage/organization_logo/${organizerLogo}`);
        squarize();
        bindDivWithImage();
        modal("#modalOrganizer").hide();
        getOrganizers();
    });

    e.preventDefault();
}
const chooseOrganizer = (organizer) => {
    organizer = JSON.parse(escapeJson(organizer));
    let organizerLogo = organizer.logo == "" ? 'default_logo.png' : organizer.logo;
    select("#organizer_name_display").innerText = organizer.name;
    select("#organizer_logo_display").style.backgroundColor = "#fff";
    select("#organizer_logo_display").setAttribute('bg-image', `storage/organization_logo/${organizerLogo}`);
    bindDivWithImage();
    state.field.organizer_id = organizer.id;
    state.field.organizer = organizer;
    modal("#modalOrganizer").hide();
    localStorage.setItem('event_data', JSON.stringify(state.field));
}
const chooseExecution = (type, button) => {
    state.field.execution_type = type;
    selectAll(".execution_type").forEach(item => item.classList.remove('active'));
    button.classList.add('active');
    select("#executionTypeDescription").innerText = state.execution_type_description[type];
    if (type.toLowerCase() == 'offline') {
        select(".breakdown-section").classList.add('d-none');
        select(".breakdown-section").classList.remove('flex');
    } else {
        select(".breakdown-section").classList.remove('d-none');
        select(".breakdown-section").classList.add('flex');
    }
    localStorage.setItem('event_data', JSON.stringify(state.field));
}
const chooseVisibility = (type, button) => {
    state.field.visibility = type;
    selectAll(".visibility_type").forEach(item => item.classList.remove('active'));
    button.classList.add('active');
    select("#visibilityDescription").innerText = state.visibility_description[type];
    localStorage.setItem('event_data', JSON.stringify(state.field));
}
const chooseTopic = (topic, button) => {
    if (button.classList.contains('active')) {
        removeArray(topic, state.field.topics);
        state.field.topic_str = state.field.topics.join('&');
        button.classList.remove('active');
    } else {
        if (state.field.topics.length < 3) {
            state.field.topics.push(topic);
            state.field.topic_str = state.field.topics.join('&');
            button.classList.add('active');
        } else {
            select("#topic_error").classList.remove('d-none');
        }
    }
    localStorage.setItem('event_data', JSON.stringify(state.field));
}
const closeError = () => {
    modal('#modalError').hide();
    if (state.error_context == "topic") {
        modal('#modalTopic').show();
    } else if (state.error_context == "location") {
        modal('#modalLocation').show();
    } else if (state.error_context == "datetime") {
        modal('#modalDateTime').show();
    } else if (state.error_context == "organizer") {
        modal('#modalOrganizer').show();
    } else if (state.error_context.match('session_err')) {
        autoOpenEdit(state.error_context.split('~!@!~')[1]);
    } else if(state.error_context == "err_add_session") {
        modal("#modal-add-session-multiple").show();
    } else if(state.error_context == "error_add_ticket"){
        modal("#modalTicket").show();
    }
    state.error_context = null
}
const closeTopicModal = () => {
    let topics = state.field.topics;
    let area = select("#topic_display");
    if (topics.length > 0) {
        area.innerText = state.field.topics.join(',');
        area.innerHTML = "";
        area.classList.add('flex', 'row', 'wrap');
        topics.forEach(topic => {
            Element("div", {
                class: "mb-1 rounded bg-primary p-1 pl-2 pr-2 mr-1 text small-2 transparent"
            })
            .render("#topic_display", topic)
        })
    } else {
        area.classList.remove('flex', 'row', 'wrap')
        area.innerText = 'Pilih Topik';
    }
    select("#topic_error").classList.add('d-none');
    modal('#modalTopic').hide();
}

const login = (e, payload = null) => {
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
            writeError(res.message);
        }
    });

    if (e !== null) {
        e.preventDefault();
    }
}
const register = (e, payload = null) => {
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
            console.log(user);
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

flatpickr("#event_start_date", {
    dateFormat: 'Y-m-d',
    minDate: Date.now(),
    onChange: (selectedDate, dateStr) => {
        select("#event_end_date").value = "";
        flatpickr("#event_end_date", {
            dateFormat: 'Y-m-d',
            minDate: dateStr
        });
    }
});

flatpickr("#event_start_time", {
    dateFormat: "H:i",
    noCalendar: true,
    enableTime: true,
    time_24hr: true,
    onChange: (selectedDate, dateStr) => {
        select("#event_end_time").value = "";
        flatpickr("#event_end_time", {
            dateFormat: "H:i",
            noCalendar: true,
            enableTime: true,
            time_24hr: true,
        })
    }
});

const saveDateTime = (e = null) => {
    let modalMessage = select("#modalDateTime #modalMessage");
    let startDate = moment(select("#event_start_date").value);
    let endDate = moment(select("#event_end_date").value);
    let startTime = select("#event_start_time").value;
    let endTime = select("#event_end_time").value;
    state.field.start_date = startDate.format('Y-MM-D');
    state.field.end_date = endDate.format('Y-MM-D');
    state.field.start_time = startTime;
    state.field.end_time = endTime;

    if (startDate.format('D') == "Invalid date") {
        modalMessage.classList.remove('d-none');
        modalMessage.innerText = "Kamu harus memilih tanggal mulai";
    } else if (endDate.format('D') == "Invalid date") {
        modalMessage.classList.remove('d-none');
        modalMessage.innerText = "Kamu harus memilih tanggal berakhir";
    } else if (startTime == "" || endTime == "") {
        modalMessage.classList.remove('d-none');
    } else {
        select(`#date_display`).innerHTML = `${startDate.format('D MMMM')} - ${endDate.format('D MMMM Y')}`;
        select(`#time_display`).innerHTML = `${startTime} - ${endTime}`;
        
        // select("#time_display").innerHTML = `${startDate.format('D MMMM')} - ${endDate.format('D MMMM Y')}
        // <br /><br />
        // ${startTime} - ${endTime}`;
        modal('#modalDateTime').hide();
    }
    localStorage.setItem('event_data', JSON.stringify(state.field));
    if (e != null) {
        e.preventDefault();
    }
}
let form;
const submit = () => {
    let formData = new FormData();
    for (let key in state.field) {
        if (key == 'tickets') {
            formData.append('tickets', btoa(JSON.stringify(state.field[key])));
        } else if(key == 'sessions'){
            formData.append('sessions', btoa(JSON.stringify(state.field[key])));
        } else {
            formData.append(key, state.field[key]);
        }
    }

    // handle cover image
    formData.append('cover', select("input#cover").files[0]);
    form = formData;
    let req = fetch('/api/event/buat', {
        method: "POST",
        body: formData
    })
    .then( res => res.json() )
    .then(res => {
        // localStorage.removeItem('event_data');
        let ref = `${state.field.organizer_id}/event/${res.event_id}/event-overview`;
        let token = res.organizer.user.token;
        setTimeout(() => {
            localStorage.removeItem('event_data');
            localStorage.removeItem('user_data');
            window.location = `/buat-event/after?ref=${ref}&t=${token}`;
        }, 500);
    })
}

if (screen.width < 480) {
    select(".footer button.primary").innerHTML = "<i class='bx bx-chevron-right'></i>";
    select(".footer button.prev").innerHTML = "<i class='bx bx-chevron-left'></i>";
    select("#chooseOrganizer").innerText = "Pilih";
    select("#modalDateTime h2").innerText = "Tanggal dan Waktu";
}

// mapboxgl.accessToken = state.mapboxAccessToken;
// const map = new mapboxgl.Map({
//     container: 'map',
//     style: 'mapbox://styles/mapbox/streets-v12',
//     center: [112.7378266, -7.2459717],
//     zoom: 10,
//     accessToken: state.mapboxAccessToken
// });

// let marker = new mapboxgl.Marker({
//     draggable: true
// }).setLngLat([-74.5, 40]).addTo(map);

// map.on('load', e => {
//     map.resize();
// })
// map.on('click', e => {
//     let coordinates = e.lngLat;
//     marker.setLngLat(coordinates).addTo(map);
//     getAddress(coordinates);
// });

// marker.on('dragend', () => {
//     let coordinates = marker.getLngLat();
//     getAddress(coordinates);
// })

// const getAddress = (coordinates) => {
//     select("#location_display_on_modal").innerHTML = "";
//     let urlQuery = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${coordinates.lat},${coordinates.lng}&key=${state.gmapsToken}`;
//     fetch(urlQuery)
//     .then(res => res.json())
//     .then(res => {
//         res = res.results[0];
//         select("#location_display_on_modal").innerHTML = res.formatted_address;
//         state.field.address = res.formatted_address;
//         localStorage.setItem('event_data', JSON.stringify(state.field));
//     })
// }
// const getCenter = (query) => {
//     let urlQuery = `https://api.mapbox.com/geocoding/v5/mapbox.places/${query}.json?types=region&access_token=${state.mapboxAccessToken}`;
//     get(urlQuery)
//     .then(response => {
//         let res = response.features[0];
//         let center = res.center;
//         localStorage.setItem('event_data', JSON.stringify(state.field));
//         map.flyTo({
//             center: center
//         });
//         select("#mapContainer").classList.remove('d-none');
//     });
// }

ClassicEditor
.create( document.querySelector( '#EventDescription' ) )
.then(editor => {
    editor.setData(state.field.event_description);
    editor.model.document.on('change:data', (e, data) => {
        state.field.event_description = editor.getData();
        localStorage.setItem('event_data', JSON.stringify(state.field));
    });
})
.catch( error => {
    console.error( error );
});

ClassicEditor
.create( document.querySelector( '#SnK' ) )
.then(editor => {
    editor.setData(state.field.snk);
    editor.model.document.on('change:data', (e, data) => {
        state.field.snk = editor.getData();
        localStorage.setItem('event_data', JSON.stringify(state.field));
    });
})
.catch( error => {
    console.error( error );
} );
