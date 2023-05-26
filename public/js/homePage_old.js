var eventCategorys = [];
var eventCounter = 0;
let counterShow = 0;

// parameter dan data untuk memunculkan & paginasi load more event minggu ini
var counterShowWeek = 0;
var eventCounterWeek = 0;
var eventsWeek = [];

// Function untuk setup data event by category
function setDataEventByCategory(jsonData) {
    eventCategorys = jsonData;
}

// function untuk setup data event in this week / event minngu ini
function setDataEventThisWeek(jsonData) {
    eventsWeek = jsonData;
}

const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
];

function setView(data, category) {
    // style = '';
    linkDetail = '';
    imgPath = '';
    eventName = '';
    organizerName = '';
    timeS = '';
    timeE = '';
    timeTxtS = '';
    timeTxtE = '';
    if(data != null){
        // console.log(window.location);
        linkDetail = window.location.origin+'/event-detail/'+data.slug;
        imgPath = window.location.origin+'/storage/event_assets/'+data.slug+'/event_logo/thumbnail/'+data.logo;
        eventName = data.name;
        organizerName = data.organizer.name;
        timeS = new Date(data.start_date+' '+data.start_time);
        timeE = new Date(data.end_date+' '+data.end_time);
        timeTxtS = timeS.getDate()+' '+monthNames[timeS.getMonth()]+' '+data.start_time;
        timeTxtE = timeE.getDate()+' '+monthNames[timeE.getMonth()]+' '+data.end_time;
    }else{
        // console.log('Kosong', category);
    }

    var template_1 = 
    // '<div id="'+category+'" class="tabcontent-category" style="'+style+'">'+
        '<div class="bagi bagi-4">'+
            '<div class="wrap">'+
                '<a href="'+linkDetail+'" style="text-decoration: none; color: black;">'+
                '<div class="bg-putih rounded-5 bayangan-5">'+
                    `<div class="img-card-top" style="background-image: url('`+imgPath+`'); background-position: center center; background-size: cover;"></div>`+
                    '<h3 class="mt-2" style="margin-left: 5%; margin-right:5%; font-family: `Inter`, sans-serif !important; font-style: normal; color: #000000; font-size: 18px; font-weight: 500;">'+eventName+'</h3>'+
                    '<div class="mb-2" style="margin-left: 5%; margin-right:5%; font-family: `Inter`, sans-serif;color:#979797; font-size: 12px;">Diadakan oleh <span style="font-weight: bold">'+organizerName+'</span></div>'+
                '</a>'+
                    '<div class="mb-1 pb-3" style="margin-left: 5%; margin-right:5%; font-size: 12px; font-family: `Inter`, sans-serif !important; font-style: normal; font-weight: normal; color: #000000;">'+
                        '<i class="fa fa-calendar"></i> &nbsp;'+
                        timeTxtS+' WIB -'+
                        timeTxtE+' WIB'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</div>';
    // '</div>';
    // console.log(template_1);
    var template_2 = '<p class="teks-primer wrap  rata-tengah">Tidak Ada Event Untuk Kategori Ini</p>';

    // Insert HTML ke dalam tag div category event
    if(data != null){
        // console.log(template_1);
        document.getElementById(category).innerHTML += template_1;
    }else{
        document.getElementById(category).innerHTML += template_2;
    }
}

function eventByCategory(eventCategorys) {
    // Mengatasi tombol load mmore (jika data event sudah habis, reset counterShow)
    if(eventCounter == 0){
        counterShow = 0;
    }
    // console.log('counterShow : '+counterShow+', '+eventCounter);
    // ----------------------------------------------------------------------------

    // resset eventCounter -> untuk bisa menghitunng apakah loop event category berhasil
    eventCounter = 0;

    eventCategorys.forEach(entrie => {
        element = entrie[1];
        key = entrie[0];
        var counter = 0;
        // console.log(key, element);
        for (let index = counterShow; index < element.length; index++) {
            // Mengatasi supaya hanya muncul max 4 card
            if(counter <= 7){
                // console.log(counter, element[index]);
                const elementLocal = element[index];
                setView(elementLocal, key);
            }
            // -----------------------------------------
            counter += 1;
            eventCounter += 1;
        }
        if(counter == 0){
            setView(null, key);
        }
    });
    if(counterShow == 0){
        counterShow = 4;
    }else{
        counterShow += 4;
    }
}

function eventThisWeek(events) {
    var counterLocal = 0;
    var paidCounter = 0;
    var freeCounter = 0;
    
    // Mengatasi fitur load more ------------
    if(eventCounterWeek == 0){
        // Reset counter event week
        counterShowWeek = 0;
    }
    // --------------------------------------
    // console.log(events);
    // console.log('counterShowWeek : '+counterShowWeek+', '+eventCounterWeek);
    eventCounterWeek = 0;
    for (let index = counterShowWeek; index < events.length; index++){
        
        if(counterLocal <= 7){
            // pakai indeks [1] karena yang direima berupa konversi jsin ke array
            var data = events[index][1];
            var JumlahEvent = events.length;
            var arr = [];
            var total = "";
            // console.log(data);
            data.sessions.forEach(elementSession => {
                var session = elementSession;
                session.tickets.forEach(elementInner => {
                    arr.push(elementInner.price);
                });
            });

            var hargaTerendah = arr[0];
            var hargaTertinggi = arr[0];
            for(var i =0; i < arr.length; i++){
                if(arr[i] < hargaTerendah){
                    hargaTerendah = arr[i];
                }
                if(arr[i] >= hargaTertinggi){
                    hargaTertinggi = arr[i];
                }
            }

            var eventID = data.id;
            var linkDetail = window.location.origin+'/event-detail/'+data.slug;
            var imgPath = window.location.origin+'/storage/event_assets/'+data.slug+'/event_logo/thumbnail/'+data.logo;
            var eventName = data.name;
            var organizerName = data.organizer.name;
            var timeS = new Date(data.start_date+' '+data.start_time);
            var timeE = new Date(data.end_date+' '+data.end_time);
            var timeTxtS = timeS.getDate()+' '+monthNames[timeS.getMonth()]+' '+data.start_time;
            var timeTxtE = timeE.getDate()+' '+monthNames[timeE.getMonth()]+' '+data.end_time;
            var template_1 = 
            '<div class="bagi bagi-4">'+
                '<div class="wrap">'+
                    '<a href="'+linkDetail+'" style="text-decoration: none; color: black;">'+
                    '<div class="bg-putih rounded-5 bayangan-5">'+
                        `<div class="img-card-top" style="background-image: url('`+imgPath+`'); background-position: center center; background-size: cover;"></div>`+
                        '<h3 class="mt-2" style="margin-left: 5%; margin-right:5%; font-family: `Inter`, sans-serif !important; font-style: normal; color: #000000; font-size: 18px; font-weight: 500;">'+eventName+'</h3>'+
                        '<div class="mb-2" style="margin-left: 5%; margin-right:5%; font-family: `Inter`, sans-serif;color:#979797; font-size: 12px;">Diadakan oleh <span style="font-weight: bold">'+organizerName+'</span></div>'+
                    '</a>'+
                        '<div class="mb-1 pb-3" style="margin-left: 5%; margin-right:5%; font-size: 12px; font-family: `Inter`, sans-serif !important; font-style: normal; font-weight: normal; color: #000000;">'+
                            '<i class="fa fa-calendar"></i> &nbsp;'+
                            timeTxtS+' WIB -'+
                            timeTxtE+' WIB'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>';

            // Mengisi data event all
            document.getElementById('All-minggu-ini').innerHTML += template_1;
            
            if(hargaTertinggi > 0){
                document.getElementById('Paid-minggu-ini').innerHTML += template_1;
                paidCounter += 1;
            }else{
                document.getElementById('Free-minggu-ini').innerHTML += template_1;
                freeCounter += 1;
            }
        }
        counterLocal += 1;
        eventCounterWeek += 1;
    };

    var template_2 = '<p class="teks-primer wrap  rata-tengah">Tidak Ada Event Untuk Minggu Ini</p>';

    if(counterLocal == 0){
        document.getElementById('All-minggu-ini').innerHTML = template_2;
    }
    if(paidCounter == 0){
        document.getElementById('Paid-minggu-ini').innerHTML = template_2;
    }
    if(freeCounter == 0){
        document.getElementById('Free-minggu-ini').innerHTML = template_2;
    }

    if(counterShowWeek == 0){
        counterShowWeek = 4;
    }else{
        counterShowWeek += 4;
    }
}

function loadMore(eventCategorys) {
    eventCategorys.forEach(entries => {
        key = entries[0];
        document.getElementById(key).innerHTML = "";
    });
    eventByCategory(eventCategorys);
}

function loadMoreWeekEvent(eventsWeek) {
    document.getElementById('All-minggu-ini').innerHTML = '';
    document.getElementById('Paid-minggu-ini').innerHTML = '';
    document.getElementById('Free-minggu-ini').innerHTML = '';
    eventThisWeek(eventsWeek);
}

document.addEventListener('DOMContentLoaded', function () {
    // Load data event byCategory
    eventByCategory(Object.entries(eventCategorys));
    eventThisWeek(Object.entries(eventsWeek));
})

// Ketika tombol muat lagi di baris event minngu ini
document.getElementById('load-more-week-events').addEventListener('click', function () {
   loadMoreWeekEvent(Object.entries(eventsWeek)); 
});

// Ketika tombol muat lagi di klik pada baris event by category
document.getElementById('load-more-events-category').addEventListener('click', function () {
    loadMore(Object.entries(eventCategorys));
})