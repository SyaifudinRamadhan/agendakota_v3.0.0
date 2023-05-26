    
    let state = {
        isOptionOpened: false,
    }

    const toggleViewInner = btn => {
        let mode = btn.getAttribute('mode');
        selectAll(".toggle-view-button").forEach(button => {
            button.classList.remove('active');
        });
        btn.classList.add('active');
        selectAll(".list-item").forEach(item => {
            if (mode == "list") {
                item.classList.add("is-list-mode");
            } else {
                item.classList.remove("is-list-mode");
            }
        })
    }

    document.addEventListener("click", e => {
        selectAll(".dropdown-content").forEach(dropdown => {
            dropdown.classList.remove('show');
        });

        let target = e.target;
        console.log(target);
        if (target.classList.contains('dropdownToggle')) {
            console.log('disana')
            let id = target.getAttribute('data-id');
            document.getElementById("Dropdown" + id).classList.toggle("show");
            state.isOptionOpened = true;
        } else {
            state.isOptionOpened = false;
        }
    });

    const getSelectedBreakdown = () => {
        let selectedBreakdown = [];
        selectAll(".breakdowns").forEach(breakdown => {
            if (breakdown.classList.contains('active')) {
                let breakdownType = breakdown.getAttribute('breakdown-type');
                selectedBreakdown.push(breakdownType);
            }
        });
    }

    selectAll(".breakdowns").forEach(breakdown => {
        breakdown.addEventListener("click", e => {
            let target = e.currentTarget;
            target.classList.toggle('active');
            getSelectedBreakdown();
        })
    })

    const uploadLogoProfile = (input, isEdit = null) => {
        let file = input.files[0];
        let reader = new FileReader();
        reader.readAsDataURL(file);

        reader.addEventListener("load", function() {
            if (isEdit == null) {
                let preview = select("#logoPreviewProfile");
                select("#inputLogoAreaProfile").classList.add('d-none');
                select("#previewLogoAreaProfile").classList.remove('d-none');
                preview.setAttribute('src', reader.result);
            } else {
                let preview = select("#logoPreviewProfile");
                select("#inputLogoAreaProfile").classList.add('d-none');
                select("#previewLogoAreaProfile").classList.remove('d-none');
                preview.setAttribute('src', reader.result);
            }
        });
    }

    
    @foreach ($events as $event)
        var arr = [];
        var total = "";
        @foreach ($event->sessions as $session)
            @foreach ($session->tickets as $ticket)
                arr.push("{{ $ticket->price }}");
            @endforeach
        @endforeach
    
        var hargaTerendah = arr[0];
        var hargaTertinggi = arr[0];
        for(var i =0; i < arr.length; i++){ if(arr[i] < hargaTerendah){ hargaTerendah=arr[i]; } if(arr[i]>= hargaTertinggi){
            hargaTertinggi = arr[i];
            }
            }
    
            var rupiahHargaTerendah = currencyRupiah(hargaTerendah);
            var rupiahHargaTertinggi = currencyRupiah(hargaTertinggi);
    
            if(hargaTertinggi == 0){
            total = "Free";
            }else if(hargaTerendah == hargaTertinggi){
            total = "Rp. "+rupiahHargaTerendah;
            }
            else{
            total = "Rp. "+rupiahHargaTerendah+" - Rp. "+rupiahHargaTertinggi;
            }
    
            var eventID = "{{ $event->id }}";
            document.getElementById('target-harga-tiket-'+eventID).innerHTML = '&nbsp;'+total;
            document.getElementById('target-harga-tiket2-'+eventID).innerHTML = total;
    @endforeach