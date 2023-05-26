<!DOCTYPE html>
<html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <title>Cetak Ticket - Agendakota</title>

    <!-- Font -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Print Ticket</title>

    <style>
        /* ------------------------------------------------------ */
        #htmlContent{
            display: flex;
        }
        .main-paper{
            width: 541px;
            height: 793px;
            background-color: #D9D9D9;
            margin: auto;
            padding: 10px;
            transform: translate(0px, 120px) scale(1.3);
        }
        .box-inner{
            background-color: #fff;
            width: 100%;
            height: 100%;
            max-width: unset;
        }
        .row{
            margin: unset;
            padding: unset;
            display: inline-flex;
        }
        .col{
            position: absolute;
        }
        .box-1{
            width: 100%;
            height: 30px;
            margin-bottom: 10px;
        }
        .box-2{
            width: 321px;
            height: 165px;
            display: inline-block;
        }
        .box-3{
            width: 189px;
            height: 60px;
            margin-left: 10px;
            right: 0;
            text-align: center
        }
        .box-4{
            height: 112px;
            width: 300px;
            margin-top: 10px;
            padding: 16px;
        }
        .box-5{
            height: 152px;
            width: 189px;
            margin-top: 10px;
            margin-left: 10px;
        }
        .box-6{
            height: 73px;
            width: 195px;
            display: flex;
            margin-top: 10px;
            margin-left: 10px;
            padding: 2px;
        }
        .box-7{
            width: 100%;
            height: 65px;
            margin-bottom: 10px;
            margin-top: 10px;
        }
        .box-8{
            position: absolute;
            width: 540px;
            height: 32px;
            margin-bottom: 10px;
            top: 390px;
        }
        .box-9{
            width: 540px;
            height: 85px;
            margin-bottom: 10px;
            /* position: absolute; */
            margin-top: 425px;
            /* margin-top: 10px; */
        }
        .top-col-1{
            width: 321px;
            max-width: unset;
            margin: unset;
            padding: unset;
            /* margin-top: 30px */
        }
        .top-col-2{
            width: 189px;
            max-width: unset;
            margin: unset;
            padding: unset;
            left: 422px;
            /* margin-top: 30px; */
        }
        .row-mid{
            height: 39px;
            width: 100%;
            margin-bottom: 10px;
            position: absolute;
            top: 432px;
        }
        .col-mid{
            max-width: unset;
            margin: unset;
            padding: unset;
            width: 166.5px;
            position: absolute;
        }
        .ml-5{
            margin-left: 10px;
        }
        .fs-8{
            font-size: 8px;
            text-decoration: none;
            color: black;
        }
        .fs-10{
            font-size: 10px;
            text-decoration: none;
            color: black;
        }.fs-24{
            font-size: 24px;
            text-decoration: none;
            color: black;
        }
        .fs-15{
            font-size: 15px;
            text-decoration: none;
            color: black;
        }
        .fs-12{
            font-size: 12px;
            text-decoration: none;
            color: black;
        }
        a:hover{
            color: unset;
        }
        .text-prm{
            color: #e5214f; /*#EB597B*/
        }
        .text-prm:hover{
            color: #e5214f; /*#EB597B*/
        }
        .pt-0-2{
            padding-top: 2px;
        }
        .img-box-1{
            padding-top: 11px;
            padding-bottom: 11px;
            padding-right: 18px;
            padding-left: 18px;
        }
        .img-center{
            justify-content: center;
            display: flex;
            padding: 5px 5px 5px 5px;
        }
        .img-code{
            display: flex;
            text-align: center;
        }
        .bi-envelope-fill{
            color: #3498DB;
        }
        .bi-telephone-fill{
            color: green;
        }
        .mail-box{
            width: 80%;
            overflow: hidden;
            display: inline-flex;
            text-overflow: ellipsis;
        }
        .overflow-wrap{
            overflow-wrap: anywhere;
        }
    </style>
    
</head>


<body class="justify-content-center d-flex" style="background-color: unset;">

    <?php
    use Carbon\Carbon;
    setlocale(LC_ALL, 'id_ID');
    Carbon::setLocale('id');
    ?>

    <div id="htmlContent" class="container">

        <div class="main-paper">
            <div class="box-inner box-1 pt-0-2">
                <a class="fs-12 ml-2 fw-bold">TICKET TYPE : </a>
                <a class="fs-12 fw-bold text-prm">{{ $purchase->events->name }},  {{ $purchase->events->city }} </a>
                <a class="fs-12 fw-bold">(@currencyEncode($purchase->price))</a>
            </div>
            <div class="row">
                <div class="col top-col-1">
                    <div class="row">
                        <div class="col-12 box-inner box-2 img-box-1 img-center">
                            <img width="100%" height="100%" src="{{ public_path('storage/event_assets/' . $purchase->events->slug . '/event_logo/thumbnail/' . $purchase->events->logo) }}" alt="">
                        </div>
                        <div class="col-12 box-inner box-4">
                            <p class="fs-15 fw-bold">{{ $purchase->events->name }}</p>
                            <p class="fs-10 mb-2">
                                <i class="bi bi-calendar-event fs-12"></i> {{ Carbon::parse($purchase->tickets->session->start_date)->isoFormat('dddd, D MMMM Y') }} {{ Carbon::parse($purchase->tickets->session->start_time)->format('H:m') }} - {{ Carbon::parse($purchase->tickets->session->end_time)->format('H:m') }} WIB
                            </p>
                            <p class="fs-10 mb-2">
                                <i class="bi bi-pin-map fs-12"></i> {{ $purchase->events->execution_type }} event
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col top-col-2">
                    <div class="row">
                        <div class="col-12 box-inner box-3 img-center">
                            <img style="aspect-ratio: 1/1;" height="100%" src="{{ $purchase->events->organizer->logo == '' ? public_path('storage/organization_logo/default_logo.png') : public_path('storage/organization_logo/' . $purchase->events->organizer->logo) }}" alt="">
                        </div>
                        <div class="col-12 box-inner box-5 img-center img-code">
                            @if ($type == 'barcode')
                                <img width="100%" height="30%" style="margin: auto; margin-top: 50px;" src="data:image/png;base64,{{BarCode1::getBarcodePNG($invoiceID.'-'.$myData->id, 'C39',2,100)}}" class="img-barcode" alt="barcode" />
                            @else
                                <img style="aspect-ratio: 1/1;" height="100%" src="data:image/png;base64,{{BarCode2::getBarcodePNG($invoiceID.'-'.$myData->id, 'QRCODE', 8,8)}}" class="img-code" alt="barcode" /> 
                            @endif
                        </div>
                        <div class="col-12 box-inner box-6">
                            <div style="margin-top: 10px;">
                                <p class="text-center fs-8 mb-0">{{ $purchase->payment->order_id }}</p>
                                <p class="text-center fs-8 mb-0">{{ $purchase->users->name }}</p>
                                <p class="text-center fs-8 mb-0">Dipesan pada {{ Carbon::parse($purchase->payment->created_at)->isoFormat('D MMMM Y') }}</p>
                                <p class="text-center fs-8 mb-0">Ref: Online </p>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- </div>
            <div class="box-inner box-7">
                <img src="{{ public_path('images/pdf/Group 4.png') }}" width="100%" height="100%" alt="">
            </div> --}}
            <div class="box-inner box-8">
                <img src="{{ public_path('images/pdf/Group 3.png') }}" width="100%" height="100%" alt="">
            </div>
            <div class="row row-mid">
                <div class="col-4 col-mid bg-white p-2 fs-10">
                    <i class="bi bi-instagram fs-15"> </i> 
                    <div class="mail-box">
                        <a style="text-decoration: none" class="text-dark" href="{{ $purchase->events->organizer->instagram }}">{{ $purchase->events->organizer->instagram }}</a>
                    </div>
                </div>
                <div class="col-4 col-mid bg-white ml-5 p-2 fs-12" style="left: 143.5px">
                    <i class="bi bi-telephone-fill fs-15"> </i> {{ $purchase->events->organizer->no_telepon }}
                </div>
                <div class="col-4 col-mid bg-white ml-5 p-2 fs-10" style="overflow: hidden; left: 335px; width: 141.3px">
                    <i class="bi bi-envelope-fill fs-15"> </i> 
                    <div class="mail-box">
                        <a style="text-decoration: none" class="text-dark" href="mailto:{{ $purchase->events->organizer->email }}">{{ $purchase->events->organizer->email }}</a>
                    </div>
                </div>
            </div>
            <div class="box-inner box-9">
                <img src="{{ public_path('images/pdf/Group 2.png') }}" width="100%" height="100%" alt="">
            </div>
        </div>
        <script></script>
</body>

</html>
