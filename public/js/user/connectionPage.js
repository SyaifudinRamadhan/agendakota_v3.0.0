var state = false
document.addEventListener("click", e => {
    selectAll(".dropdown-content").forEach(dropdown => {
        dropdown.classList.remove('show');
    });
    let target = e.target;
    if (target.classList.contains('dropdownToggle')) {
        let id = target.getAttribute('data-id');
        console.log('Klik other button du click');
        // state.isOptionOpened = true;
        if(state == false){
            document.getElementById("Dropdown" + id).classList.add('d-block');
            state = true;
        }else{
            document.getElementById("Dropdown" + id).classList.remove('d-block');
            state = false;
        }
    }
    // } else {
    //     state.isOptionOpened = false;
    // }
});