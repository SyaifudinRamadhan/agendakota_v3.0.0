const select = dom => document.querySelector(dom);
const selectAll = dom => document.querySelectorAll(dom);

function inArray(needle, haystack) {
    let length = haystack.length;
    for (let i = 0; i < length; i++) {
        if (haystack[i] == needle) return true;
    }
    return false;
}
const removeArray = (toRemove, arr) => {
    let index = arr.indexOf(toRemove);
    arr.splice(index, 1);
}

const scrollTo = dom => {
    select(dom).scrollIntoView({
        behavior: 'smooth'
    })
}

const generateRandomString = (length = 6) => {
    let result = '';
    let patt = '0123456789abcdefghijklmnopqrstuvwxyz';
    for (let i = 0; i < length; i++) {
        result += patt[Math.floor(Math.random() * patt.length)];
    }
    return result;
}

const serialize = (obj, prefix) => {
    var str = [],
    p;
    for (p in obj) {
        if (obj.hasOwnProperty(p)) {
            var k = prefix ? prefix + "[" + p + "]" : p,
            v = obj[p];
            str.push((v !== null && typeof v === "object") ?
            serialize(v, k) :
            encodeURIComponent(k) + "=" + encodeURIComponent(v));
        }
    }
    return str.join("&");
}
    
const post = (url, data) => {
    let options = {
        method: "POST",
        body: JSON.stringify(data),
        headers: {
            "Content-Type": "application/json"
        }
    };
    if (data != null && data.hasOwnProperty('csrfToken')) {
        options['headers']['X-CSRF-TOKEN'] = data.csrfToken;
    }
    if (data != null && data.hasOwnProperty('headers') && data.headers == 'multipart/form-data') {
        options['headers'] = data.headers;
        options['body'] = data;
    }

    return fetch(url, options).then(res => res.json())
}
const get = url => {
    return fetch(url).then(res => res.json());
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

const bindDivWithImage = () => {
    const divsWithBgImg = selectAll("div[bg-image]")
    divsWithBgImg.forEach(div => {
        let bg = div.getAttribute('bg-image');
        if (bg != "") {
            let styles = getComputedStyle(div);
            div.style.backgroundImage = `url(\'${bg}\')`;
            div.style.backgroundPosition = 'center center';
            div.style.backgroundSize = 'cover';
        }
    });
}
// setTimeout(() => {
    bindDivWithImage();
// }, 1000);

const modal = (sel = '') => {
    let selector = typeof sel == 'string' ? document.querySelector(`.modal${sel}`) : sel;
    selector.show = () => {
        selector.style.display = "flex";
    }
    selector.hide = () => {
        if (typeof sel != 'string') {
            selector = selector.parentNode.parentNode.parentNode;
        }
        selector.style.display = "none";
    }
    selector.hideAll = (force = false) => {
        document.querySelectorAll(".modal").forEach(mod => {
            if (force) {
                modal(`#${mod.getAttribute('id')}`).hide();
            } else {
                if (!mod.classList.contains('nox')) {
                    modal(`#${mod.getAttribute('id')}`).hide();
                }
            }
        });
    }
    return selector;
}

document.querySelectorAll(".modal span[hide]").forEach(btn => {
    btn.setAttribute('onclick', 'modal(this).hide()');
});


let keyboards = {
    Escape: () => {
        modal().hideAll();
    },
};
document.addEventListener('keydown', e => {
    if (keyboards.hasOwnProperty(e.key)) {
        keyboards[e.key]();
    }
});

const press = (key, callback) => {
    keyboards[key] = callback;
}

const getExtension = filename => {
    let exts = filename.split('.');
    return exts[exts.length - 1].toLowerCase();
}
const inputFile = (input, options) => {
    // previewArea = null, callback = null
    let file = input.files[0];
    let reader = new FileReader();
    let preview = null;
    if (options.hasOwnProperty('preview')) {
        preview = select(options.preview);
    }
    reader.readAsDataURL(file);

    reader.addEventListener("load", function(event) {
        file.extension = getExtension(file.name);
        if (
            inArray(getExtension(file.name), ['png','jpg','jpeg'])
        ) {
            if (options.hasOwnProperty('preview')) {
                preview.setAttribute('bg-image', reader.result);
                bindDivWithImage();
                if (preview.classList.contains('squarize')) {
                    squarize();
                }
                preview.innerHTML = "";
            }
        }
        if (options.hasOwnProperty('callback')) {
            options.callback(event, file);
        }
    });
}

const copyText = text => {
    navigator.clipboard.writeText(text);
}
const escapeJson = str => {
    return str.replace(/\n/g, "\\n")
    .replace(/\&quot;/g, "\"")
    .replace(/\r/g, "\\r")
    .replace(/\t/g, "\\t")
    .replace(/\f/g, "\\f");
}
function parseJwt (token) {
    var base64Url = token.split('.')[1];
    var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));

    return JSON.parse(jsonPayload);
}

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
            } else if (classes.contains('use-height')) {
                dom.style.width = computedStyle.height;
            } else {
                dom.style.height = computedStyle.width;
            }
        }
    });
}

squarize();

selectAll(".switch").forEach(button => {
    button.setAttribute('onclick', 'clickSwitch(this)');
})
selectAll(".tab-item").forEach(tabItem => {
    let ogAction = tabItem.getAttribute('onclick');
    tabItem.setAttribute('onclick', 'clickTab(this)');
    if (tabItem.getAttribute('tab-level') == null) {
        tabItem.setAttribute('tab-level', 0);
    }
    tabItem.setAttribute('ogAction', ogAction);
});
selectAll(".tab-content").forEach(content => {
    if (content.getAttribute('tab-level') == null) {
        content.setAttribute('tab-level', 0);
    }
    if (!content.classList.contains('active')) {
        content.classList.add('d-none');
    }
});

const clickSwitch = button => {
    let whenOn = button.getAttribute('whenOn');
    let whenOff = button.getAttribute('whenOff');
    
    if (button.classList.contains('on') && whenOff != null) {
        eval(`(${whenOff})()`);
    } else {
        if (whenOn != null) {
            eval(`(${whenOn})()`);
        }
    }
    button.classList.toggle('on');
}
const clickTab = tab => {
    let tabLevel= tab.getAttribute('tab-level') == null ? 0 : tab.getAttribute('tab-level');
    let tabs = selectAll(`.tab-item[tab-level='${tabLevel}']`);
    let ogAction = tab.getAttribute('ogAction');
    let target = tab.getAttribute('target');

    if (ogAction != null) {
        eval(`${ogAction}`);
    }
    tabs.forEach(theTab => {
        if ((theTab.tagName == 'DIV' || theTab.tagName == 'LI') && theTab.classList.contains('tab-item')) {
            theTab.classList.remove('active');
        }
        if (theTab.getAttribute('target') == target) {
            theTab.classList.add('active');
        }
    });
    // tab.classList.add('active');

    selectAll(`.tab-content[tab-level='${tabLevel}']`).forEach(content => content.classList.add('d-none'));
    select(`.tab-content[key='${target}'][tab-level='${tabLevel}']`).classList.remove('d-none');
}

selectAll("dropdown").forEach((dropdown, d) => {
    let name = dropdown.getAttribute('name');
    let input = document.createElement('input');
    input.type = "text";
    input.name = name;
    input.classList.add('dropdown_input');
    input.setAttribute('pointer', d);
    dropdown.appendChild(input);

    let theValue = "jiancok";

    let childs = dropdown.childNodes;
    childs.forEach(child => {
        if (child.tagName == 'OPSI') {
            let items = child.childNodes;
            let i = 0;
            items.forEach(item => {
                if (item.tagName == 'ITEM') {
                    if (i == 0) {
                        theValue = item.getAttribute('value');
                        if (theValue == null) {
                            theValue = item.innerHTML;
                        }
                    }
                    item.addEventListener('click', e => {
                        let target = e.currentTarget;
                        let value = target.getAttribute('value');
                        if (value == null) {
                            value = target.innerHTML;
                        }
                        value = target.innerHTML;
                        select(`input.dropdown_input[pointer='${d}']`).value = valueForInput;
                        select(`.value_area[pointer='${d}']`).innerHTML = value;
                        target.parentNode.style.display = "none";
                    });
                    i++;
                }
            })
        }
    });

    let valueArea = document.createElement('div');
    valueArea.classList.add('value_area');
    valueArea.setAttribute('pointer', d);
    valueArea.innerHTML = theValue;
    dropdown.appendChild(valueArea);

    dropdown.addEventListener('click', e => {
        let theChilds = e.target.childNodes;
        theChilds.forEach(child => {
            if (child.tagName == 'OPSI') {
                child.style.display = "block";
            }
        });
    })
})