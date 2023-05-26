let locationArea = document.querySelector("#location");
if (locationArea !== null) {
    ClassicEditor.create(document.querySelector("#location")).then((editor) => {
        editor.isReadOnly = true; // make the editor read-only right after initialization
    });
}

const getSelectedTicket = () => {
    let selectTicket = [];
    selectAll(".pilih-ticket").forEach((ticket) => {
        if (ticket.classList.contains("ticket-list")) {
            let ticketId = ticket.getAttribute("ticket-id");
            ticket.checked = true;
            ticket.classList.add("active");
        } else {
            ticket.classList.remove("active");
        }
    });
};
selectAll(".pilih-ticket").forEach((ticket) => {
    ticket.addEventListener("click", (e) => {
        let target = e.currentTarget;
        target.classList.add("ticket-list");
        let ticketId = ticket.getAttribute("ticket-id");
        getSelectedTicket();
        target.classList.remove("ticket-list");
    });
});

const getSelectedBreakdown = () => {
    let selectedBreakdown = [];
    selectAll(".breakdowns").forEach((breakdown) => {
        if (breakdown.classList.contains("active")) {
            let breakdownType = breakdown.getAttribute("breakdown-type");
            selectedBreakdown.push(breakdownType);
            document.getElementById(breakdownType).checked = true;
            document.getElementById("pilih-jenis-tiket").disabled = false;
        }
    });
};

selectAll(".breakdowns").forEach((breakdown) => {
    breakdown.addEventListener("click", (e) => {
        let target = e.currentTarget;
        target.classList.toggle("active");
        let breakdownType = breakdown.getAttribute("breakdown-type");
        document.getElementById(breakdownType).checked = false;
        document.getElementById("pilih-jenis-tiket").disabled = true;
        getSelectedBreakdown();
    });
});

function copyLinkEvent() {
    const elem = document.createElement("textarea");
    elem.value = window.location.href;
    document.body.appendChild(elem);
    elem.select();
    document.execCommand("copy");
    document.body.removeChild(elem);
    // alert("URL Telah Berhasil Di Copy");
    Swal.fire({
        title: "Berhasil !!!",
        text: "Link telah dicopy",
        icon: "success",
        confirmButtonText: "Ok",
    });
}


// ------------------------ Button buy ticket click event ------------------------------------
// Update 10 June 2022
var buyBtn = document.getElementById('buy-ticket');

buyBtn.addEventListener('click', function () {
    var tabTicketDsp = document.getElementById('Tickets').style.display;
    var ticketChecked = document.querySelectorAll('input[type="checkbox"]:checked').length;
    
    if(tabTicketDsp == 'none'){
        document.getElementById('tab-tickets').click();
        document.body.scrollTop = 500;
        document.documentElement.scrollTop = 500;
    }else if(ticketChecked == 0){
        Swal.fire({
            title: "Gagal !!!",
            text: "Setidaknya pilih satu tiket",
            icon: "error",
            confirmButtonText: "Ok",
        });
    }else{
        document.getElementById('form-buy-tickets').submit();
    }
});

// -------------------------------------------------------------------------------------------