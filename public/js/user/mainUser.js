var indicator = false;

function showDropDownMenu() {
    if (indicator == false) {
        document.getElementById('dropdown-menu').classList.add('show');
        indicator = true;
    } else if (indicator == true) {
        document.getElementById('dropdown-menu').classList.remove('show');
        indicator = false;
    }
}
const expandMenu = btn => {
    console.log("clicked");
    let toExpand = btn.parentNode.parentNode.children[1];
    let style = getComputedStyle(toExpand);
    if (style.display == "block") {
        btn.classList.add('fa-plus');
        btn.classList.remove('fa-minus');
        toExpand.style.display = "none";
    } else {
        btn.classList.remove('fa-plus');
        btn.classList.add('fa-minus');
        toExpand.style.display = "block";
    }
}
// const chooseFile = input => {
//     let file = input.files[0];
//     let reader = new FileReader();
//     let preview = select("#logoPreview");
//     reader.readAsDataURL(file);
//     reader.addEventListener("load", function() {
//         select("#inputLogoArea").classList.add('d-none');
//         select("#previewLogoArea").classList.remove('d-none');
//         preview.setAttribute('src', reader.result);
//     });
// }
// const removePreview = () => {
//     select("#inputLogoArea").classList.remove('d-none');
//     select("#previewLogoArea").classList.add('d-none');
// }
function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
AOS.init();