class ExploreTemplate {
    #JSONData;
    #target;
    // Parameter for loop showing data
    #counterShow = 0;
    #numShow = 0;

    constructor(dataEvents,dataPrices,targetID){
        this.#JSONData = {dataEvents, dataPrices};
        this.#target = targetID;
    }

    getTargetID(){
        return this.#target;
    }

    #templateView = '';
    #btnMore = 
            `<div id="load-more" class="col-md-12 no-pd mb-3 text-center mt-3">
                <button style="width: unset;" class="btn btn-primer btn-no-pd text-light rounded-5" onclick="loadMore();">
                    <i class="fas fa-refresh mr-2"></i> Load More
                </button>
            </div>`;

    _genTemplate(dataEvent, pricesData){
        if(this.#templateView == ''){
            if(dataEvent != null || dataEvent != undefined){
                let linkDetail = '';
                let imgPath = '';
                let eventName = '';
                let organizerName = '';
                let timeS = '';
                let timeE = '';
                let timeTxtS = '';
                let timeTxtE = '';
                let cityName = '';
                let price = '';

                const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov","Dec"];


                linkDetail = window.location.origin+'/event-detail/'+dataEvent.slug;
                imgPath = window.location.origin+'/storage/event_assets/'+dataEvent.slug+'/event_logo/thumbnail/'+dataEvent.logo;
                let nameImgLogo = '';
                if(dataEvent.organizer.logo == ''){
                    nameImgLogo = 'default_logo.png';
                }else{
                    nameImgLogo = dataEvent.organizer.logo;
                }
                let imgPathOrg = window.location.origin+'/storage/organization_logo/'+nameImgLogo;
                eventName = dataEvent.name;
                organizerName = dataEvent.organizer.name;
                timeS = new Date(dataEvent.start_date+' '+dataEvent.start_time);
                timeE = new Date(dataEvent.end_date+' '+dataEvent.end_time);
                // timeTxtS = timeS.getDate()+' '+monthNames[timeS.getMonth()]+' '+data.start_time;
                // timeTxtE = timeE.getDate()+' '+monthNames[timeE.getMonth()]+' '+data.end_time;
                timeTxtS = timeS.getDate()+' '+monthNames[timeS.getMonth()]+' '+timeS.getFullYear();

                cityName = dataEvent.city;
                organizerName = dataEvent.organizer.name;
                if(pricesData.length == 0){
                    price = 'Harga belum tersedia';
                }else{
                    if(pricesData.length >= 2){
                        if(pricesData[0] == 0 && pricesData[pricesData.length-1] == 0){
                            price = 'Gratis'
                        }else{
                            if(pricesData[0] == 0){
                                price = 'Gratis - '+formatRupiah(pricesData[pricesData.length-1],'Rp');
                            }else{
                                price = formatRupiah(pricesData[0],'Rp');
                            }
                        }
                    }else{
                        if(pricesData[0] == 0){
                            price =  'Gratis'
                        }else{
                            price = formatRupiah(pricesData[0],'Rp');
                        }
                    }
                }

                let template_1 = `<div class="col-md-4 no-pd">
                    <div class="wrap box-card">`+
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

                return template_1;
            }

            let template_2 = 
            `<div class="col-12 text-center mt-2">
                <h3 class="rata-tengah">Tidak ada event</h3>
                <div class="mt-2 rata-tengah">Coba ubah kategori atau lokasi lainnya</div>
            </div>`;
            return template_2;

        }else{
            return this.#templateView;
        }
    }

    _setCustomTemplate(strNewTemplate){
        this.#templateView = strNewTemplate;
    }

    setView(numShow = undefined, setBlank = false){
        if(numShow != undefined || numShow != null) this.#numShow = numShow;

        let limitLoop = this.#numShow+this.#counterShow;
        setBlank == true && this.#numShow != 0 ? document.getElementById(this.#target).innerHTML = '' : '';
        for (let i = this.#counterShow; i < limitLoop; i++) {
            if(i < this.#JSONData.dataEvents.length){
                document.getElementById(this.#target).innerHTML += this._genTemplate(this.#JSONData.dataEvents[i], this.#JSONData.dataPrices[i]);
                this.#counterShow += 1;
            }
        }

        if(this.#JSONData.dataEvents.length > 0){
            try {
                document.getElementById('load-more').remove();
            } catch (error) {
                console.log('load pertama');
            }
            document.getElementById(this.#target).innerHTML += this.#btnMore;
        }
        
        if(this.#JSONData.dataEvents.length <= this.#counterShow){
            try {
                document.querySelector('#load-more button').classList.add('disabled');
            } catch (error) {
                console.log('tidak dapat dihilangkan');
            }
        }

        if(this.#counterShow == 0){
            document.getElementById(this.#target).innerHTML += this._genTemplate();
        }
    }

    genNewData(url, objData){

        $.ajaxSetup({
            headers:{
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            }
        });
        $.ajax({
            url : url,
            dataType : 'JSON',
            method : "POST",
            data : objData,
            success : (data) => {
                console.log(data);
                this.#JSONData.dataEvents = data.events;
                this.#JSONData.dataPrices = data.eventPrices;
                // Reset counter show
                this.#counterShow = 0;
                // Reset View data
                document.getElementById(this.#target).innerHTML = '';
                // Running setView()
                this.setView(this.#numShow, true);
            }
        });
    }
}

// Class for skeleton loading template
class SkeletonTemplate extends ExploreTemplate{
    #numShow = 0;
    constructor(targetID){
        super(null,null, targetID);
    }

    #genTemplate(){
        super._setCustomTemplate(
            `<div class="col-md-4 mb-4">
                <div class="card">
                
                <div class="w-100 card-img-top bg-skeleton img-skeleton"></div>

                <div class="card-body">
                <div class="rounded-pill w-100 bg-skeleton title-skeleton"></div>
                    
                    <p class="card-text">
                        <div class="rounded-pill bg-skeleton mb-2 p1-skeleton"></div>
                        <div class="rounded-pill bg-skeleton mb-2 p2-skeleton"></div>
                        <div class="rounded-pill bg-skeleton mb-2 p3-skeleton"></div>
                        <div class="rounded-pill bg-skeleton mb-2 p3-skeleton"></div>
                    </p>
                    <div class="rounded-pill w-100 bg-skeleton btn-skeleton"></div>
                </div>
                </div>
            </div>`
        );
        return super._genTemplate();
    }

    genNewData(){
        return JSON.stringify({'status-code':404, 'err-code':'this class not support get new data'});
    }

    setView(numShow = undefined, setBlank = false){
        if(numShow != undefined || numShow != null) this.#numShow = numShow;
        setBlank == true && this.#numShow != 0 ? document.getElementById(super.getTargetID()).innerHTML = '' : '';
        for (let i = 0; i < this.#numShow; i++) {
            document.getElementById(super.getTargetID()).innerHTML += this.#genTemplate();
        }
    }
};

function formatRupiah(num, prefix) {
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

