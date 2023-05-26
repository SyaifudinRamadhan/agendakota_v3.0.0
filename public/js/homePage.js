class TemplateCard{
    #targetView;
    #dataArray;
    constructor(targetView, dataArray){
        this.#targetView = targetView;
        this.#dataArray = dataArray;
    }

    countHighAndLowPrice(){
        let arr = [];
        this.#dataArray.sessions.forEach(elementSession => {
            let session = elementSession;
            session.tickets.forEach(elementInner => {
                arr.push(elementInner.price);
            });
        });

        let hargaTerendah = arr[0];
        let hargaTertinggi = arr[0];
        for(let i =0; i < arr.length; i++){
            if(arr[i] < hargaTerendah){
                hargaTerendah = arr[i];
            }
            if(arr[i] >= hargaTertinggi){
                hargaTertinggi = arr[i];
            }
        }

        return {'lower': hargaTerendah, 'higher': hargaTertinggi};
    }

    #templateView(linkDetail, imgPath, eventName, organizerName, timeTxtS, timeTxtE){
        let templateContent = `<div class="col-md-3 mb-4">
            <div>
                <a href="${linkDetail}" style="text-decoration: none; color: black;">
                <div class="bg-putih rounded-5 bayangan-5">
                    <div class="img-card-top" style="background-image: url(${"'"+imgPath+"'"}); background-position: center center; background-size: cover;"></div>
                    <h3 class="mt-2" style="margin-left: 5%; margin-right:5%; font-family: Inter, sans-serif !important; font-style: normal; color: #000000; font-size: 18px; font-weight: 500; height: 43.20px; overflow: hidden; text-overflow: ellipsis;">${eventName}</h3>
                    <div class="mb-2" style="margin-left: 5%; margin-right:5%; font-family: Inter, sans-serif;color:#979797; font-size: 12px;">Diadakan oleh <span style="font-weight: bold">${organizerName}</span></div>
                </a>
                    <div class="mb-1 pb-3" style="margin-left: 5%; margin-right:5%; font-size: 11px; font-family: Inter, sans-serif !important; font-style: normal; font-weight: normal; color: #000000;">
                        <i class="fa fa-calendar"></i> &nbsp;
                        ${timeTxtS} WIB -
                        ${timeTxtE} WIB
                    </div>
                </div>
            </div>
        </div>`;
        
        let templateBlank = `<p class="teks-primer wrap  rata-tengah w-100">Tidak Ada Event Untuk Kategori Ini</p>`;
        return linkDetail == undefined || imgPath == undefined || eventName == undefined || organizerName == undefined || timeTxtS == undefined || timeTxtE == undefined ? templateBlank : templateContent;
    }

    setView(targetID){
        let target = targetID == undefined ? this.#targetView : targetID; 
        
        if(this.#dataArray != null){
            let linkDetail = window.location.origin+'/event-detail/'+this.#dataArray.slug;
            let imgPath = window.location.origin+'/storage/event_assets/'+this.#dataArray.slug+'/event_logo/thumbnail/'+this.#dataArray.logo;
            let eventName = this.#dataArray.name;
            let organizerName = this.#dataArray.organizer.name;
            let timeS = new Date(this.#dataArray.start_date+' '+this.#dataArray.start_time);
            let timeE = new Date(this.#dataArray.end_date+' '+this.#dataArray.end_time);
            let timeTxtS = timeS.getDate()+' '+monthNames[timeS.getMonth()]+' '+this.#dataArray.start_time;
            let timeTxtE = timeE.getDate()+' '+monthNames[timeE.getMonth()]+' '+this.#dataArray.end_time;

            document.getElementById(target).innerHTML += this.#templateView(linkDetail, imgPath, eventName, organizerName, timeTxtS, timeTxtE);
            
        }else{
            document.getElementById(target).innerHTML += this.#templateView();
        }
    }
}

function eventThisWeek(events) {
    let counterLocal = 0;
    let paidCounter = 0;
    let freeCounter = 0;
    
    // Mengatasi fitur load more ------------
    let counterShowWeek = 0;
    let eventCounterWeek = 0;
  
    // --------------------------------------
    for (let index = counterShowWeek; index < events.length; index++){
        
        if(counterLocal <= 7){
            
            let templateObj = new TemplateCard('All-minggu-ini', events[index][1]);
            let hargaTertinggi = templateObj.countHighAndLowPrice().higher;

            // Mengisi data event all
            templateObj.setView();
            
            if(hargaTertinggi > 0){
                templateObj.setView('Paid-minggu-ini');
                paidCounter += 1;
            }else{
                templateObj.setView('Free-minggu-ini');
                freeCounter += 1;
            }
        }
        counterLocal += 1;
        eventCounterWeek += 1;
    };

    let template_2 = '<p class="teks-primer wrap  rata-tengah w-100">Tidak Ada Event Untuk Minggu Ini</p>';

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

function eventByCategory(eventCategorys) {
    // Mengatasi tombol load mmore (jika data event sudah habis, reset counterShow)
    let counterShow = 0;
    let eventCounter;
    // console.log('counterShow : '+counterShow+', '+eventCounter);
    // ----------------------------------------------------------------------------

    // resset eventCounter -> untuk bisa menghitunng apakah loop event category berhasil
    eventCounter = 0;

    eventCategorys.forEach(entrie => {
        element = entrie[1];
        key = entrie[0];
        let counter = 0;
        // console.log(key, element);
        for (let index = counterShow; index < element.length; index++) {
            // Mengatasi supaya hanya muncul max 4 card
            
            if(counter <= 7){
                let templateObj = new TemplateCard(key, element[index]);
                templateObj.setView()
            }
            // -----------------------------------------
            counter += 1;
            eventCounter += 1;
        }
        if(counter == 0){
            let templateObj = new TemplateCard(key, null);
            templateObj.setView()
        }
    });
    if(counterShow == 0){
        counterShow = 4;
    }else{
        counterShow += 4;
    }
}

// --------------------------------------------------------------------
// Function untuk setup data event by category
let eventCategorys;
let eventsWeek;
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
    console.log('running')
    eventByCategory(Object.entries(eventCategorys));
    eventThisWeek(Object.entries(eventsWeek));
})

// // Ketika tombol muat lagi di baris event minngu ini
// document.getElementById('load-more-week-events').addEventListener('click', function () {
//    loadMoreWeekEvent(Object.entries(eventsWeek)); 
// });

// // Ketika tombol muat lagi di klik pada baris event by category
// document.getElementById('load-more-events-category').addEventListener('click', function () {
//     loadMore(Object.entries(eventCategorys));
// })

function opentabs(evt, jenis, kategori) {
    console.log('iki dieksekusi')
    var i, tabcontent, tablinks, tabContentName, tabLinkName;
    tabContentName = "tabcontent-"+jenis;
    tabcontent = document.getElementsByClassName(tabContentName);
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    tabLinkName = "tablinks-" + jenis;
    tablinks = document.getElementsByClassName(tabLinkName);
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    document.getElementById(kategori).style.display = "flex";
    try {
        evt.currentTarget.className += " active";
    } catch (error) {
        console.log(error);
    }
}