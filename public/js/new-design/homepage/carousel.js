var defaultMargin = 0;
var firstMargin = 1;
var different = -42;
var currentMargin = firstMargin;
var index = 0;

var rowCarousel = document.getElementById('row-carousel');
let cards = document.querySelectorAll('#row-carousel .card');
var countCard = cards.length;

// NB : Default move to left

// arithmetic sequence function
function setNewCountCard(){
    cards = document.querySelectorAll('#row-carousel .card')
    countCard = cards.length
}

function arithmeticSequence(a, b, n) {
    // a = first tribe
    // b = different
    // n = index
    var Un = a + ((n-1)*b);
    console.log(a+" "+b+" "+n+" "+Un);
    return Un;
}

function left(indexCustom = null) {
    var innerIndex = index;
    if(index + 1 < countCard){
        innerIndex++;
    }
    if(indexCustom != null){
        innerIndex = indexCustom;
        console.log('index custom');
    }
    var currentMargin = arithmeticSequence(firstMargin, different, innerIndex+1);
    rowCarousel.style.transform = "translate("+currentMargin+"% ,0px)";
    // if index = count card carousel
    if(index + 1 == countCard || innerIndex == 0){
        rowCarousel.style.transform = "translate("+defaultMargin+"% ,0px)";
    }

    if(index + 1 < countCard){
        index++;
    }else if(index + 1 == countCard){
        index = 0;
    }

    if(indexCustom != null){
        index = indexCustom;
    }
}

function right(indexCustom = null) {
    let innerIndex = index;
    var firstCondition = true;
    
    let endTribe = arithmeticSequence(1, different, countCard);
    
    if(index > 0){
        innerIndex--;
    }
    if(indexCustom != null){
        innerIndex = indexCustom;
    }

   if(index == 0){
        rowCarousel.style.transform = "translate("+endTribe+"% ,0px)";
        index = countCard-1;
    }else{
        let currentMargin = arithmeticSequence(firstMargin, different, innerIndex+1);
        rowCarousel.style.transform = "translate("+currentMargin+"% ,0px)";
        index--;
    }

    if(indexCustom != null){
        index = indexCustom;
    }

    // // if index is 0 back to first position
    // if(index == countCard-1){
    //     rowCarousel.style.transform = "translate("+firstTribe+"% ,0px)";
    //     index++;
    //     firstCondition = false;
    // }
    // // else if(innerIndex == 0){
    // //     rowCarousel.style.transform = "translate("+defaultMargin+"% ,0px)";
    // //     index = 0;
    // // }

    // if(index > 0 && firstCondition == true){
    //     index--;
    // }
    // if(indexCustom != null){
    //     index = indexCustom;
    // }
}

function setChangeTransform(currentWidth) {
    if(currentWidth < 1030){
        defaultMargin = 1;
        firstMargin = 1;
        different = -100;
        
        console.log('execution');
        // document.getElementById('carousel-content').classList.add('container');
    

        left(index);
        console.log('Setup ulang' +" "+ defaultMargin +" "+ firstMargin +" "+ different);
    }else{
        defaultMargin = 1;
        firstMargin = 1;
        different = -42;

        try {
            // document.getElementById('carousel-content').classList.remove('container');
        } catch (error) {
            console.log(error);
        }

        left(index);
        console.log('Setup ulang' +" "+ defaultMargin +" "+ firstMargin +" "+ different);
    }
}

window.addEventListener('resize', function () {
   console.log('resize'); 
   var currentWidth = this.document.body.clientWidth;
   console.log(currentWidth);

   setChangeTransform(currentWidth);
});

window.addEventListener('DOMContentLoaded', function () {
    console.log('set-size'); 
    var currentWidth = this.document.body.clientWidth;
    console.log(currentWidth);

    setChangeTransform(currentWidth);

    // Auto load carousel
    // this.setInterval(function () {
    //     left();
    // }, 5000);
});

document.getElementById('nav-carousel-left').addEventListener('click', function () {
    if(index >= 0){
        right();
        console.log('right', index)
    }
});
document.getElementById('nav-carousel-right').addEventListener('click', function () {
    if(index < countCard){
        left();
        console.log('left', index)
    }
});