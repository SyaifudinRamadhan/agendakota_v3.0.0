// document.addEventListener('DOMContentLoaded', function () {
//     if (
//         /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
//             navigator.userAgent
//         )
//     ) {
//         console.log("mobile");
       
//     }
// });
const uploadPhotoProfile = (input,maxSize,isEdit = null) => {
    console.log(maxSize);
    let file = input.files[0];
    console.log(file);
    let fileSize = file.size / 1024;
    console.log(fileSize);
    maxSize = maxSize * 1024;
    if(fileSize > maxSize){
        Swal.fire({
            title: 'Error!',
            text: 'File maksimal '+(maxSize/1024)+' Mb',
            icon: 'error',
            confirmButtonText: 'Ok'
        }).then(function(){
            removePreview();
        });
        
    }{
        let reader = new FileReader();
        reader.readAsDataURL(file);
        reader.addEventListener("load", function() {
            if (isEdit == null) {
                let preview = select("#photoPreviewProfile");
                select("#inputPhotoAreaProfile").classList.add('d-none');
                select("#previewPhotoAreaProfile").classList.remove('d-none');
                preview.setAttribute('src', reader.result);
            } else {
                let preview = select("#photoPreviewProfile");
                select("#inputPhotoAreaProfile").classList.add('d-none');
                select("#previewPhotoAreaProfile").classList.remove('d-none');
                preview.setAttribute('src', reader.result);
                console.log(reader.result);
                console.log(preview);
            }
        });
    }
}
const removePreview = () => {
    select("#inputPhotoAreaProfile").classList.remove('d-none');
    select("#previewPhotoAreaProfile").classList.add('d-none');
}