<div class="modal" id="modalError">
    <div class="modal-body" style="width: 30%;">
        <form action="#" class="modal-content">
            {{ csrf_field() }}
            <div class="flex row justify-center mb-4">
                <div class="h-100 squarize use-height rounded-max bg-primary transparent flex row item-center justify-center">
                    <i class="bx bxs-error-circle" style="font-size: 70px"></i>
                </div>
            </div>

            <div id="message" class="text center big"></div>
            
            <div class="text primary bold pointer w-100 mt-4 center" onclick="closeError()">
                Tutup
            </div>
        </form>
    </div>
</div>