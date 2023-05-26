const select = dom => document.querySelector(dom)
const selectAll = dom => document.querySelectorAll(dom)


const escapeJson = str => {
    return str.replace(/\n/g, "\\n")
    .replace(/\&quot;/g, "\"")
    .replace(/\r/g, "\\r")
    .replace(/\t/g, "\\t")
    .replace(/\f/g, "\\f");
}
const scrollKe = dom => {
    select(dom).scrollIntoView({
        behavior: 'smooth'
    })
}

const post = (url, data) => {
    return fetch(url, {
        method: 'POST',
        headers: {
            "X-CSRF-TOKEN": data.csrfToken,
            "Content-Type": "application/json"
        },
        mode: "no-cors",
        body: JSON.stringify(data)
    })
    .then(res => res.json())
}
const get = url => {
    return fetch(url)
    .then(res => res.json())
}

const bindDivWithImage = (objFit = 'cover') => {
    const divsWithBgImg = selectAll("div[bg-image]");
    divsWithBgImg.forEach(div => {
        let bg = div.getAttribute('bg-image');
        let objectFit = div.getAttribute('object-fit');
        if (objectFit == "" || objectFit == null) {
            objectFit = objFit;
        }
        div.style.backgroundRepeat = "no-repeat";
        div.style.backgroundImage = `url('${bg}')`;
        div.style.backgroundPosition = 'center center';
        div.style.backgroundSize = objectFit;
    })
}
bindDivWithImage()

// alert
let alerts = selectAll('.alert .ke-kanan')
alerts.forEach(alert => {
    alert.addEventListener('click', e => {
        let parent = e.currentTarget.parentNode
        parent.style.display = "none"
    })
})

const munculPopup = sel => {
    let popup = select(sel)
    select(".bg").style.display = "block"
    popup.style.display = "block"
    // let height = popup.clientHeight
    // popup.style.marginTop = `${height}px`
    setTimeout(() => {
        select(sel + " .popup").style.top = "70px"
    }, 50)
}
const hilangPopup = (sel) => {
    select(".bg").style.display = "none"
    selectAll(sel + " .popup").forEach(res => {
        // let height = res.clientHeight + 1250
        // res.style.marginTop = `-${height}px`
    })
    // setTimeout(() => {
        selectAll(sel).forEach(res => res.style.display = "none")
    // }, 100);
}
if (select(".bg")) {
    select(".bg").addEventListener('click', e => {
        hilangPopup(".popupWrapper")
    })
}

function htmlDecode(input){
    var e = document.createElement('textarea');
    e.innerHTML = input;
    return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
}
function createElement(props) {
    let el = document.createElement(props.el)
    if (props.attributes !== undefined) {
        props.attributes.forEach(res => {
            el.setAttribute(res[0], res[1])
        })
    }
    if(props.html !== undefined) {
        el.innerHTML = props.html
    }
    select(props.createTo).appendChild(el)
}

function toIdr(angka) {
	var rupiah = '';
	var angkarev = angka.toString().split('').reverse().join('');
	for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
	return 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('');
}
function toAngka(rupiah) {
    return parseInt(rupiah.replace(/,.*|[^0-9]/g, ''), 10);
}
function inArray(needle, haystack) {
    let length = haystack.length;
    for (let i = 0; i < length; i++) {
        if (haystack[i] == needle) return true;
    }
    return false;
}

const redirect = url => {
    let a = document.createElement('a')
    a.href = url
    a.setAttribute('target', '_blank')
    a.click()
}
const sum = (...args) => {
    return args.reduce((total, arg) => total + arg, 0)
}

document.addEventListener('keydown', e => {
    if (e.key == "Escape") {
        hilangPopup(".popupWrapper")
    }
})

// selectAll(".box").forEach(input => {
//     input.addEventListener('focus', e => {
//         e.currentTarget.style.border = "1px solid #ddd"
//     })
// })

function currencyRupiah(harga){
    var	number_string = harga.toString(),
    sisa 	= number_string.length % 3,
    hargaRupiah 	= number_string.substr(0, sisa),
    ribuan 	= number_string.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
        separator = sisa ? '.' : '';
        hargaRupiah += separator + ribuan.join('.');
    }
    return hargaRupiah;
}


function opentabs(evt, jenis, kategori) {
    var i, tabcontent, tablinks, tabContentName, tabLinkName;
    tabContentName = "tabcontent-"+jenis;
    tabcontent = document.getElementsByClassName(tabContentName);
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    tabLinkName = "tablinks-" + jenis;
    tablinks = document.getElementsByClassName(tabLinkName);
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    document.getElementById(kategori).style.display = "block";
    try {
        evt.currentTarget.className += " active";
    } catch (error) {
        console.log(error);
    }
}

//revisi openTabs
function openTabs(evt, jenis, kategori){
    // var i, tabcontent, tablinks, tabContentName, tabLinkName;
    var tabContentName = "tabcontent-"+jenis;
    var tabcontent = document.getElementsByClassName(tabContentName);
    console.log(tabcontent, tabContentName);
    for (var i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    var tabLinkName = "tablinks-" + jenis;
    var tablinks = document.getElementsByClassName(tabLinkName);
    for (var i = 0; i < tablinks.length; i++) {
        if(jenis == 'rightMenu'){
            tablinks[i].className = tablinks[i].className.replace("bg-primer-transparent", "");
        }else{
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        
    }

    var categoryView = document.getElementsByClassName(kategori);
    for(var i = 0; i < categoryView.length; i++){
        categoryView[i].style.display = "block"
    }
    if(jenis == 'rightMenu'){
        evt.currentTarget.className += " bg-primer-transparent "; 
    }else{
        evt.currentTarget.className += " active "; 
    }
}

 const toggleView = btn => {
        let mode = btn.getAttribute('mode');
        selectAll(".toggle-view-button").forEach(button => {
            button.classList.remove('active');
        });

        btn.classList.add('active');
        // selectAll(".list-item").forEach(item => {
        //     if (mode == "table") {
        //         item.classList.add("is-table-mode");
        //     }else {
        //         item.classList.remove("is-table-mode");
        //     }
        // })
        if(mode == 'table'){
                
                selectAll(".table-button").forEach(button => {
                    button.classList.add('active');
                });
                // document.getElementById('card-mode').classList.remove('d-block');
                // document.getElementById('card-mode').classList.add('d-none');
            
                // document.getElementById('table-mode').classList.remove('d-none');
                // document.getElementById('table-mode').classList.add('d-block');

                var divCard = document.querySelectorAll('#card-mode');

                for (var i = 0; i < divCard.length; i++) {
                    divCard[i].classList.remove('d-block');
                    divCard[i].classList.add('d-none');
                }

                var divTable = document.querySelectorAll('#table-mode');

                for (var i = 0; i < divTable.length; i++) {
                    divTable[i].classList.remove('d-none');
                    divTable[i].classList.add('d-block');
                }
            
        }else if(mode == 'card'){

                selectAll(".card-button").forEach(button => {
                    button.classList.add('active');
                });
            
                // document.getElementById('table-mode').classList.remove('d-block');
                // document.getElementById('table-mode').classList.add('d-none');
            
            
                // document.getElementById('card-mode').classList.remove('d-none');
                // document.getElementById('card-mode').classList.add('d-block');
           
                var divTable = document.querySelectorAll('#table-mode');

                for (var i = 0; i < divTable.length; i++) {
                    divTable[i].classList.remove('d-block');
                    divTable[i].classList.add('d-none');
                }

                var divCard = document.querySelectorAll('#card-mode');

                for (var i = 0; i < divCard.length; i++) {
                    divCard[i].classList.remove('d-none');
                    divCard[i].classList.add('d-block');
                }
        }
    }

const inputFile = (input, previewArea) => {
    let file = input.files[0];
    let reader = new FileReader();
    let preview = select(previewArea);
    reader.readAsDataURL(file);

    reader.addEventListener("load", function() {
        preview.style.width = "100%";
        preview.setAttribute('bg-image', reader.result);
        preview.innerHTML = "";
        preview.classList.add('tinggi-200')
        bindDivWithImage();
    });
}

// document.getElementById("pay-button").onclick = function(){
        
//         console.log('{{$purchase->token_trx}}');
//         snap.pay('{{ $purchase->token_trx }}', {

//                 onSuccess: function(result){
//                     /* You may add your own implementation here */
//                     alert('Pembayaranmu sudah kami terima');
//                 },
//                 onPending: function(result){
//                     /* You may add your own implementation here */
//                     alert("Mohon selesaikan pembayarannya segera");
//                 },
//                 onError: function(result){
//                     /* You may add your own implementation here */
//                     alert("Pembayaranmu gagal!"); console.log(result);
//                 },
//                 onClose: function(){
//                     /* You may add your own implementation here */
//                     alert('Kamu menutup Pop-Up pembayaran sebelum selesai');
//                 }
//         }); // Replace it with your transaction token



//     }



const squarize = () => {
    let doms = selectAll(".squarize");
    doms.forEach(dom => {
        let classes = dom.classList;
        let computedStyle = getComputedStyle(dom);
        if (classes.contains('rectangle')) {
            let width = computedStyle.width.split("px")[0];
            let widthRatio = parseFloat(width) / 16;
            let setHeight = 9 * widthRatio;
            dom.style.height = `${setHeight}px`;
        } else {
            if (classes.contains('use-lineHeight')) {
                dom.style.lineHeight = computedStyle.width;
            } else {
                dom.style.height = computedStyle.width;
            }
        }
    });
}

squarize();

try {
    document.querySelectorAll('#mode-event').forEach(element => {
        element.addEventListener('click', function () {
            // Ajax dengan jquery untuk set mode event
            this.checked ? document.getElementById('public-mode').submit() : document.getElementById('private-mode').submit();
        });
    })
} catch (error) {
    
}
