@extends('layouts.homepage_new')

@section('title', "Explore")

@section('head.dependencies')
<style>
    .FilterControl select,
    .FilterControl input {
        border: none;
        border-bottom: 1px solid #ddd;
        border-radius: 0px;
        padding: 0px;
    }
    .FilterControl #TopicArea {
        border: 1px solid #ddd;
    }
    input#topic {
        border: none;
        height: 40px;
        padding: 10px;
        margin: 0px;
        font-size: 12px;
        width: auto;
    }
    @media(max-width: 480px) {
        .FilterControl {
            position: fixed;
            width: 100%;
            top: 100px;bottom: 0px;
            right: -150%;
        }
        .FilterControl.active {
            right: 0%;
        }
    }
</style>
@endsection
    
@section('content')
<div class="flex row item-stretch">
    <div class="FilterControl p-3 bg-white border-right flex column" style="flex-basis: 20%">
        <div class="text small primary mb-1">Jenis Event</div>
        <div class="divide divide-2">
            <div class="flex row item-center h-40 pointer" data-name="execution_type" data-value="" onclick="setFilter('execution_type', this, true)">
                <div class="radio {{ $request->execution_type == '' ? 'active' : '' }}"><div></div></div>
                <div class="text ml-2">Semua</div>
            </div>
        </div>
        <div class="divide divide-2">
            <div class="flex row item-center h-40 pointer" data-name="execution_type" data-value="hybrid" onclick="setFilter('execution_type', this, true)">
                <div class="radio {{ $request->execution_type == 'hybrid' ? 'active' : '' }}"><div></div></div>
                <div class="text ml-2">Hybrid</div>
            </div>
        </div>
        <div class="divide divide-2">
            <div class="flex row item-center h-40 pointer" data-name="execution_type" data-value="online" onclick="setFilter('execution_type', this, true)">
                <div class="radio {{ $request->execution_type == 'online' ? 'active' : '' }}"><div></div></div>
                <div class="text ml-2">Online</div>
            </div>
        </div>
        <div class="divide divide-2">
            <div class="flex row item-center h-40 pointer" data-name="execution_type" data-value="offline" onclick="setFilter('execution_type', this, true)">
                <div class="radio {{ $request->execution_type == 'offline' ? 'active' : '' }}"><div></div></div>
                <div class="text ml-2">Offline</div>
            </div>
        </div>

        <div class="h-20"></div>
        <div class="group">
            <div class="text small primary">Lokasi</div>
            <select name="city" id="city" class="mt-0" onchange="setFilter('city', this.value)">
                <option value="">Semua Lokasi</option>
                @foreach ($cities as $city)
                    <option {{ $request->city == $city->name ? 'selected="selected"' : '' }}>{{ $city->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="h-20"></div>
        <div class="group">
            <div class="text small primary">Kategori</div>
            <select name="city" id="city" class="mt-0" onchange="setFilter('category', this.value)">
                <option value="">Semua Kategori</option>
                @foreach ($event_types as $type)
                    <option {{ $request->category == $type['name'] ? 'selected="selected"' : '' }}>{{ $type['name'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="h-30"></div>
        <div class="text small primary mb-1">Harga</div>
        <div class="flex row item-center h-40 pointer" data-name="payment_type" data-value="" onclick="setFilter('payment_type', this, true)">
            <div class="radio {{ $request->payment_type == "" ? 'active' : '' }}"><div></div></div>
            <div class="text ml-2">Semua</div>
        </div>
        <div class="flex row item-center h-40 pointer" data-name="payment_type" data-value="gratis" onclick="setFilter('payment_type', this, true)">
            <div class="radio {{ $request->payment_type == "gratis" ? 'active' : '' }}"><div></div></div>
            <div class="text ml-2">Gratis</div>
        </div>
        <div class="flex row item-center h-40 pointer" data-name="payment_type" data-value="berbayar" onclick="setFilter('payment_type', this, true)">
            <div class="radio {{ $request->payment_type == "berbayar" ? 'active' : '' }}"><div></div></div>
            <div class="text ml-2">Berbayar</div>
        </div>

        <div class="h-20"></div>
        <div class="group">
            <div class="text small primary">Waktu</div>
            <select onchange="setFilter('timeframe', this.value)">
                <option value="this-day" {{ $request->timeframe == 'this-day' ? "selected='selected'" : "" }}>Hari Ini</option>
                <option value="next-day" {{ $request->timeframe == 'next-day' ? "selected='selected'" : "" }}>Besok</option>
                <option value="this-week" {{ $request->timeframe == 'this-week' || $request->timeframe == "" ? "selected='selected'" : "" }}>Minggu Ini</option>
                <option value="next-week" {{ $request->timeframe == 'next-week' ? "selected='selected'" : "" }}>Minggu Depan</option>
                <option value="this-month" {{ $request->timeframe == 'this-month' ? "selected='selected'" : "" }}>Bulan Ini</option>
                <option value="next-month" {{ $request->timeframe == 'next-month' ? "selected='selected'" : "" }}>Bulan Depan</option>
            </select>
        </div>

        <div class="h-20"></div>
        <div class="text small primary">Topik</div>
        <div id="TopicArea" class="mt-1 flex row wrap item-center rounded">
            <div id="RenderTopic" class="w-100 flex row wrap item-center">
                @if ($request->topics != "")
                    @foreach (explode("|", $request->topics) as $topic)
                        <div class="flex p-05 pl-1 pr-1 rounded-max text small-2 bordered m-05 pointer" onclick="removeTopic(this)">
                            {{ $topic }}
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="w-100 flex row item-center">
                <input type="text" id="topic" class="flex grow-1" oninput="searchTopic(this.value)">
                <i class="bx bx-search mr-1"></i>
            </div>
        </div>
        <div id="TopicSearchResult" class="flex row item-center wrap"></div>
    </div>
    <div class="flex column shrink-0 grow-0 grow-1 p-2" style="flex-basis: 80%">
        <div class="flex row item-center wrap" id="render"></div>

        <div class="flex column item-center">
            <button class="primary mt-2 w-50" id="LoadMore" onclick="loadMore()">Load More</button>
        </div>
    </div>
</div>

<button class="FAB primary mobile-only" onclick="toggleFilterControl()">
    <i class="bx bx-filter"></i>
</button>
@endsection

@section('javascript')
<script>
    let pageIndex = 1;
    let url = new URL(document.URL);
    let filters = {
        q: url.searchParams.get('q'),
        execution_type: url.searchParams.get('execution_type'), // Online, Offline, Hybrid, all
        city: url.searchParams.get('city'),
        category: url.searchParams.get('category'),
        timeframe: url.searchParams.get('timeframe') == "" ? "this-week" : url.searchParams.get('timeframe'),
        payment_type: url.searchParams.get('payment_type'),
        topics: url.searchParams.get('topics') != null ? url.searchParams.get('topics').split('|') : [],
    }
    let topics = JSON.parse(escapeJson("{{ json_encode(config('agendakota')['event_topics']) }}"))

    const renderTopics = () => {
        // select("#RenderTopic").innerHTML = "";
        filters.topics.forEach(topic => {
            Element("div", {
                class: "flex p-05 pl-1 pr-1 rounded-max text small-2 bordered m-05 pointer",
                onclick: "removeTopic(this)"
            })
            .render("#RenderTopic", topic);
        });
        url.searchParams.set('topics', filters.topics.join('|'));
        window.history.pushState(filters, '', url.toString())
        getEvents();
    }
    const removeTopic = btn => {
        let topic = btn.innerText;
        btn.remove();
        removeArray(topic, filters.topics);
        renderTopics();
    }
    const filterTopic = btn => {
        let topic = btn.innerText;
        if (inArray(topic, filters.topics)) {
            removeArray(topic, filters.topics);
        } else {
            filters['topics'].push(topic);
        }
        renderTopics();
        select("#TopicSearchResult").innerHTML = "";
        select("input#topic").value = "";
    }
    const searchTopic = search => {
        let sorted = [...topics];
        let theResult = [];
        select("#TopicSearchResult").innerHTML = "";
        if (search != "") {
            sorted.map((data, i) => {
                if (data.match(new RegExp(search+'.*', 'i')) !== null && !inArray(data, filters.topics)) {
                    theResult.push(data);
                    Element("div", {
                        class: "flex p-05 pl-1 pr-1 rounded-max text small bordered mt-1 pointer",
                        onclick: "filterTopic(this)"
                    })
                    .render("#TopicSearchResult", data);
                }
            });
        } else {
            // 
        }
    }

    const setFilter = (key, value, isRadio = false, refetch = true) => {
        pageIndex = 1;
        filters[key] = isRadio ? value.getAttribute('data-value') : value;
        url.searchParams.set(key, isRadio ? value.getAttribute('data-value') : value);
        window.history.pushState(filters, '', url.toString())
        if (refetch) {
            getEvents();
        }
        if (isRadio) {
            let name = value.getAttribute('data-name');
            selectAll(`div[data-name='${name}'] .radio`).forEach(dom => {
                dom.classList.remove('active');
            })
            select(`div[data-name='${name}'][data-value='${value.getAttribute('data-value')}'] .radio`).classList.add('active');
        }
    }

    let HeaderScrollEffect = screen.width > 480 ? 30 : 10;
    let FilterControl = select(".FilterControl");
    window.addEventListener('scroll', e => {
        let scroll = window.scrollY;
        if (scroll > HeaderScrollEffect) {
            select(".TopBar").style.top = "-40px";
        } else {
            select(".TopBar").style.top = "0px";
        }

        if (screen.width >= 390) {
            if (scroll > 50) {
                FilterControl.style.top = "70px";
            } else {
                FilterControl.style.top = "110px";
            }
        }
    })

    const getEvents = (reRender = true) => {
        if (reRender) {
            renderEventCard(null, "#render", "IB");
        }
        post(`/api/event/explore?page=${pageIndex}`, filters)
        .then(res => {
            if (res.events.next_page_url == null) {
                select("button#LoadMore").classList.add('d-none');
            } else {
                select("button#LoadMore").classList.remove('d-none');
            }
            if (reRender) {
                select("#render").innerHTML = "";
                clearInterval(cardAnimationInterval["#render"]);
            }
            console.log(res.events.data);
            res.events.data.forEach(event => {
                renderEventCard(event, "#render", "IB");
            })
        })
    }
    const loadMore = () => {
        pageIndex++;
        getEvents((pageIndex - 1) % 3 == 0 ? true : false);
    }
    const toggleFilterControl = () => {
        FilterControl.classList.toggle('active');
    }
    
    getEvents()
</script>
@endsection