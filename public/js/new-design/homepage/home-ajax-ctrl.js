// ----------------------- Handling action button basic ----------------------
let stateCitiesBox = false;
let stateTimeBox = false;

document.getElementById('select-city').addEventListener('click', () => {
    stateCitiesBox ? (document.getElementById('cities-box').classList.add('d-none')) : (document.getElementById('cities-box').classList.remove('d-none'))
    stateCitiesBox ? stateCitiesBox = false : stateCitiesBox = true
})
document.getElementById('btn-select-city').addEventListener('click', () => {
    stateCitiesBox ? (document.getElementById('cities-box').classList.add('d-none')) : (document.getElementById('cities-box').classList.remove('d-none'))
    stateCitiesBox ? stateCitiesBox = false : stateCitiesBox = true
})

document.getElementById('select-time').addEventListener('click', () => {
    stateTimeBox ? (document.getElementById('times-box').classList.add('d-none')) : (document.getElementById('times-box').classList.remove('d-none'))
    stateTimeBox ? stateTimeBox = false : stateTimeBox = true
})
document.getElementById('btn-select-time').addEventListener('click', () => {
    stateTimeBox ? (document.getElementById('times-box').classList.add('d-none')) : (document.getElementById('times-box').classList.remove('d-none'))
    stateTimeBox ? stateTimeBox = false : stateTimeBox = true
})

// Handle close all pop up div
window.onclick = function (event) {
    // lst variable div pop up
    let popUp1 = document.getElementById('cities-box')
    let popUp2 = document.getElementById('times-box')

    if (event.target.contains(popUp1) && event.target !== popUp1) {
        popUp1.classList.add('d-none')
        stateCitiesBox = false
    }
    if (event.target.contains(popUp2) && event.target !== popUp2) {
        popUp2.classList.add('d-none')
        stateTimeBox = false
    }
}

// ---------------------------------------------------------------------------

// ---------------------- controller show data ---------------------------
let cardByCity = undefined
let cardByTime = undefined

window.addEventListener('load', () => {
    // top image
    let skeletonTemplateTopImg = new SkeletonTemplate('row-carousel')
    skeletonTemplateTopImg.use('top-image')
    skeletonTemplateTopImg.genTemplate()
    skeletonTemplateTopImg.setView(5)
    setNewCountCard()

    //card rekom
    let skeletonTemplateCardRecom = new SkeletonTemplate('carousel-recom-event')
    skeletonTemplateCardRecom.use('card')
    skeletonTemplateCardRecom.genTemplate()
    skeletonTemplateCardRecom.setView(5)

    // box category
    let skeletonTemplateBox = new SkeletonTemplate('category-box-inner')
    skeletonTemplateBox.use('square')
    skeletonTemplateBox.genTemplate()
    skeletonTemplateBox.setView(6)

    // box category pill
    let skeletonTemplatePill = new SkeletonTemplate('type-box')
    skeletonTemplatePill.use('pill')
    skeletonTemplatePill.genTemplate()
    skeletonTemplatePill.setView(8)

    //card rekom-2
    let skeletonTemplateCardRecom2 = new SkeletonTemplate('carousel-city-event')
    skeletonTemplateCardRecom2.use('card')
    skeletonTemplateCardRecom2.genTemplate()
    skeletonTemplateCardRecom2.setView(5)

    //card rekom-3
    let skeletonTemplateCardRecom3 = new SkeletonTemplate('carousel-time-event')
    skeletonTemplateCardRecom3.use('card')
    skeletonTemplateCardRecom3.genTemplate()
    skeletonTemplateCardRecom3.setView(5)

    //img banner
    let skeletonImgBanner = new SkeletonTemplate('banner-images')
    skeletonImgBanner.use('img-banner')
    skeletonImgBanner.genTemplate()
    skeletonImgBanner.setView(5)

    //profil card
    let skeletonProfil = new SkeletonTemplate('carousel-fav-organizer')
    skeletonProfil.use('profile-card')
    skeletonProfil.genTemplate()
    skeletonProfil.setView(10)


    //----------------------- fillling skeleton with data -----------------
    let topImg = new TopImageTemplate([{}], 'row-carousel', setNewCountCard)
    topImg.setNewJson(`${window.location.origin}/api/event/featured`, topImg)

    let cardRecom = new CardTemplate('carousel-recom-event')
    cardRecom.setNewJson(`${window.location.origin}/api/event/recommendation`, cardRecom)

    let square = new BoxTemplate('category-box-inner')
    square.setNewJson(`${window.location.origin}/api/event/cat-list`, square)

    let pill = new PillTemplate('type-box')
    pill.setNewJson(`${window.location.origin}/api/event/topic-list`, pill)

    const xhttp = new XMLHttpRequest();
    xhttp.onload = () => {
        // console.log(JSON.parse(xhttp.response), 'ini hasil response');
        let cities = JSON.parse(xhttp.response).cities
        document.getElementById('select-city').innerHTML = cities[0].name
        for (let index = 0; index < cities.length; index++) {
            document.getElementById('list-city').innerHTML += `<p class="mb-0 border-bottom border-dark-subtle pointer" onclick="changeCity('${cities[index].name}')">${cities[index].name}</p>`
        }

        cardByCity = new CardTemplate('carousel-city-event')
        cardByCity.setNewJson(`${window.location.origin}/api/event/by-city`, cardByCity, {"city": cities[0].name})
    }
    xhttp.open('POST', window.location.origin + '/api/event/city-list');
    xhttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'))
    xhttp.setRequestHeader('Content-Type', 'application/json');
    xhttp.send();

    cardByTime = new CardTemplate('carousel-time-event')
    cardByTime.setNewJson(`${window.location.origin}/api/event/by-time`, cardByTime, {"timeframe": 'Hari Ini'})

    let banner = new BannerTemplate('banner-images')
    banner.setNewJson(`${window.location.origin}/api/event/banners`, banner)

    let favOrg = new OrganizerTemplate('carousel-fav-organizer')
    favOrg.setNewJson(`${window.location.origin}/api/event/favorite-organizer`, favOrg)
    // --------------------- Show btn navigation --------------------------
    document.querySelectorAll('.col-nav-carousel').forEach(item => {
        item.classList.remove('d-none')
    })
    document.querySelectorAll('.col-nav-carousel-2').forEach(item => {
        item.classList.remove('d-none')
    })

})

const handleChange = (event, fn) => {
    fn(event.target.value)
}

const changeCity = (city) => {
    console.log(city)
    let popUp1 = document.getElementById('cities-box')
    popUp1.classList.add('d-none')
    stateCitiesBox = false
    cardByCity.setBlankView()
    document.getElementById('select-city').innerHTML = city
    let skeletonTemplateCardRecom2 = new SkeletonTemplate('carousel-city-event')
    skeletonTemplateCardRecom2.use('card')
    skeletonTemplateCardRecom2.genTemplate()
    skeletonTemplateCardRecom2.setView(5)
    cardByCity.setNewJson(`${window.location.origin}/api/event/by-city`, cardByCity, {"city": city})
}

const changeTime = (time) => {
    console.log(time)
    let popUp1 = document.getElementById('times-box')
    popUp1.classList.add('d-none')
    stateTimeBox = false
    cardByTime.setBlankView()
    document.getElementById('select-time').innerHTML = time
    let skeletonTemplateCardRecom2 = new SkeletonTemplate('carousel-time-event')
    skeletonTemplateCardRecom2.use('card')
    skeletonTemplateCardRecom2.genTemplate()
    skeletonTemplateCardRecom2.setView(5)
    cardByTime.setNewJson(`${window.location.origin}/api/event/by-time`, cardByTime, {"timeframe": time})
}