function deleteConfirm(evt) {
    evt.preventDefault(); // prevent form submit
    var urlToRedirect = evt.currentTarget.getAttribute("href");

    Swal.fire({
        title: "Apakah kamu yakin ?",
        text: "Quiz ini akan dihapus",
        type: "warning",
        icon: "warning",
        dangerMode: true,
        showCancelButton: true,
        confirmButtonText: "Ya, hapus",
        cancelButtonText: "Batal",
        closeOnConfirm: false,
        closeOnCancel: false,
    }).then((result) => {
        if (result.isConfirmed) {
            // console.log(urlToRedirect);
            window.location.replace(urlToRedirect); // submitting the form when user press yes
        } else {
            Swal.fire("Dibatalkan", "Quiz batal dihapus", "info");
        }
    });
}

// Scroll table controller
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('dummy-table').addEventListener('scroll', function () {
       document.getElementById('real-table').scrollLeft = document.getElementById('dummy-table').scrollLeft; 
    });
    document.getElementById('real-table').addEventListener('scroll', function () {
        document.getElementById('dummy-table').scrollLeft = document.getElementById('real-table').scrollLeft; 
     });
});
