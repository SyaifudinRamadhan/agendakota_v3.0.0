<!DOCTYPE html>
<html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?php echo csrf_token() ?>">
    <title>Cetak Ticket - AgendaKota</title>
    
    <!-- Font -->
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  
    
    
    <title>Print Ticket</title>

    <style>
        .teks-finished{
            color:#BDBDBD;
            width: 95px;
            height: 25px;
            margin-top: -6%;
            margin-left: 3%;
        }
        .teks-finished p {
            font-size: 14px;
        }
        .teks-upcoming{
            color:#EB597B;
            width: 95px;
            height: 25px;
            margin-top: -6%;
            margin-left: 3%;
        }
        .teks-upcoming p {
            font-size: 14px;
        }
        .teks-happening{
            background: #EB597B;
            border-radius: 25px;
            color: #FFFFFF;
            width: 95px;
            height: 25px;
            margin-top: -2%;
            margin-left: 3%;
        }
        .teks-happening p {
            font-size: 14px;
            margin-top: -0px;
            margin-left: 10px;
            padding: 2px;
        }

        .ticket {            
            margin-top: 3%;
            position: relative;
            border: 1.5pt solid #E1E5E8;
            display: inline-block;
            padding: 2em 2em;
            width: 280%;
            height: 100%;
            border-radius: 10px;

        }                

        .garis-tiket{
            border-top: 2px solid;            
        }

        .price{
            font-size: 15px;
            color:#EB597B;
            margin-top: -5%;
        }
        .ticket-name{
            font-size: 20px;
            color:black;
            text-transform: uppercase;
        }

        :root {
		    --firstColor: #7a53a2;
		    --secondColor: #ac3643;
		    --primaryColor: #eb597b;
		}
		@font-face {
		    font-family: DM_Sans !important;
		    src: url(../fonts/ProReg.ttf);
		}
		@font-face {
		    font-family: DM_Sans !important;
		    src: url(../fonts/ProBold.otf);
		}
		@font-face {
		    font-family: ProLight;
		    src: url(../fonts/ProLight.otf);
		}
		@font-face {
		    font-family: DM_Sans !important;
		    src: url(../fonts/Roboto/Roboto-Regular.ttf);
		}
		@font-face {
		    font-family: DM_SansBold !important;
		    src: url(../fonts/Roboto/Roboto-Bold.ttf);
		}
		@font-face {
		    font-family: DM_Sans !important;
		    src: url(../fonts/Roboto/Roboto-Black.ttf);
		}
		@font-face {
		    font-family: DM_Sans !important;
		    src: url(../fonts/Roboto/Roboto-Light.ttf);
		}
		@font-face {
		    font-family: DM_Sans !important;
		    src: url(../fonts/Roboto/Roboto-Thin.ttf);
		}
		@font-face {
		    font-family: DM_Sans !important;
		    src: url(https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;600;700&display=swap);
		}
		body {
		    background-color: #ecf0f1;
		    color: #444;
		    font-family: DM_Sans !important;
		}
		.font-inter {
		    font-family: DM_Sans !important;
		}
		.wrap { margin: 5%; }
		.wrap.super { margin: 10%; }
		.wrap.small { margin: 2.5%; }
		.bag-10 > .wrap { margin: 3.5% 5%; }
		h1,h2,h3,h4,h5,h6 {
		    font-family: DM_Sans !important;
		    color: #666;
		}
		a { text-decoration: none;color: var(--firstColor); }
		p { font-family: DM_Sans !important;font-size: 18px;  }
		b { font-family: DM_SansBold !important; }
		.fontLight { font-family: DM_Sans !important; }
		.fontRegular { font-family: DM_Sans !important; }
		.fontBold { font-family: DM_SansBold !important; }

		.relative { position: relative; }

		pre {
		    white-space: pre-wrap;
		    word-wrap: break-word;
		    font-family: DM_Sans !important;
		    line-height: 36px;
		    font-size: 20px;
		}

		div { transition: 0.4s; }

		input[type=text],input[type=email],input[type=password],input[type=number] {
		    height: 50px;
		    font-size: 17px;
		    transition: 0.4s;
		}
		.box {
		    width: 100%;
		    height: 50px;
		    padding: 0px;
		    font-size: 17px;
		    color: #444;
		    border: 1px solid #ddd;
		    outline: none;
		    padding: 0px 15px;
		    transition: 0.4s;
		    box-sizing: border-box;
		    border-radius: 6px;
		    margin-top: 12px;
		}
		.box[readonly] {
		    background-color: #ecf0f1;
		    cursor: not-allowed;
		}
		.box-2 {
		    border: 2px solid #fff;
		    border-radius: 250px;
		    background: none;
		    width: 88.8%;
		    padding: 0px 25px;
		    color: #fff;
		}
		.box-2::-webkit-input-placeholder { color: #fff; }
		.box:focus { box-shadow: 1px 1px 10px 1px #ddd; }
		.box-2:focus {
		    background-color: #fff;
		    color: #444;
		    outline: none;
		}
		textarea.box { height: 120px;font-family: DM_Sans !important;padding: 15px; }
		select.box {
		    background-color: #fff;
		    border: none;
		    border: 1px solid #ddd;
		    border-radius: 6px;
		    padding: 0px 15px;
		}
		select.box:focus {
		    border: none;
		    box-shadow: 1px 1px 5px #3498db;
		}

		.bag {
			display: inline-block;
			vertical-align: top;
			margin: -2px;
			margin-bottom: 20px;
		}
		.bag-1 { width: 10%; }
		.bag-2 { width: 20%; }
		.bag-3 { width: 30%; }
		.bag-4 { width: 40%; }
		.bag-5 { width: 50%; }
		.bag-6 { width: 60%; }
		.bag-7 { width: 70%; }
		.bag-8 { width: 80%; }
		.bag-9 { width: 90%; }
		.bag-10 { width: 100%; }

		.bagi { display: inline-block;margin: -2px;vertical-align: top !important;box-sizing: border-box; }
		.bagi-2 { width: 50%; }
		.bagi-3 { width: 33.3%; }
		.bagi-3-setengah { width: 28.5%; }
		.bagi-3-perempat { width: 30.7%; }
		.bagi-4 { width: 25%; }
		.bagi-5 { width: 20%; }

		.bg-putih { background-color: #fff;color: #444; }
		.bg-merah { background-color: #e74c3c;color: #fff; }
		.bg-kuning { background-color: #fcd840;color: #444; }
		.bg-merah-transparan { background-color: #e74c3c30;color: #c0392b; }
		.bg-hijau { background-color: #2ecc71;color: #fff; }
		.bg-hijau-transparan { background-color: #2ecc7130;color: #27ae60; }
		.bg-biru { background-color: #2196f3;color: #fff; }
		.bg-biru-transparan { background-color: #3498db30;color: #2980b9; }
		.bg-oren { background-color: #f15b2d;;color: #fff; }
		.bg-oren-transparan { background-color: #d8491d30;color: #f15b2d; }
		.bg-primer {
		    background-color: #eb597b;
		    color: #fff;
		}

		.teks-merah { color: #e74c3c; }
		.teks-hijau { color: #2ecc71; }
		.teks-biru { color: #3498db; }
		.teks-oren { color: #f15b2d;; }
		.teks-primer { color: #eb597b; }
		.teks-hitam { color: #444; }
		.teks-emas { color: #ffd700; }

		.teks-besar { font-size: 24px; }

		.bayangan-5 { box-shadow: 1px 1px 5px 1px #ddd; }
		.bayangan-1 { box-shadow: 1px 1px 1px 1px #ddd; }

		.rounded { border-radius: 5px; }
		.rounded-circle { border-radius: 999px; }
		.corner-top-left { border-top-left-radius: 6px; }
		.corner-top-right { border-top-right-radius: 6px; }
		.corner-bottom-left { border-bottom-left-radius: 6px; }
		.corner-bottom-right { border-bottom-right-radius: 6px; }

		.border-primer { border-style: solid; border-width: 1px; padding: 10px; border-color: #eb597b; }

		button {
		    padding: 12px 25px;
		    border-radius: 5px;
		    border: none;
		    height: 50px;
		    color: #333;
		    cursor: pointer;
		    transition: 0.4s;
		    font-size: 15px;
		    background: none;
		    border: 1px solid #ddd;
		    font-family: DM_SansBold !important;
		    color: #eb597b;
		}
		.no-border {
		    border: none !important;
		}
		button.disabled { cursor: not-allowed; }
		button.biru {
		    background-color: #2196f3;
		    color: #fff;
		}
		button.biru:hover { background-color: #2980b9; }
		button.biru:focus {
		    outline: none;
		    box-shadow: 1px 1px 5px 5px #2196f380;
		}
		button.merah:focus {
		    outline: none;
		    box-shadow: 1px 1px 5px 5px #c0392b80;
		}
		button.primer {
		    background-color: #eb597b;
		    color: #fff;
		}
		button.primer:hover { background-color: #d84b6c; }
		button.primer:focus {
		    outline: none;
		    box-shadow: 1px 1px 5px 5px #eb597b80;
		}
		button.kuning {
		    background-color: #fcd840;
		    color: #444;
		}
		button.kuning:hover { background-color: #d4b62c; }
		button.kuning:focus {
		    outline: none;
		    box-shadow: 1px 1px 5px 1px #d4b62c30;
		}

		button.biru-alt {
		    background: none;
		    color: #2196f3;
		}
		button.biru-alt:hover { background-color: #2196f340; }
		button.merah-alt {
		    background: none;
		    color: #e74c3c;
		}
		button.merah-alt:hover { background-color: #e74c3c40; }
		button.oren-alt {
		    background: none;
		    color: #f15b2d;
		}
		button.oren-alt:hover { background-color: #f15b2d40; }
		button.kuning-alt {
		    background: none;
		    color: #fcd840;
		}
		button.kuning-alt:hover { background-color: #fcd84030; }
		button.kuning-alt:focus { outline: none; }

		button.no-style {
		    color: #444;
		    font-family: DM_Sans !important;
		    background: none;
		}
		button.hijau {
		    background-color: #2ecc71;
		    color: #fff;
		}
		button.hijau:hover { background-color: #27ae60; }
		button.hijau-alt {
		    background: none;
		    color: #2ecc71;
		}
		button.hijau-alt:hover {
		    background-color: #2196f340;
		}
		button.oren {
		    background-color: #f15b2d;
		    color: #fff;
		}
		button.oren:hover { background-color: #d8491d; }
		button.merah {
		    background-color: #e74c3c;
		    color: #fff;
		}
		button.merah:hover { background-color: #c0392b; }

		a.oren { color: #f15b2d; }

		/* pagination */
		/*
		.page-item {
		    display: inline-block;
		    background-color: #0a5e6a;
		    color: #fff;
		    padding: 15px 20px;
		    border-radius: 90px;
		}
		.page-item a {
		    color: #fff;
		    text-decoration: none;
		}
		.page-item.active {
		    font-family: DM_SansBold !important;
		    cursor: normal;
		}
		*/

		/* tabel */
		table {
		    width: 100%;
		    font-family: DM_Sans !important;
		}
		table td,table th {
		    border-bottom: 1px solid #ddd;
		    padding: 15px 10px;
		}
		table th { text-align: left; }
		table td form { display: inline-block; }

		/* text */
		.rata-tengah { text-align: center; }
		.rata-kiri { text-align: left; }
		.rata-kanan { text-align: right; }

		.teks-putih { color: #fff; }
		.teks-gelap { color: #444; }
		.teks-transparan { color: #777; }
		.teks-tebal { font-family: DM_SansBold !important; }
		.teks-tipis { font-family: DM_Sans !important;}
		.teks-kecil { font-size: 80%; }
		.teks-mini { font-size: 50%; }
		.teks-micro { font-size: 30%; }

		.ke-kanan { float: right; }
		.ke-kiri { float: left; }

		/* MARGINS */

		/* margin */
		.m-0 { margin: 0px; }
		.m-1 { margin: 10px; }
		.m-2 { margin: 20px; }
		.m-3 { margin: 30px; }
		.m-4 { margin: 40px; }
		.m-5 { margin: 50px; }
		/* margin top */
		.mt-0 { margin-top: 0px; }
		.mt-1 { margin-top: 10px; }
		.mt-2 { margin-top: 20px; }
		.mt-3 { margin-top: 30px; }
		.mt-4 { margin-top: 40px; }
		.mt-5 { margin-top: 50px; }
		/* margin bottom */
		.mb-0 { margin-bottom: 0px; }
		.mb-1 { margin-bottom: 10px; }
		.mb-2 { margin-bottom: 20px; }
		.mb-3 { margin-bottom: 30px; }
		.mb-4 { margin-bottom: 40px; }
		.mb-5 { margin-bottom: 50px; }
		/* margin left */
		.ml-0 { margin-left: 0px; }
		.ml-1 { margin-left: 10px; }
		.ml-2 { margin-left: 20px; }
		.ml-3 { margin-left: 30px; }
		.ml-4 { margin-left: 40px; }
		.ml-5 { margin-left: 50px; }
		.ml-10 { margin-left: 10%; }
		.ml-20 { margin-left: 20%; }
		.ml-25 { margin-left: 25%; }
		.ml-30 { margin-left: 30%; }
		.ml-40 { margin-left: 40%; }
		.ml-50 { margin-left: 50%; }
		.ml-60 { margin-left: 60%; }
		.ml-70 { margin-left: 70%; }
		.ml-75 { margin-left: 75%; }
		.ml-80 { margin-left: 80%; }
		.ml-90 { margin-left: 90%; }
		.ml-100 { margin-left: 100%; }
		/* margin right */
		.mr-0 { margin-right: 0px; }
		.mr-1 { margin-right: 10px; }
		.mr-2 { margin-right: 20px; }
		.mr-3 { margin-right: 30px; }
		.mr-4 { margin-right: 40px; }
		.mr-5 { margin-right: 50px; }

		/* PADDINGS */
		.smallPadding { padding: 1px; }

		/* padding */
		.p-0 { padding: 0px; }
		.p-1 { padding: 10px; }
		.p-2 { padding: 20px; }
		.p-3 { padding: 30px; }
		.p-4 { padding: 40px; }
		/* padding top */
		.pt-1 { padding-top: 10px; }
		.pt-2 { padding-top: 20px; }
		.pt-3 { padding-top: 30px; }
		.pt-4 { padding-top: 40px; }
		.pt-5 { padding-top: 50px; }
		/* padding bottom */
		.pb-1 { padding-bottom: 10px; }
		.pb-2 { padding-bottom: 20px; }
		.pb-3 { padding-bottom: 30px; }
		.pb-4 { padding-bottom: 40px; }
		.pb-5 { padding-bottom: 50px; }
		/* padding left */
		.pl-1 { padding-left: 10px; }
		.pl-2 { padding-left: 20px; }
		.pl-3 { padding-left: 30px; }
		.pl-4 { padding-left: 40px; }
		.pl-5 { padding-left: 50px; }
		/* padding right */
		.pr-1 { padding-right: 10px; }
		.pr-2 { padding-right: 20px; }
		.pr-3 { padding-right: 30px; }
		.pr-4 { padding-right: 40px; }
		.pr-5 { padding-right: 50px; }

		/* width */
		.lebar-100 { width: 100%; }
		.lebar-95 { width: 95%; }
		.lebar-90 { width: 90%; }
		.lebar-85 { width: 85%; }
		.lebar-80 { width: 80%; }
		.lebar-75 { width: 75%; }
		.lebar-70 { width: 70%; }
		.lebar-65 { width: 65%; }
		.lebar-60 { width: 60%; }
		.lebar-55 { width: 55%; }
		.lebar-50 { width: 50%; }
		.lebar-45 { width: 45%; }
		.lebar-40 { width: 40%; }
		.lebar-35 { width: 35%; }
		.lebar-30 { width: 30%; }
		.lebar-25 { width: 25%; }
		.lebar-20 { width: 20%; }
		.lebar-15 { width: 15%; }
		.lebar-10 { width: 10%; }
		.lebar-5 { width: 5%; }

		/* height */
		.tinggi-600 { height: 600px; }
		.tinggi-550 { height: 550px; }
		.tinggi-500 { height: 500px; }
		.tinggi-450 { height: 450px; }
		.tinggi-400 { height: 500px; }
		.tinggi-350 { height: 350px; }
		.tinggi-300 { height: 300px; }
		.tinggi-285 { height: 285px; }
		.tinggi-250 { height: 250px; }
		.tinggi-200 { height: 200px; }
		.tinggi-150 { height: 150px; }
		.tinggi-100 { height: 100px; }
		.tinggi-95 { height: 95px; }
		.tinggi-90 { height: 90px; }
		.tinggi-85 { height: 85px; }
		.tinggi-80 { height: 80px; }
		.tinggi-75 { height: 75px; }
		.tinggi-70 { height: 70px; }
		.tinggi-65 { height: 65px; }
		.tinggi-60 { height: 60px; }
		.tinggi-55 { height: 55px; }
		.tinggi-50 { height: 50px; }
		.tinggi-45 { height: 45px; }
		.tinggi-40 { height: 40px; }
		.tinggi-35 { height: 35px; }
		.tinggi-30 { height: 30px; }
		.tinggi-25 { height: 25px; }
		.tinggi-20 { height: 20px; }
		.tinggi-15 { height: 15px; }
		.tinggi-10 { height: 10px; }
		.tinggi-5 { height: 5px; }

		/* display */
		.d-block { display: block; }
		.d-none { display: none; }
		.d-inline-block { display: inline-block; }

		/* border */
		.bordered { border: 1px solid #ddd; }
		.border-white { border: 1px solid #fff; }
		.border-bottom { border-bottom: 1px solid #ddd; }
		.border-bottom-2 { border-bottom: 2px solid #ddd; }
		.border-top { border-top: 1px solid #ddd; }

		/* popup */
		.bg {
		    position: fixed;
		    top: 0px;left: 0px;right: 0px;bottom: 0px;
		    background-color: #00000075;
		    z-index: 9;
		    display: none;
		}
		.popupWrapper {
			display: none;
			position: fixed;
			top: 0px;left: 0%;right: 0%;bottom: 0px;
			overflow: auto;
			transition: 0.4s;
		    z-index: 10;
		    text-align: center;
		}
		.popupWrapper::-webkit-scrollbar {
		    /* width: 1px; */
		}
		.popup {
		    display: inline-block;
		    text-align: left;
		    border-radius: 6px;
			width: 40%;
			background-color: #fff;
			margin-bottom: 75px;
		    padding-bottom: 10px;
		    margin-top: 80px;
			color: #444;
			transition: 0.4s;
		}
		.popup .box { width: 100%; }
		.popup p {
			font-size: 20px;
			font-family: DM_Sans !important;
			line-height: 42px;
		}
		.bag-tombol { margin-top: 13px; }
		.bag-tombol button {
			width: 100%;
			height: 44px;
			border: none;
			font-size: 21px;
			cursor: pointer;
			transition: 0.4s;
		}
		.bag-tombol button i { margin-right: 9px; }

		.pointer { cursor: pointer; }

		.mobile { display: none; }
		.desktop { display: block; }

		.overflowAuto { overflow: auto; }

		/*
		body .pagination_links .items {
		    display: inline-block !important;
		    padding: 10px 15px !important;
		    border: 2px solid #2ecc71 !important;
		    margin-right: 12px !important;
		    margin-top: 20px !important;
		    border-radius: 900px !important;
		}
		body .pagination_links a {
		    color: #2ecc71 !important;
		}
		body .pagination_links .items.active {
		    background-color: #2ecc71 !important;
		    color: #fff !important;
		}
		*/

		@media(max-width: 480px) {
		    .desktop { display: none; }
		    .mobile { display: block; }
		    .mobile-inline-block { display: block; }

		    .bagi { display: block;width: 100% !important;margin-top: 10px; }
		    .bagi.bagi-1,
		    .bagi.bagi-2,
		    .bagi.bagi-3,
		    .bagi.bagi-4,
		    .bagi.bagi-5,
		    .bagi.bagi-6,
		    .bagi.bagi-7,
		    .bagi.bagi-8,
		    .bagi.bagi-9,
		    .bagi.bagi-10
		     {
		        width: 100%;
		        padding: 1px;
		    }
		    .popupWrapper .popup {
		        left: 5%;right: 5%;
		        width: 90%;
		    }
		}

		.ukuran-homepage{
		    height: 265px;
		    width: 294px;
		}
		.logo-homepage{
		    height: 165px;
		    border-top-left-radius: 6px;
		    width: 294px;
		    border-top-right-radius: 6px;
		}
		.logo {
		    height: 250px;
		    border-top-left-radius: 6px;
		    border-top-right-radius: 6px;
		}
		.label {
		    border-top-right-radius: 26px;
		    border-bottom-right-radius: 26px;
		    display: inline-block;
		    margin-top: 15px;
		    padding: 8px 15px;
		}
		.partial-image{
		    width: 100px;
		    display: block;
		    margin-left: auto;
		    margin-right: auto;
		    position: relative;
		    padding-top: 35px;
		    padding-bottom: 35px;
		}
		.tab {
		overflow: hidden;
		border: 1px solid #ccc;
		}

		/* Style the buttons inside the tab */
		.tab button {
		background-color: inherit;
		border: none;
		outline: none;
		cursor: pointer;
		padding: 14px 16px;
		transition: 0.3s;
		font-size: 17px;
		}

		/* Change background color of buttons on hover */
		.tab button:hover {
		background-color: #ddd;
		}

		/* Create an active/current tablink class */
		.tab button.active {
		    border-bottom:5px solid #EB597B;
		}

		/* Style the tab content */
		.tabcontent, .tabcontent-modal {
		display: none;
		padding: 6px 12px;
		border: 1px solid #ccc;
		border-top: none;
		}
		.partial-image{
		    width: 100px;
		    display: block;
		    margin-left: auto;
		    margin-right: auto;
		    position: relative;
		    padding-top: 35px;
		    padding-bottom: 35px;
		}




    </style>
</head>

<body>

<?php

    use Carbon\Carbon;
    $tanggalsekarang= Carbon::now()->toDateString(); 

 ?>



		<?php if($data['type'] == 'qrcode')	{?>
			<div class="alert alert-primary mb-0">
				<h3 class="text-center mb-0">QR Code Ticket</h3>
			</div>
			
                <div class="justify-content-between" style="width: 660px; height: 300px;">
                    
                    <div class="ticket col-md" id="pilih-ticket" ticket-id="">  

                        <div class="bagi bagi-3 text-center">
                            <div class="d-flex">
                                <h4 class="mx-auto"><?php echo $data['purchase']->events->name ?></h4>                                  
                            </div>  
                            <span class="teks-kecil"><?php echo $data['invoiceID'] ?></span>                        
                            <div class="d-flex justify-content-center mt-2 mb-2">
                                <img src="data:image/png;base64,<?php echo BarCode2::getBarcodePNG($data['invoiceID'], 'QRCODE', 8,8) ?>" alt="barcode" /> 
                            </div>                              
                                                     
                        </div>   

                        <div class="bagi bagi-3 mb-0">
                            <label class="text-secondary mb-1">Nama Tiket</label>
                            <b class="d-flex mb-5"><?php echo $data['purchase']->tickets->name ?></b>   
                            
                            <label class="text-secondary mb-1">Tanggal</label>
                            <b class="d-flex mb-5"> <?php echo Carbon::parse($data['purchase']->events->start_date)->format('d M,') ?> <?php echo Carbon::parse($data['purchase']->events->start_time)->format('H:i') ?> WIB -
                                <?php echo Carbon::parse($data['purchase']->events->end_date)->format('d M,') ?> <?php echo Carbon::parse($data['purchase']->events->end_time)->format('H:i') ?> WIB</b>   

                            <label class="text-secondary mb-1">Hosted By</label>
                            <b class="d-flex mb-5"><?php echo $data['purchase']->events->organizer->name ?></b>   
                        </div>

                        <div class="bagi bagi-3 mb-0">
                            <label class="text-secondary mb-1">Kode Invoice</label>
                            <b class="d-flex mb-5"><?php echo $data['invoiceID'] ?></b>                                                           

                            <label class="text-secondary mb-1">Metode Pembayaran</label>
                            <b class="d-flex mb-5">Transfer via Midtrans</b>   

                            <label class="text-secondary mb-1">Status</label>
                            <?php if($data['purchase']->pay_state == 'Terbayar'){ ?>
                                <div class="alert alert-success text-success alert-dismissible fade show col-md-2 pt-1 pb-1 pl-3 mb-5" role="alert">
                                    <strong>Paid</strong>
                                </div>
                            <?php }else{ ?>
                                <div class="alert alert-danger text-success alert-dismissible fade show col-md-2 pt-1 pb-1 pl-3 mb-5" role="alert">
                                    <strong>Belum Dibayar</strong>
                                </div>
                            <?php } ?>
                        </div>
                                                                
                        <div class="mt-0">
                            <hr class="garis-tiket" style="color: lightgray;"/>                            
                            <p class="text-secondary mt-3">Description : <br><?php echo $data['purchase']->tickets->description ?></p>
                        </div>

                        <div class="bagi bagi-3">
                            <label class="text-secondary mb-1">Nama</label>
                            <b class="d-flex"><?php echo $data['myData']->name ?></b>                                                           
                        </div> 

                        <div class="bagi bagi-3">
                            <label class="text-secondary mb-1">Email</label>
                            <b class="d-flex"><?php echo $data['myData']->email ?></b> 
                        </div> 
                        
                        <div class="bagi bagi-3">
                            <label class="text-secondary mb-1">No. Telpon</label>
                            <b class="d-flex"><?php echo $data['myData']->phone ?></b> 
                        </div> 
                    </div>
                </div>
            <br>

            <?php } else if($data['type'] == 'barcode'){ ?>
			<div class="alert alert-primary mb-0">
				<h3 class="text-center mb-0">Barcode Ticket</h3>
			</div>
			<br>               

                <div class="mt-0 justify-content-between" style="width: 660px; height: 300px;">
                    
                    <div class="ticket col-md" id="pilih-ticket" ticket-id="">  

                        <div class="bagi bagi-3 text-center">
                            <div class="d-flex">
                                <h4 class="mx-auto"><?php echo $data['purchase']->events->name ?></h4>                                  
                            </div>  
                            <div class="d-flex justify-content-center mt-2 mb-2">
                                <img src="data:image/png;base64,<?php echo BarCode1::getBarcodePNG($data['invoiceID'], 'C39',2,100) ?>" alt="barcode" />                                
                            </div>  
                            <h4 class="justify-content-center d-flex mb-2"><?php echo $data['invoiceID'] ?></h4>                        
                                                     
                        </div>   

                        <div class="bagi bagi-3 mb-0">
                            <label class="text-secondary mb-1">Nama Tiket</label>
                            <b class="d-flex mb-5"><?php echo $data['purchase']->tickets->name ?></b>   
                            
                            <label class="text-secondary mb-1">Tanggal</label>
                            <b class="d-flex mb-5"> <?php echo Carbon::parse($data['purchase']->events->start_date)->format('d M,') ?> <?php echo Carbon::parse($data['purchase']->events->start_time)->format('H:i') ?> WIB -
                                <?php echo Carbon::parse($data['purchase']->events->end_date)->format('d M,') ?> <?php echo Carbon::parse($data['purchase']->events->end_time)->format('H:i') ?> WIB</b>   

                            <label class="text-secondary mb-1">Hosted By</label>
                            <b class="d-flex mb-5"><?php echo $data['purchase']->events->organizer->name ?></b>   
                        </div>

                        <div class="bagi bagi-3 mb-0">
                            <label class="text-secondary mb-1">Kode Invoice</label>
                            <b class="d-flex mb-5"><?php echo $data['invoiceID'] ?></b>                                                           

                            <label class="text-secondary mb-1">Metode Pembayaran</label>
                            <b class="d-flex mb-5">Transfer via Midtrans</b>   

                            <label class="text-secondary mb-1">Status</label>
                            <?php if($data['purchase']->pay_state == 'Terbayar'){ ?>
                                <div class="alert alert-success text-success alert-dismissible fade show col-md-2 pt-1 pb-1 pl-3 mb-5" role="alert">
                                    <strong>Paid</strong>
                                </div>
                            <?php }else{ ?>
                                <div class="alert alert-danger text-success alert-dismissible fade show col-md-2 pt-1 pb-1 pl-3 mb-5" role="alert">
                                    <strong>Belum Dibayar</strong>
                                </div>
                            <?php } ?>
                        </div>
                                                                
                        <div class="mt-0">
                            <hr class="garis-tiket" style="color: lightgray;"/>                            
                            <p class="text-secondary mt-3">Description : <br><?php echo $data['purchase']->tickets->description ?></p>
                        </div>
                        <div class="bagi bagi-3">
                            <label class="text-secondary mb-1">Nama</label>
                            <b class="d-flex"><?php echo $data['myData']->name ?></b>                                                           
                        </div> 

                        <div class="bagi bagi-3">
                            <label class="text-secondary mb-1">Email</label>
                            <b class="d-flex"><?php echo $data['myData']->email ?></b> 
                        </div> 
                        
                        <div class="bagi bagi-3">
                            <label class="text-secondary mb-1">No. Telpon</label>
                            <b class="d-flex"><?php echo $data['myData']->phone ?></b> 
                        </div> 
                    </div>
                </div>

            <hr><br>
			<hr> 

			<?php } ?>  

</body>
</html>
    