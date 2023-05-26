// JS Native
// function chatListClick(obj) {
//     var id = obj.dataset.id;
//     var nama = obj.dataset.nama;
//     var email = obj.dataset.email;
//     console.log(id + '. ' + nama + ' => ' + email);
// }
//jQuery

if (
    /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
        navigator.userAgent
    )
) {
    console.log("mobile");
    $(".content-no-msg").remove();
    $(".messages-kiri").removeClass("col-md-5");
    $(".messages-kiri").addClass("col");
    $("#add-msg").addClass("add-msg-collapse");
}

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
   
    // Get Data Pesan Terakhir
    setInterval(function () {
        $(".id-user").each(function () {
            var id = $(this).attr("data-id");
            var senderID = "";
            $.ajax({
                url: "/messages/latest/" + id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    $("#latest" + id).text(data.message);
                    $("#latest" + id).text(
                        $("#latest" + id)
                            .text()
                            .substr(0, 40) + "..."
                    );
                    senderID = data.sender;
                    if (data.sender == id) {
                        $(".notif-new" + id).remove();
                        $("#notif" + id).append(
                            '<p class="btn btn-sm btn-danger text-white float-right d-inline col-md-3 ml-2 mr-2 notif-new' +
                                id +
                                '">New</p>'
                        );
                    }
                    if (data.sender != id) {
                        $(".notif-new" + id).remove();
                    }
                },
            });

            if ($("#msgBox").attr("data-id") == id) {
                $.ajax({
                    url: "/messages/all/" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function (data) {
                        // $(".messages-kanan-detil").empty();
                        $.each(data, function (i, item) {
                            // console.log(item.id);
                            if($('#chatId'+item.id).length == 0){
                                // console.log('Item ini belum ada');
                                if (item.receiver == id) {
                                    $(".messages-kanan-detil").append(
                                        '<div class ="chat-keluar float-right" data-aos="fade-up" id="chatId'+item.id+'"><p>' +
                                            item.message +
                                            '</p><p class ="teks-transparan teks-kecil float-right text-white timestamp-msg">' +
                                            moment(item.created_at)
                                                .startOf("minute")
                                                .fromNow() +
                                            "</p></div>"
                                    );
                                } else {
                                    $(".messages-kanan-detil").append(
                                        '<div class ="chat-masuk" data-aos="fade-up" id="chatId'+item.id+'"><p>' +
                                            item.message +
                                            '</p><p class ="teks-transparan teks-kecil ml-auto timestamp-msg">' +
                                            moment(item.created_at)
                                                .startOf("minute")
                                                .fromNow() +
                                            "</p></div>"
                                    );
                                }
                                $(".messages-kanan-detil").scrollTop(9999999999999);
                            }
                        });
                    },
                });
            }
        });
        var paramAutoClick = $('#msg-click').attr('value');
        if(paramAutoClick != 0){
            console.log('auto click');
             $('[data-id='+paramAutoClick+']').trigger('click');
             $('#msg-click').attr('value', 0);
        }
    }, 2000);
    // Kirim Pesan + Refresh
    $(".simpan").click(function (e) {
        e.stopImmediatePropagation();
        $(".input-chat").addClass("disabled");
        var id = $(".receiver").val();
        var data = $(".form-chat").serialize();
        var url = "/messages/send/chat";
        $.ajax({
            url: url,
            method: "PUT",
            data: data,
            success: function (response) {
                if (response.success) {
                    // $(".messages-kanan-detil").empty();
                    $(".input-chat").val("");
                    $(".input-chat").removeClass("disabled");
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Pesan gagal dikirim',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            },
            error: function (error) {
                console.log(error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Pesan gagal dikirim. Koneksi gagal terbangun !!!',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            },
        });
        $.ajax({
            url: "/messages/all/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $.each(data, function (i, item) {
                    if($('#chatId'+item.id).length == 0){
                        // console.log('Item ini belum ada');
                        if (item.receiver == id) {
                            $(".messages-kanan-detil").append(
                                '<div class ="chat-keluar float-right" data-aos="fade-up" id="chatId'+item.id+'"><p>' +
                                    item.message +
                                    '</p><p class ="teks-transparan teks-kecil float-right text-white timestamp-msg">' +
                                    moment(item.created_at)
                                        .startOf("minute")
                                        .fromNow() +
                                    "</p></div>"
                            );
                        } else {
                            $(".messages-kanan-detil").append(
                                '<div class ="chat-masuk" data-aos="fade-up" id="chatId'+item.id+'"><p>' +
                                    item.message +
                                    '</p><p class ="teks-transparan teks-kecil ml-auto timestamp-msg">' +
                                    moment(item.created_at)
                                        .startOf("minute")
                                        .fromNow() +
                                    "</p></div>"
                            );
                        }
                    }
                });
                $(".messages-kanan-detil").scrollTop(9999999999999);
            },
        });
    });
    $(".input-chat").keydown(function (e) {
        if (e.keyCode == 13) {
            $(".simpan").click();
        }
        console.log('Tombol di klik');
    });
});
//Get Data Chat dari ID yang diklik
$(".chat-list").click(function () {
    if (
        /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
            navigator.userAgent
        )
    ) {
        console.log("mobile");
        $(".wrp-set").removeClass("wrapper");
        // var wrapper = document.getElementsByClassName('wrp-set');
        // wrapper[0].classList.remove('wrapper');
        console.log("tambah class");
        // wrapper[0].classList.add('wrapper-mobile');
        $(".wrp-set").addClass("wrapper-mobile");
        $("#add-msg").removeClass("add-msg-collapse");
        // $('.content-no-msg').remove();
    } else {
        console.log("not mobile");
        $(".wrp-set").removeClass("wrapper-mobile");
        // var wrapper = document.getElementsByClassName('wrp-set');
        // wrapper[0].classList.remove('wrapper-mobile');
        console.log("tambah class");
        // wrapper[0].classList.add('wrapper');
        $(".wrp-set").addClass("wrapper");
        $(".close-msg-btn").remove();
    }

    console.log("kontak di klik");
    $(".wrp-set").show();
    var id = $(this).attr("data-id");
    var nama = $(this).data("nama");
    var email = $(this).data("email");
    var photo = $(this).data("photo");
    $(".receiver").attr("value", id);
    $(".messages-kanan-detil").empty();
    $(".btn").removeClass("disabled");
    $(".messages-kanan-detil").attr("data-id", id);
    $.ajax({
        url: "/messages/all/" + id,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            $(".nama-pengirim").text(nama);
            $(".email-pengirim").text(email);
            $(".img-profile2").attr('src', photo);
            $.each(data, function (i, item) {
                if($('#chatId'+item.id).length == 0){
                    // console.log('Item ini belum ada');
                    if (item.receiver == id) {
                        $(".messages-kanan-detil").append(
                            '<div class ="chat-keluar float-right" data-aos="fade-up" id="chatId'+item.id+'"><p>' +
                                item.message +
                                '</p><p class ="teks-transparan teks-kecil float-right text-white timestamp-msg">' +
                                moment(item.created_at)
                                    .startOf("minute")
                                    .fromNow() +
                                "</p></div>"
                        );
                    } else {
                        $(".messages-kanan-detil").append(
                            '<div class ="chat-masuk" data-aos="fade-up" id="chatId'+item.id+'"><p>' +
                                item.message +
                                '</p><p class ="teks-transparan teks-kecil ml-auto timestamp-msg">' +
                                moment(item.created_at)
                                    .startOf("minute")
                                    .fromNow() +
                                "</p></div>"
                        );
                    }
                }
            });
            $(".content-no-msg").addClass("content-no-msg-op");
            $(".wrp-set").addClass("wrapper-op");
            $(".messages-kanan-detil").scrollTop(9999999999999);
        },
    });
});
//Close messages box
$(".close-msg-btn").on("click", function () {
    $(".wrp-set").removeClass("wrapper-op");
    $("#add-msg").addClass("add-msg-collapse");
    $(".wrp-set").hide();
});
// Nyari Data User Select2
$(".search").select2({
    width: "100%",
    heigth: "100%",
    placeholder: "Masukkan Nama atau Email...",
    ajax: {
        url: "messages/search/user/",
        method: "POST",
        dataType: "json",
        delay: 250,
        processResults: function (data) {
            return {
                results: $.map(data, function (item) {
                    console.log(item.id);
                    $("#connection_id").attr("value", item.id);
                    return {
                        text: item.name,
                        id: item.id,
                        name: item.name,
                    };
                }),
            };
        },
        cache: true,
    },
});
