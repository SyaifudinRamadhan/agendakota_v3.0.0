{{-- @dd($event->messages) --}}
<link rel="stylesheet" href="{{ asset('css/user/messagesStreamPage.css') }}">

<div id="float-chat-stream" class="wrp-set wrapper d-none">
    <div id="head-chat" class="header-chat" style="display: none">

        <i id="close-msg-btn" class="bi bi-x-circle text-danger text-right close-msg-btn pointer mt-2 ml-3"
            onclick="hideMessage()"></i>
    </div>

    @if (isset($purchase))
        <div id="msgBox" data-id="{{ $purchase->id }}" role-as="user" my-id="{{ $myData->id }}"
            class="messages-kanan-detil col-md"></div>
    @else
        <div id="msgBox" data-id="{{ $event->id }}" role-as="admin" my-id="{{ $myData->id }}"
            class="messages-kanan-detil col-md"></div>
    @endif

    <div class="messages-kanan-kirim bg-white">
        <form class="form-chat" method="post">
            @csrf
            @method('get')

            <div class="form-group">
                <div class="input-group">
                    <input class="input-chat tulis-pesan form-control mt-2 rounded-5"
                        style="margin-right: 10px; margin-bottom: 10px;" type="text" name="message" id="message"
                        placeholder="Tuliskan pesanmu di sini">
                    <input class="hidden groupID" type="text" name="groupID" id="groupID" value="{{ $event->id }}"
                        hidden>
                    <a class="btn bg-primer text-light mt-2 tombol-kirim rounded-5 simpan disabled d-none">
                        <i class="fa fa-send"></i>
                        Kirim
                    </a>
                </div>
            </div>
            {{-- <div class="form-group">
                    <div class="input-group">
                        <input class="input-chat tulis-pesan form-control mt-2 rounded-5" type="text" name="message" id="message"placeholder="Tuliskan pesanmu di sini">
                        <input class="hidden groupID" type="text" name="groupID" id="groupID" value="{{ $event->id }}" hidden>
                        <a class="btn bg-primer text-light mt-2 tombol-kirim rounded-5 simpan d-none">
                            <i class="fa fa-send"></i>
                        </a>
                    </div>
                </div> --}}
        </form>
    </div>
</div>



@section('javascript')
    <script src="{{ asset('js/user/chatStream.js') }}"></script>
@endsection
