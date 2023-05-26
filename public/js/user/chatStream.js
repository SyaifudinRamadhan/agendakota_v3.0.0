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
    console.log("Masuk ready fn");
    // Get Data Pesan Terakhir
    setInterval(function () {
        var idGroup = $("#msgBox").attr("data-id");
        var myId = $("#msgBox").attr("my-id");
        var roleAs = $("#msgBox").attr("role-as");
        var mainUrl = "/messages/messages/allGroup/";
        if(roleAs == 'admin'){
            mainUrl = "/messages/messages/allGroup2/";
        }
        // console.log(myId);
        $.ajax({
            url: mainUrl + idGroup,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                // console.log(data);
                // $(".messages-kanan-detil").empty();
                $.each(data, function (i, item) {
                    // console.log(item.id);
                    if ($("#chatId" + item.id).length == 0) {
                        // console.log('Item ini belum ada');
                        if (item.sender == myId) {
                            $(".messages-kanan-detil").append(
                                '<div class="float-right mb-2" style="width: 70%" data-aos="fade-up" id="chatId' + item.id +
                                '">'+
                                    '<p class="fs-14 mb-0 float-right text-name-r">'+
                                        '<img src="'+item.photo+'" class="picture-chat">&nbsp;<b> Saya</b></img>'+
                                    '</p>'+
                                    '<div class ="chat-keluar w-100 float-right" >'+
                                        '<p>' + item.message +
                                        '</p>'+
                                        '<p class ="teks-transparan fs-13 float-right text-white timestamp-msg">' +
                                            moment(item.created_at).startOf("minute").fromNow() +
                                        '</p>'+
                                    '</div>'+
                                '</div>'
                            );
                        } else if (item.sender != myId) {
                            $(".messages-kanan-detil").append(
                                '<div class="float-left w-100 mb-2" data-aos="fade-up" id="chatId' + item.id +
                                '">'+
                                    '<p class="fs-14 mb-0 text-name">'+
                                        '<img src="'+item.photo+'" class="picture-chat">&nbsp;<b> '+item.sender_mail+'</b></img>'+
                                    '</p>'+
                                    '<div class ="chat-masuk">'+
                                        '<p>' + item.message +
                                        '</p>'+
                                        '<p class ="teks-transparan fs-13 float-right timestamp-msg">' +
                                            moment(item.created_at).startOf("minute").fromNow() +
                                        '</p>'+
                                    '</div>'+
                                '</div>'
                            );
                        }
                        $(".messages-kanan-detil").scrollTop(9999999999999);
                    }
                });
            },
        });
    }, 2000);
    // Kirim Pesan + Refresh
    $(".simpan").click(function (e) {
        e.stopImmediatePropagation();
        $(".input-chat").addClass("disabled");
        var idGroup = $(".groupID").val();
        var data = $(".form-chat").serialize();
        var url = "/messages/messages/send/chatGroup";
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
                    text: 'Koneksi data pesan gagal !!!',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            },
        });
    });
    $(".input-chat").keydown(function (e) {
        if (e.keyCode == 13) {
            $(".simpan").click();
        }
    });
});
// Biar Bisa Enter == Kirim

//Close messages box
$(".close-msg-btn").on("click", function () {
    $(".wrp-set").removeClass("wrapper-op");
    $("#add-msg").addClass("add-msg-collapse");
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
