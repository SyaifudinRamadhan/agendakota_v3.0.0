function hideBody(targetID) {
    document.querySelector(targetID+' '+'#close').classList.add('d-none');
    document.querySelector(targetID+' '+'#open').classList.remove('d-none');
    document.querySelector(targetID+' '+'#body-card').classList.add('d-none');
}

function unhideBody(targetID) {
    document.querySelector(targetID+' '+'#close').classList.remove('d-none');
    document.querySelector(targetID+' '+'#open').classList.add('d-none');
    document.querySelector(targetID+' '+'#body-card').classList.remove('d-none');
}

function cardAction(targetID){
    document.querySelector(targetID+' '+'#open').addEventListener('click', function () {
       unhideBody(targetID); 
    });

    document.querySelector(targetID+' '+'#close').addEventListener('click', function () {
        hideBody(targetID);
    })
}