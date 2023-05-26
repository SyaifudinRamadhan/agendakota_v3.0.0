// identify an element to observe
const elementToObserve = document.querySelector("#msgBox");

// Basic setup
let stateShowProfile = false;
document.getElementById('profilelink').addEventListener('click', function(){
    !stateShowProfile ? document.getElementById('profile-menu').classList.remove('d-none') : document.getElementById('profile-menu').classList.add('d-none');

    stateShowProfile = !stateShowProfile;
});

let statePopUpChat = false;
document.getElementById('btn-chat-stream').addEventListener('click', function(){
    if( statePopUpChat == false){
        this.style.transform = 'translate(0px, 30px)';
        this.getElementsByTagName('i')[0].classList.remove('bi-chat-left-text');
        this.getElementsByTagName('i')[0].classList.add('bi-x-lg');
        document.getElementById('float-chat-stream').classList.remove('d-none');
        elementToObserve.childNodes.forEach(element => {
            element.classList.add('aos-animate');
        });
    }else{
        this.style.transform = 'translate(0px, 0px)';
        this.getElementsByTagName('i')[0].classList.add('bi-chat-left-text');
        this.getElementsByTagName('i')[0].classList.remove('bi-x-lg');
        document.getElementById('float-chat-stream').classList.add('d-none');
    }
    console.log(this.style.transform, statePopUpChat);
    statePopUpChat = !statePopUpChat;
});


try {
    document.getElementById('__vconsole').classList.add('d-none');
} catch (error) {
    
}


// Fix message cannot show automatically after send and resize window
// create a new instance of `MutationObserver` named `observer`,
// passing it a callback function
const observer = new MutationObserver(function() {
    elementToObserve.childNodes.forEach(element => {
        element.classList.add('aos-animate');
    });
});

// call `observe()` on that MutationObserver instance,
// passing it the element to observe, and the options object
observer.observe(elementToObserve, {subtree: true, childList: true});

window.addEventListener('resize', ()=>{
    statePopUpChat ? document.getElementById('btn-chat-stream').click() : '';
});

function setBannerLikeParent() {
    let parent = document.getElementById('parent-banner');
    document.getElementById('banner-img').style.height = parent.clientHeight;
}

window.addEventListener('DOMContentLoaded', ()=>{
    setTimeout(() => {
        document.getElementById('stream-row').style.display='unset';
    }, 300);
    setTimeout(() => {
        setBannerLikeParent();
    }, 1000);
});
window.addEventListener('resize', ()=>{
    setBannerLikeParent();
});


// Controlling side menu click
function setWhiteBg() {
    document.body.style.backgroundColor = '#fff';
}

function setDarkBg() {
    document.body.style.backgroundColor = '#262b2e';
}

function setDspNoneAll() {
    document.getElementById('home-stream').classList.add('d-none');
    document.getElementById('handbook-list').classList.add('d-none');
    document.getElementById('content-stream').classList.add('d-none');
    document.getElementById('connect-list').classList.add('d-none');
    document.getElementById('exh-list').classList.add('d-none');

    document.querySelectorAll('#drop-menu a').forEach(element => {
        try {
            element.classList.remove('active');
        } catch (error) {
            
        }
    });

    document.querySelectorAll('#side-bar li').forEach(element => {
        try {
            element.classList.remove('active');
        } catch (error) {
            
        }
    });

    showSidebar();

   setTimeout(() => {
        try {
            document.styleSheets[13].cssRules[13].style.removeProperty('overflow')
            document.styleSheets[13].cssRules[13].style.setProperty('overflow','auto');;
            console.log('ini diclose');
        } catch (error) {  }
   }, 500);

    document.getElementById('sidebar-show').classList.add('d-none');
    document.getElementById('sidebar-hidden').classList.add('d-none');
}

function showSection(targetID) {
    document.getElementById(targetID).classList.remove('d-none');
}

function setActive(targetClass){
    document.querySelectorAll(targetClass).forEach(elementIn => {
        elementIn.classList.add('active')
    });
}

function hiddSidebar(){
    document.getElementById('sidebar-hidden').classList.add('d-none');
    document.getElementById('sidebar-show').classList.remove('d-none');
    document.getElementById('side-bar').classList.add('d-none');
    document.getElementById('btn-chat-stream').classList.add('d-none');
    try {
        document.getElementById('btn-photo-booth').classList.add('d-none');
    } catch (error) {
        
    }
    statePopUpChat ? document.getElementById('btn-chat-stream').click() : '';
    document.getElementById('flag-pkg').classList.add('d-none');
}

function showSidebar(){
    setTimeout(() => {
        setBannerLikeParent();
    }, 100);
    document.getElementById('sidebar-show').classList.add('d-none');
    document.getElementById('sidebar-hidden').classList.remove('d-none');
    document.getElementById('side-bar').classList.remove('d-none');
    document.getElementById('btn-chat-stream').classList.remove('d-none');
    try {
        document.getElementById('btn-photo-booth').classList.remove('d-none');
    } catch (error) {
        
    }
    document.getElementById('flag-pkg').classList.remove('d-none');
}

// window.addEventListener('DOMContentLoaded', ()=>{
//     document.body.style.overflow = 'unset';
// });

document.querySelectorAll('.home-btn').forEach(element => {
    element.addEventListener('click', function () {
        setDspNoneAll();
        showSection('home-stream');
        setDarkBg();
    })
});

document.querySelectorAll('.stage-btn').forEach(element => {
    element.addEventListener('click', function () {
        setDspNoneAll();
        showSection('content-stream');
        setWhiteBg();
        
        setActive('.stage-btn');

        hiddSidebar();
    })
});

document.querySelectorAll('.connections-btn').forEach(element => {
    element.addEventListener('click', function(){
        setDspNoneAll();
        showSection('connect-list');
        setWhiteBg();
        this.classList.add('active');
        setActive('.connections-btn');
    })
});

document.querySelectorAll('.exhibitions-btn').forEach(element => {
    element.addEventListener('click', function(){
        setDspNoneAll();
        showSection('exh-list');
        setWhiteBg();
        this.classList.add('active');
        setActive('.exhibitions-btn');
    })
});

document.querySelectorAll('.handbooks-btn').forEach(element => {
    element.addEventListener('click', function(){
        setDspNoneAll();
        showSection('handbook-list');
        setWhiteBg();
        this.classList.add('active');
        setActive('.handbooks-btn');
    })
});