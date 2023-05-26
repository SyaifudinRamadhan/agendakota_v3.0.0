// Abstract class
class TemplateView {
    #JSONData;
    constructor(JSONData, targetDOM) {
        this.#JSONData = JSONData;
        this.target = targetDOM;
    }

    setNewJson(url, childObj, paramData) {
        // Metode XHTTPRequest
        const xhttp = new XMLHttpRequest();
        xhttp.onload = () => {
            this.#setJSONData(JSON.parse(xhttp.response));
            console.log(JSON.parse(xhttp.response), 'ini hasil response');
            console.log(typeof (functionExp));
            childObj.setView();
        }
        xhttp.open('POST', url);
        xhttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'))
        xhttp.setRequestHeader('Content-Type', 'application/json');
        if (paramData == undefined || paramData == '') {
            xhttp.send()
        } else {
            xhttp.send(JSON.stringify(paramData))
        }
    }

    _setTemplate(tempateString) {
        this.tempateString = tempateString;
    }

    setView() {
        document.getElementById(this.target).innerHTML += this.tempateString;
    }

    setBlankView() {
        document.getElementById(this.target).innerHTML = '';
    }

    #setJSONData(newData) {
        this.#JSONData = newData;
    }
    getJSONData() {
        return this.#JSONData;
    }
}

// child class / polymorpishm
class SkeletonTemplate extends TemplateView {
    #strTemplate = '';

    constructor(targetDOM) {
        super([{}], targetDOM);
    };

    use(partName) {
        if (partName === 'top-image') {
            this.#strTemplate = ` <div class="col-5">
                <div class="card card__mod text-bg-primary mb-3" style="max-width: 18rem">
                <div class="img-5-2-loading"></div>
                </div>
            </div>`
        } else if (partName === 'card') {
            this.#strTemplate = `<div class="card ms-4" style="width: 350px;">
                <div class="img-5-2-loading img-card-loading"></div>
                <div class="card-body">
                    <div class="text-2-loading mb-2"></div>
                    <div class="text-3-loading mb-2"></div>
                    <div class="text-1-loading w-50"></div>
                    <hr>
                    <div class="d-flex">
                        <div class="text-3-loading circle-icon-loading-1 my-auto me-2"></div>
                        <div class="text-1-loading w-50 my-auto"></div>
                    </div>
                </div>
            </div>`
        } else if (partName === 'img-banner') {
            this.#strTemplate = `<div class="carousel-item">
                <div class="img-4-1-loading"></div>
            </div>`
        } else if (partName === 'profile-card') {
            this.#strTemplate = ` <div class="p-3 org-fav-box" style="min-width: 100px">
                <div>
                    <div class="img-1-1-loading circle-icon-loading-relative"></div>
                    <div class="text-2-loading mt-2"></div>
                </div>
            </div>`
        } else if (partName === 'square') {
            this.#strTemplate = `<div class="col-md-2 mb-4 img-category">
                <div class="img-1-1-loading rounded-4"></div>
            </div>`
        } else if (partName === 'pill') {
            this.#strTemplate = `<div class="btn-pill-100-px-loading m-2"></div>`
        }
    }

    genTemplate() {
        super._setTemplate(this.#strTemplate);
    }

    setNewJson() {
        console.log('This not using data anymore. Is only skeleton view');
    }

    setView(num) {
        for (let i = 0; i < num; i++) {
            super.setView();
        }
    }
}

// class ContentTemplate extends TemplateView {

//     constructor(JSONData, targetDOM) {
//         super(JSONData, targetDOM);
//     };

//     #strTemplate;

//     #genTemplate(objData) {
//         this.#strTemplate = strTempalate(objData.manufacture, objData.type, objData.image, objData.rentPerDay, objData.description, objData.transmission, objData.capacity, objData.year);
//         super._setTemplate(this.#strTemplate);
//     }

//     setView() {
//         console.log('ini dijalnakan lohh');
//         this.setBlankView();
//         this.getJSONData().forEach(element => {
//             this.#genTemplate(element);
//             super.setView();
//         });
//     }
// }

class TopImageTemplate extends TemplateView {

    #fnSetCountCard;

    constructor(JSONData, targetDOM, fnSetCountCard) {
        super(JSONData, targetDOM);
        this.#fnSetCountCard = fnSetCountCard
    };

    #strTemplate;

    #strTempalate(slug, logo) {
        return `<div class="col-5">
        <a href="${window.location.origin}/event-detail/${slug}">
            <div class="card card__mod text-bg-primary mb-3" style="max-width: 18rem">
                <img src="${window.location.origin}/storage/event_assets/${slug}/event_logo/thumbnail/${logo}"
                    alt="">
            </div>
        </a>
        </div>`;
    }

    #genTemplate(objData) {
        this.#strTemplate = this.#strTempalate(objData.slug, objData.logo);
        super._setTemplate(this.#strTemplate);
    }

    setView() {
        console.log('ini dijalnakan lohh');
        this.setBlankView();
        console.log(this.getJSONData());
        this.getJSONData().events.forEach(element => {
            this.#genTemplate(element);
            super.setView();
        });
        this.#fnSetCountCard()
    }
}

class CardTemplate extends TemplateView {
    constructor(targetDOM) {
        super([{}], targetDOM)
    }

    #strTemplate

    #strTempalate(name, slug, logo, start_date, end_date, sessions, organizer) {
        const months = ["Jan", "Fed", "Mar", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        let startDate = new Date(start_date)
        let endDate = new Date(end_date)
        return `<a href="${window.location.origin}/event-detail/${slug}" class="text-dark me-4">
            <div class="card" style="width: 350px;">
                <img src="${window.location.origin}/storage/event_assets/${slug}/event_logo/thumbnail/${logo}"
                    class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">
                        ${name}
                    </h5>
                    <p class="card-text">
                        ${startDate.getDate()+' '+months[startDate.getMonth()]+' - '+endDate.getDate()+' '+months[endDate.getMonth()]+' '+endDate.getFullYear()}
                    </p>
                    <div class="card-price">
                        ${sessions.length > 0 ? (sessions[0].cheapest_ticket.length > 0 ? (sessions[0].cheapest_ticket[0].price == 0 ? 'Mulai dari  Gratis' : 'Mulai dari Rp. ' + currencyEncode(sessions[0].cheapest_ticket[0].price)) : 'Tiket Belum Tersedia') : 'Tiket Belum Tersedia'}
                    </div>
                    <hr>
                    <div class="d-flex">
                        <img src="${window.location.origin}/storage/organization_logo/${organizer.logo == '' ? 'default_logo.png' : organizer.logo}" alt="" width="35px"
                            height="35px" class="rounded-circle my-auto">
                        <p class="card-footer my-auto">
                            ${organizer.name}
                        </p>
                    </div>
                </div>
            </div>
        </a>`
    }

    #genTemplate(objData) {
        this.#strTemplate = this.#strTempalate(objData.name, objData.slug, objData.logo, objData.start_date, objData.end_date, objData.sessions, objData.organizer);
        super._setTemplate(this.#strTemplate);
    }

    #genBlankTemplate() {
        this.#strTemplate = `<div class="w-100 text-center color-origin fs-3">Event Belum Tersedia</div>`
        super._setTemplate(this.#strTemplate);
    }

    setView() {
        console.log('ini dijalnakan lohh');
        this.setBlankView();
        console.log(this.getJSONData().events);
        if (this.getJSONData().events === undefined || this.getJSONData().events === null) {
            this.#genBlankTemplate()
            super.setView()
        } else if (this.getJSONData().events.length === 0) {
            this.#genBlankTemplate()
            super.setView()
        } else {
            this.getJSONData().events.forEach(element => {
                this.#genTemplate(element);
                super.setView();
            });
        }
    }
}

class BoxTemplate extends TemplateView {
    constructor(targetDOM) {
        super([{}], targetDOM)
    }

    #strTemplate

    #strTempalate(name, image) {
        return `<div class="col-md-2 mb-4 img-category">
            <a href='/explore?category=${name}' class="text-dark">
                <img class="rounded-3" src="${window.location.origin}/images/category/${image}" width="100%"
                    alt="">
                <div class="title-category">
                    <div class="inner rounded-3 d-flex">
                        <p class="text-light text-center w-100 mt-auto mb-2">
                            ${name}
                        </p>
                    </div>
                </div>
            </a>
        </div>`
    }

    #genTemplate(objData) {
        this.#strTemplate = this.#strTempalate(objData.name, objData.photo);
        super._setTemplate(this.#strTemplate);
    }

    setView() {
        console.log('ini dijalnakan lohh');
        this.setBlankView();
        console.log(this.getJSONData());
        Object.values(this.getJSONData().categorys).forEach(value => {
            this.#genTemplate(value);
            super.setView();
        })
    }
}

class PillTemplate extends TemplateView {
    constructor(targetDOM) {
        super([{}], targetDOM)
    }

    #strTemplate

    #strTempalate(name) {
        return `<a href="/explore?topic=${name}" class="btn btn-white btn-category rounded-pill shadow-lg m-2">${name}</a>`
    }

    #genTemplate(objData) {
        this.#strTemplate = this.#strTempalate(objData);
        super._setTemplate(this.#strTemplate);
    }

    setView() {
        console.log('ini dijalnakan lohh');
        this.setBlankView();
        console.log(this.getJSONData());
        this.getJSONData().topics.forEach(item => {
            this.#genTemplate(item);
            super.setView();
        })
    }
}

class BannerTemplate extends TemplateView {
    constructor(targetDOM) {
        super([{}], targetDOM)
    }

    #strTemplate

    #strTempalate(url, image) {
        return `<div class="carousel-item">
        <a href="${url}">
            <img src="${window.location.origin}/storage/banner_image/${image}"
                class="d-block w-100" alt="...">
        </a>
        </div>`
    }

    #genTemplate(objData) {
        this.#strTemplate = this.#strTempalate(objData.url, objData.image);
        super._setTemplate(this.#strTemplate);
    }

    setView() {
        console.log('ini dijalnakan lohh');
        this.setBlankView();
        console.log(this.getJSONData());
        if (this.getJSONData().banners.length === 0) {
            document.getElementById(super.target).classList.add('d-none')
        } else {
            this.getJSONData().banners.forEach(item => {
                this.#genTemplate(item);
                super.setView();
            })
            document.querySelector('.carousel-item').classList.add('active')
        }
    }
}

class OrganizerTemplate extends TemplateView {
    constructor(targetDOM) {
        super([{}], targetDOM)
    }

    #strTemplate

    #strTempalate(slug, name, logo) {
        return `<div class="p-3 org-fav-box">
        <a href="/organization-detail/${slug}">
            <img width="100%" src="${window.location.origin}/storage/organization_logo/${logo == '' ? 'default_logo.png' : logo}" alt=""
                class="rounded-circle">
            <p class="mt-2 text-dark">${name}</p>
        </a>
        </div>`
    }

    #genTemplate(objData) {
        this.#strTemplate = this.#strTempalate(objData.slug, objData.name, objData.logo);
        super._setTemplate(this.#strTemplate);
    }

    setView() {
        console.log('ini dijalnakan lohh');
        this.setBlankView();
        console.log(this.getJSONData());
        this.getJSONData().organizers.forEach(item => {
            this.#genTemplate(item);
            super.setView();
        })
    }
}
// // ------------------------- Templating image top ------------------------------------
// `<div class="col-5">
// <a href="">
//     <div class="card card__mod text-bg-primary mb-3" style="max-width: 18rem">
//         <img src="{{ asset('storage/event_assets/festival-jazz-ubud-202222-09-22_09-24-52_11/event_logo/thumbnail/20220428205810_626a9d72ce91e.jpg') }}"
//             alt="">
//     </div>
// </a>
// </div>`
// // ----------------------- Templating card ------------------------------------------
// `<div class="card" style="width: 350px;">
// <img src="{{ asset('storage/event_assets/festival-jazz-ubud-202222-09-22_09-24-52_11/event_logo/thumbnail/20220428205810_626a9d72ce91e.jpg') }}"
//     class="card-img-top" alt="...">
// <div class="card-body">
//     <h5 class="card-title">Card title</h5>
//     <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
//         card's content.</p>
//     <div class="card-price">
//         Rp. !5.000,-
//     </div>
//     <hr>
//     <div class="d-flex">
//         <img src="{{ asset('storage/organization_logo/default_logo.png') }}" alt="" width="35px"
//             height="35px" class="rounded-circle my-auto">
//         <p class="card-footer my-auto">Organisasi</p>
//     </div>
// </div>
// </div>`
// // ------------------------------ Templating square ---------------------------------
// `<div class="col-md-2 mb-4 img-category">
// <img class="rounded-3" src="{{ asset('images/category/Exhibition.jpg') }}" width="100%"
//     alt="">
// <div class="title-category">
//     <div class="inner rounded-3 d-flex">
//         <p class="text-light text-center w-100 mt-auto mb-2">
//             Exhibitions
//         </p>
//     </div>
// </div>
// </div>`
// // ------------------------------ Templating pill -----------------------------------
// `<a href="" class="btn btn-white btn-category rounded-pill shadow-lg m-2">Anak, Keluarga</a>`
// // ------------------------------ Templating img banner -----------------------------
// `<div class="carousel-item active">
// <a href="">
//     <img src="{{ asset('storage/event_assets/festival-jazz-ubud-202222-09-22_09-24-52_11/event_logo/thumbnail/20220428205810_626a9d72ce91e.jpg') }}"
//         class="d-block w-100" alt="...">
// </a>
// </div>`
// // ----------------------------- Templating img profil ------------------------------
// `<div class="p-3 org-fav-box">
// <a href="">
//     <img width="100%" src="{{ asset('storage/organization_logo/default_logo.png') }}" alt=""
//         class="rounded-circle">
//     <p class="mt-2 text-dark">Orgamisasi Wong Apik</p>
// </a>
// </div>`

const currencyEncode = num => {
    let strNum = num.toString().split('').reverse();
    let result = [];
    for (let i = 0; i < strNum.length; i++) {
        if (i % 3 == 0 && i != 0) {
            result.push('.');
            result.push(strNum[i]);
        } else result.push(strNum[i]);
    }
    let res = '';
    result.reverse().forEach(element => {
        res += element;
    });
    return res;
}
