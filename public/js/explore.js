//   Main execution
function setDataEventByCategory(jsonData, jsonData2) {
    objViewCard = new ExploreTemplate(jsonData, jsonData2, 'event-show');
}

let skeletonLoading = new SkeletonTemplate('event-show');

document.addEventListener('DOMContentLoaded', function () {
    skeletonLoading.setView(12,true);
    objViewCard.setView(12,true);
})

function loadMore() {
    // skeletonLoading.setView(undefined,true);
    objViewCard.setView();
}

function filterLoad(JSONData){
    try {
        document.getElementById('btn-filter-bar-close').click();
    } catch (error) {
        
    }
    skeletonLoading.setView(undefined,true);
    objViewCard.genNewData(`${window.location.origin}/explore-core`, JSONData);
}

// Controlling filter

// Object filterer
// Note name filter variable :
// 1. execution_type -> event-type
// 2. city -> location
// 3. category -> category
// 4. start_date -> datetime 
// 5. price_from -> price event
let filterer = {};

// let countIn = 0;
document.getElementById('date').addEventListener('input', function () {
    // countIn += 1;
    if((this.value != '' || this.value != null || this.value != undefined)){
        console.log(this.value);
        document.getElementById('text-time-selected').innerHTML = this.value;
        let lblPicker = document.getElementById('label-date-pick');
        lblPicker.classList.add('d-none');
        lblPicker.classList.remove('d-flex');

        lblPicker = document.getElementById('btn-clean-pick');
        lblPicker.classList.add('d-flex');
        lblPicker.classList.remove('d-none');
        // countIn = 0;
        filterer.start_date = this.value;
        console.log(filterer);
        filterLoad(filterer);
    }
});

document.getElementById('btn-clean-pick').addEventListener('click', function () {
    document.getElementById('text-time-selected').innerHTML = 'Semua Waktu';
    
    this.classList.add('d-none');
    this.classList.remove('d-flex');

    let lblPicker = document.getElementById('label-date-pick');
    lblPicker.classList.add('d-flex');
    lblPicker.classList.remove('d-none');

    delete filterer.start_date;
    console.log(filterer);
    filterLoad(filterer);
});

// document.getElementById('event-type').addEventListener('change', function(){
//     console.log(this.checked);
//     this.checked ? filterer.execution_type = 'online' : filterer.execution_type = 'offline';
//     console.log(filterer);
//     filterLoad(filterer);
// })

document.querySelectorAll('[name="event-type"]').forEach(element => {
    element.addEventListener('change', () => {
        console.log(element.value);
        if(element.value == 'all'){
            delete filterer.execution_type;
            console.log(filterer);
            filterLoad(filterer);
        }else{
            filterer.execution_type = element.value;
            console.log(filterer);
            filterLoad(filterer);
        }
    })
});

document.querySelectorAll('[name="event-price"]').forEach(element => {
    element.addEventListener('change', () => {
        console.log(element.value);
        if(element.value == 'option1'){
            document.getElementById('form-price').classList.add('d-none');
            delete filterer.price_from;
            console.log(filterer);
            filterLoad(filterer);
        }else if(element.value == 'option2'){
            document.getElementById('form-price').classList.add('d-none');
            filterer.price_from = 'free';
            console.log(filterer);
            filterLoad(filterer);
        }else{
            document.getElementById('form-price').classList.remove('d-none');
        }
    })
});

document.querySelector('#form-price input').addEventListener('change', function () {
    filterer.price_from = this.value;
    console.log(filterer);
    filterLoad(filterer);
});

$('#city').on('select2:select', function (e) {
    var data = e.params.data;
    console.log(data);
    data.id == 'all' ? delete filterer.city : filterer.city = data.id;
    console.log(filterer);
    filterLoad(filterer);
});

$('#category').on('select2:select', function (e) {
    var data = e.params.data;
    console.log(data);
    data.id == 'all' ? delete filterer.category : filterer.category = data.id;
    console.log(filterer);
    filterLoad(filterer);
});

$('#topic').on('select2:select', function (e) {
    var data = e.params.data;
    console.log(data);
    data.id == 'all' ? delete filterer.topic : filterer.topic = data.id;
    console.log(filterer);
    filterLoad(filterer);
});