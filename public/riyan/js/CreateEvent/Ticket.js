let TicketTab = select("#modalTicket #TicketTab");
const addTicket = (type) => {
    modal("#modalTicket").show();
    select("#modalTicket #ticket_type").value = type;
    select("#modalTicket #session_key").classList.add('d-none');
    if(state.field.breakdowns.includes("Stage and Session") && state.field.execution_type != 'offline'){
        select("#modalTicket #session_key").classList.remove('d-none');
        let sessions = state.field.sessions
        select('#modalTicket #session_key').innerHTML = '<option value="">-- Pilih Sesi --</option>';
        sessions.forEach(session => {
            select('#modalTicket #session_key').innerHTML += `<option value=${session.key}>${session.title} (${session.startSession.date} ${session.startSession.time} - ${session.endSession.date} ${session.endSession.time})</option>`
        });
    }else{
        flatpickr("#ticket_start_date", {
            dateFormat: 'Y-m-d',
            maxDate: state.field.end_date == "" ? "" : state.field.end_date,
            onChange: (selectedDate, dateStr) => {
                select("#ticket_end_date").value = "";
                flatpickr("#ticket_end_date", {
                    dateFormat: 'Y-m-d',
                    minDate: dateStr,
                    maxDate: state.field.end_date
                });
            }
        });
        
        flatpickr("#voucher_start", {
            dateFormat: 'Y-m-d',
            maxDate: state.field.end_date == "" ? "" : state.field.end_date,
            onChange: (selectedDate, dateStr) => {
                select("#voucher_end").value = "";
                flatpickr("#voucher_end", {
                    dateFormat: 'Y-m-d',
                    maxDate: state.field.end_date == "" ? "" : state.field.end_date,
                    minDate: dateStr
                });
            }
        });
    }

    if (type == 'gratis') {
        select("#modalTicket #ticketPriceArea").style.display = 'none';
        TicketTab.style.display = "none";
        // select("#modalTicket .tab-content[key='TicketVoucher']").classList.add('d-none');
        select("#modalTicket .tab-item[target='TicketForm']").click();
    } else if (type == 'suka-suka') {
        select("#modalTicket #ticketPriceArea").style.display = "none";
        TicketTab.style.display = "none";
        // select("#modalTicket .tab-content[key='TicketVoucher']").classList.add('d-none');
        select("#modalTicket .tab-item[target='TicketForm']").click();
    } else {
        select("#modalTicket #ticketPriceArea").style.display = "block";
        TicketTab.style.display = "flex";
        // TicketTab.style.display = "flex";
        select("#modalTicket .tab-item[target='TicketForm']").click();
        // select("#modalTicket .tab-content[key='TicketVoucher']").classList.remove('d-none');
    }

    select("#modalTicket #ticket_type_display").innerHTML = type.toUpperCase();
}
const typingCurrency = (input) => {
    let value = input.value;
    let decodedValue = Currency(value).decode();
    state.ticket_price = decodedValue.toString();
    
    input.value = Currency(state.ticket_price).encode();
}
const setTicketQuantity = (action) => {
    if (action == 'increase') {
        if (state.ticket_quantity == 1) {
            state.ticket_quantity = 5;
        } else {
            state.ticket_quantity += 5;
        }
    } else {
        if (state.ticket_quantity - 5 > 0) {
            state.ticket_quantity -= 5;
        }
    }
    select("#modalTicket #ticket_quantity").value = state.ticket_quantity;
}

const createTicket = e => {
    let name = select("#modalTicket #ticket_name");
    let description = select("#modalTicket #ticket_description");
    let quantity = select("#modalTicket #ticket_quantity");
    let price = select("#modalTicket #ticket_price");
    let start_date = select("#modalTicket #ticket_start_date");
    let end_date = select("#modalTicket #ticket_end_date");

    let voucher_name = select("#modalTicket #voucher_name");
    let voucher_code = select("#modalTicket #voucher_code");
    let voucher_quantity = select("#modalTicket #voucher_quantity");
    let voucher_type = select("#modalTicket #voucher_type");
    let voucher_amount = select("#modalTicket #voucher_amount");
    let voucher_start = select("#modalTicket #voucher_start");
    let voucher_end = select("#modalTicket #voucher_end");
    let minimum_transaction = select("#modalTicket #minimum_transaction");
    let session_key;

    if(!state.field.breakdowns.includes("Stage and Session") || state.field.execution_type == 'offline'){
        session_key = state.field.sessions[0].key;
    }else if(state.field.breakdowns.includes("Stage and Session")){
        session_key = select("#modalTicket #session_key").value;
        if(session_key == undefined || session_key == ''){
            modal("#modalTicket").hide();
            return writeError('Kamu wajib memilih satu sesi event untuk ticketmu !', 'error_add_ticket');
        }
    }

    let toCreate = {
        key: generateRandomString(12),
        type: select("#modalTicket #ticket_type").value,
        name: name.value,
        description: description.value,
        quantity: quantity.value,
        price: Currency(price.value).decode(),
        start_date: start_date.value,
        end_date: end_date.value,
        session_key: session_key,
        voucher: null,
    };

    if (voucher_name != "") {
        toCreate['voucher'] = {
            name: voucher_name.value,
            code: voucher_code.value,
            quantity: voucher_quantity.value,
            type: voucher_type.value,
            amount: voucher_amount.value,
            start: voucher_start.value,
            end: voucher_end.value,
            minimum_transaction: minimum_transaction.value
        }
    }

    state.field.tickets.push(toCreate);

    selectAll(".tab-content[key='TicketVoucher'] input").forEach(input => {
        input.value = '';
    });

    select("#renderedTicketTitle").classList.remove('d-none');

    renderCreatedTickets();
    modal("#modalTicket").hide();

    name.value = '';
    description.value = '';
    quantity.value = 5;
    start_date.value = '';
    end_date.value = '';
    price.value = 'Rp 0';
    state.ticket_quantity = 1;
    state.ticket_price = '0';
    

    localStorage.setItem('event_data', JSON.stringify(state.field));

    e.preventDefault();
}
const removeTicket = key => {
    state.field.tickets.forEach((ticket, t) => {
        if (ticket.key == key) {
            state.field.tickets.splice(t, 1);
        }
    });
    renderCreatedTickets();
    localStorage.setItem('event_data', JSON.stringify(state.field));
}

const setSession = (el) => {
    let res = el.value;
    let session;
    state.field.sessions.forEach(s => {
        if(s.key == res){
            session = s;
        }
    })

    select("#ticket_start_date").value = "";
    select("#voucher_start").value = "";

    flatpickr("#ticket_start_date", {
        dateFormat: 'Y-m-d',
        maxDate: session.endSession.date,
        onChange: (selectedDate, dateStr) => {
            select("#ticket_end_date").value = "";
            flatpickr("#ticket_end_date", {
                dateFormat: 'Y-m-d',
                minDate: dateStr,
                maxDate: session.endSession.date
            });
        }
    });
    
    flatpickr("#voucher_start", {
        dateFormat: 'Y-m-d',
        maxDate: session.endSession.date,
        onChange: (selectedDate, dateStr) => {
            select("#voucher_end").value = "";
            flatpickr("#voucher_end", {
                dateFormat: 'Y-m-d',
                maxDate: session.endSession.date,
                minDate: dateStr
            });
        }
    });
}

