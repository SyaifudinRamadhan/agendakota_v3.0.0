var questions = [];
var userAnswear = [];
var indexCounter = 0;

// Function untuk memuat data pertanyaan
function loadQuestion(dataObj) {
    document.getElementById('question').innerHTML = dataObj.question;
    document.getElementById('choice-1').value = dataObj.option_a;
    document.getElementById('choice-11').innerHTML = dataObj.option_a;
    document.getElementById('choice-2').value = dataObj.option_b;
    document.getElementById('choice-21').innerHTML = dataObj.option_b;
    document.getElementById('choice-3').value = dataObj.option_c;
    document.getElementById('choice-31').innerHTML = dataObj.option_c;
    document.getElementById('choice-4').value = dataObj.option_d;
    document.getElementById('choice-41').innerHTML = dataObj.option_d;
}


// function setup data questions
function setupData(jsonData) {
    var jsonToArr = Object.values(jsonData);
    questions = jsonToArr;
    userAnswear = Array(questions.length);
    console.log(questions, userAnswear);
    loadQuestion(questions[0]);
    // Memberi status untuk button
    document.getElementById('prev-btn').classList.add('disabled');
    if(questions.length == 1){
        document.getElementById('next-btn').classList.add('disabled');
    }
}

// function next button
function next(arrayQuestion, lastIndex){
    var access = lastIndex + 1;
    if(access < arrayQuestion.length){
        saveAnswear(lastIndex);
        loadQuestion(arrayQuestion[access]);
        viewAnswear(access);
        indexCounter += 1;
        document.getElementById('prev-btn').classList.remove('disabled');
        console.log(arrayQuestion.length, indexCounter);
        if(indexCounter >= arrayQuestion.length-1){
            document.getElementById('next-btn').classList.add('disabled');
            indexCounter = arrayQuestion.length-1;
        }
    }
}

// function prev button
function prev(arrayQuestion, lastIndex) {
    var access = lastIndex - 1;
    console.log(access);
    if(access >= 0){
        saveAnswear(lastIndex);
        loadQuestion(arrayQuestion[access]);
        viewAnswear(access);
        indexCounter -= 1;
        document.getElementById('next-btn').classList.remove('disabled');
        if(access <= 0){
            document.getElementById('prev-btn').classList.add('disabled');
            indexCounter = 0;
        }
    }
}

// Menyimpan jawaban di array
function saveAnswear(lastIndex) {
    var ans = '';
    var AnsUser = document.querySelectorAll('input[type="radio"]:checked');
    if(AnsUser.length == 0){
        ans = '';
    }else{
        ans = AnsUser[0].value;
    }
    console.log(AnsUser, ans);
    userAnswear[lastIndex] = ans;
    console.log(userAnswear);
    unchecked();
}

// Reset checked
function unchecked() {
    var AnsUser = document.querySelectorAll('input[type="radio"]:checked');
    for (let index = 0; index < AnsUser.length; index++) {
        AnsUser[index].checked = false;
    }
}

// Preview jawaban user tersimpan
function viewAnswear(index) {
    var lastAns = userAnswear[index];
    var allBtn = document.getElementsByTagName('input');
    console.log(lastAns);
    for (let index = 0; index < allBtn.length; index++) {
        // console.log(allBtn);
        if(allBtn[index].value == lastAns){
            console.log(allBtn[index]);
            allBtn[index].checked = true;
        }
    }
}

// Submit data quiz dengan AJAX javascript vanilla
function sendAnswear(params) {
    let post = JSON.stringify(userAnswear);
 
    const url = "https://jsonplaceholder.typicode.com/posts";
    let xhr = new XMLHttpRequest();
    
    xhr.open('POST', url, true)
    xhr.setRequestHeader('Content-type', 'application/json; charset=UTF-8')
    xhr.send(post);
    
    xhr.onload = function () {
        if(xhr.status === 201) {
            console.log("Post successfully created!") 
        }
    }
} 

// Onload => memberi fungsi click pada tombol tombol
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('prev-btn').addEventListener('click', function () {
        prev(questions, indexCounter);
    });
    document.getElementById('next-btn').addEventListener('click', function () {
        next(questions, indexCounter);
    });
})
