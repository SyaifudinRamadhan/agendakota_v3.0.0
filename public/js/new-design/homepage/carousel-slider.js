const slide = ( target, direct, value) =>  {
    let valScroll = direct == 'right' ? value : -1*(value)
    document.getElementById(target).scrollLeft += valScroll;
}

// Handling click button scroll
document.getElementById('nav-carousel-2-left').addEventListener('click', () =>{
    slide('carousel-recom-event', 'left', 374)
})
document.getElementById('nav-carousel-2-right').addEventListener('click', () =>{
    slide('carousel-recom-event', 'right', 374)
})

document.getElementById('nav-carousel-3-left').addEventListener('click', () =>{
    slide('carousel-city-event', 'left', 374)
})
document.getElementById('nav-carousel-3-right').addEventListener('click', () =>{
    slide('carousel-city-event', 'right', 374)
})

document.getElementById('nav-carousel-4-left').addEventListener('click', () =>{
    slide('carousel-time-event', 'left', 374)
})
document.getElementById('nav-carousel-4-right').addEventListener('click', () =>{
    slide('carousel-time-event', 'right', 374)
})

document.getElementById('nav-carousel-5-left').addEventListener('click', () =>{
    slide('carousel-fav-organizer', 'left', 100)
})
document.getElementById('nav-carousel-5-right').addEventListener('click', () =>{
    slide('carousel-fav-organizer', 'right', 100)
})