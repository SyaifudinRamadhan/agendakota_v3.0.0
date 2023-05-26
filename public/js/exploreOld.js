var eventExplore = [];
var eventExplorePrices = [];
var eventCounter = 0;
var counterShow = 0;

var btnMore = 
            `<div id="load-more" class="col-md-12 no-pd mb-3 text-center mt-3">
                <button style="width: unset;" class="btn btn-primer btn-no-pd text-light rounded-5" onclick="loadMore(Object.values(eventExplore), eventExplorePrices);">
                    <i class="fas fa-refresh mr-2"></i> Load More
                </button>
            </div>`;

// Function untuk setup data event by category
function setDataEventByCategory(jsonData, jsonData2) {
    eventExplore = jsonData;
    eventExplorePrices = jsonData2;
}

/* Fungsi formatRupiah */
function formatRupiah(num, prefix) {
    // var number_string = angka.toString().replace(/[^,\d]/g, "").toString(),
    //   split = number_string.split(","),
    //   sisa = split[0].length % 3,
    //   rupiah = split[0].substr(0, sisa),
    //   ribuan = split[0].substr(sisa).match(/\d{3}/gi);
  
    // // tambahkan titik jika yang di input sudah menjadi angka ribuan
    // if (ribuan) {
    //   separator = sisa ? "." : "";
    //   rupiah += separator + ribuan.join(".");
    // }
  
    // rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    // return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
    var strNum = num.toString().split('').reverse();
    var result = [];
    for (var i = 0; i <strNum.length; i++) {
        if(i%3 == 0 && i != 0){
            result.push('.');
            result.push(strNum[i]);
        }else result.push(strNum[i]);
    }
    var res= '';
    result.reverse().forEach(element=>{
        res += element;
    });
    return prefix +". "+ res;
  }
  

const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
];

function setView(data,pricesData,target) {
    // style = '';
    var linkDetail = '';
    var imgPath = '';
    var eventName = '';
    var organizerName = '';
    var timeS = '';
    var timeE = '';
    var timeTxtS = '';
    var timeTxtE = '';
    var cityName = '';
    var price = '';
    console.log(data);
    if(data != null){
        // console.log(window.location);
        var linkDetail = window.location.origin+'/event-detail/'+data.slug;
        var imgPath = window.location.origin+'/storage/event_assets/'+data.slug+'/event_logo/thumbnail/'+data.logo;
        var nameImgLogo = '';
        if(data.organizer.logo == ''){
            nameImgLogo = 'default_logo.png';
        }else{
            nameImgLogo = data.organizer.logo;
        }
        var imgPathOrg = window.location.origin+'/storage/organization_logo/'+nameImgLogo;
        eventName = data.name;
        organizerName = data.organizer.name;
        timeS = new Date(data.start_date+' '+data.start_time);
        timeE = new Date(data.end_date+' '+data.end_time);
        // timeTxtS = timeS.getDate()+' '+monthNames[timeS.getMonth()]+' '+data.start_time;
        // timeTxtE = timeE.getDate()+' '+monthNames[timeE.getMonth()]+' '+data.end_time;
        timeTxtS = timeS.getDate()+' '+monthNames[timeS.getMonth()]+' '+timeS.getFullYear();

        console.log(pricesData);
        cityName = data.city;
        organizerName = data.organizer.name;
        if(pricesData.length == 0){
            price = 'Harga belum tersedia';
        }else{
            if(pricesData.length >= 2){
                price = formatRupiah(pricesData[0],'Rp')+' - '+formatRupiah(pricesData[pricesData.length-1],'Rp');
            }else{
                price = formatRupiah(pricesData[0],'Rp');
            }
        }
        
    }else{
        // console.log('Kosong', category);
    }

    var template_1 = 
    `<div class="col-md-3 no-pd">
        <div class="wrap h-100 box-card">`+
            '<a class="h-100" href="'+linkDetail+'">'+
                `<div class="img-card-top" style="background-image: url('`+imgPath+`'); background-position: center center; background-size: cover;"></div>
                <div class="bg-putih smallPadding corner-bottom-left corner-bottom-right box-text">
                    <div class="wrap">
                        <h4 class="event-title mb-2">`+eventName+`</h4>
                        <div class="flex row wrap align-end">
                            <div class="lebar-100 mt-2">
                                <div class="teks-primer fas fa-calendar mr-2"></div>
                                `+timeTxtS+`
                            </div>
                            <div class="lebar-100 mt-1">
                                <div class="teks-primer fas fa-map-marker mr-2"></div>
                                `+cityName+`
                            </div>
                            <div class="lebar-100 mt-1">
                                <div class="teks-primer mr-2"></div>
                                `+price+`
                            </div>
                        </div>
                        <hr size="1" color="#f8f7f7" />
                        <div class="flex row wrap align-center">
                            <div class="organizer-logo bg-white" style="background-image: url('`+imgPathOrg+`'); background-position: center center; background-size: cover;"></div>
                            `+organizerName+`
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>`;
    var template_2 = 
        `<h3 class="rata-tengah">Tidak ada event</h3>
        <div class="mt-2 rata-tengah">Coba ubah kategori atau lokasi lainnya</div>`;

    // Insert HTML ke dalam tag div category event
    if(data != null){
        // console.log(template_1);
        document.getElementById(target).innerHTML += template_1;
    }else{
        document.getElementById(target).innerHTML += template_2;
    }
}

function eventByCategory(eventExplore, pricesEvent, showCards, targetID) {
    console.log('Ini dirunning lohhh')
    for (var index = counterShow; index < showCards; index++) {
        if(index < eventExplore.length){
            setView(eventExplore[index], pricesEvent[index], targetID);
            counterShow += 1;
            console.log('Ini dirunning lohhh')
        }
    }
    if(eventExplore.length > 0){
        try {
            document.getElementById('load-more').remove();
        } catch (error) {
            console.log('load pertama');
        }
        document.getElementById(targetID).innerHTML += btnMore;
    }
    if(counterShow >= eventExplore.length){
        try {
            document.querySelector('#load-more button').classList.add('disabled');
        } catch (error) {
            console.log('tidak dapat dihilangkan');
        }
    }
}

function loadMore(eventExplore, pricesEvent) {
    document.getElementById('event-show').innerHTML = '';
    eventByCategory(eventExplore, pricesEvent, 16+counterShow, 'event-show');
}

document.addEventListener('DOMContentLoaded', function () {
    loadMore(Object.values(eventExplore), eventExplorePrices);
})

// // Ketika tombol muat lagi di baris event minngu ini
// document.getElementById('load-more-week-events').addEventListener('click', function () {
//    loadMoreWeekEvent(Object.entries(eventsWeek)); 
// });