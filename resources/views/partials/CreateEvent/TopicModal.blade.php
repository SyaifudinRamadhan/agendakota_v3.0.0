<div class="modal" id="modalTopic">
    <div class="modal-body" style="width: 40%;">
        <form action="#" class="modal-content">
            {{ csrf_field() }}
            <div class="flex row item-center wrap mb-4">
                <div class="flex row grow-1 item-center">
                    <h2 class="m-0">Pilih Topik</h2>
                </div>
                <div>
                    <span class="text primary bold pointer" onclick="closeTopicModal()">
                        Simpan
                    </span>
                </div>
            </div>

            <div class="bg-orange transparent rounded p-1 pl-2 pr-2 text small bold d-none mb-2" id="topic_error">
                Kamu hanya bisa memilih hingga 3 topik
            </div>

            <div class="flex row wrap">
                @foreach ($topics as $topic)
                    <div 
                        class="bordered rounded pointer p-1 pl-2 pr-2 mr-1 mb-1 topic-item" 
                        topic="{{ base64_encode($topic) }}" 
                        onclick="chooseTopic('{{ $topic }}', this)"
                    >
                        {{ $topic }}
                    </div>
                @endforeach
            </div>

            <button type="button" onclick="closeTopicModal()" class="w-100 bg-primary mt-3">Simpan</button>
        </form>
    </div>
</div>