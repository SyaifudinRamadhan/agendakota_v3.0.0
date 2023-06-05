<div class="modal" id="modalTicket">
    <div class="modal-body" style="width: 50%;">
        <form action="#" class="modal-content" onsubmit="createTicket(event)">
            {{ csrf_field() }}
            <input type="hidden" name="ticket_type" id="ticket_type">
            <div class="flex row item-center wrap mb-2">
                <div class="flex row grow-1 item-center">
                    <h3 class="m-0">Tambah Tiket</h3>
                    <div class="flex ml-1">
                        <div class="text primary small bordered p-05 pl-1 pr-1 rounded" id="ticket_type_display"></div>
                    </div>
                </div>
                <div>
                    <div class="h-40 squarize use-height rounded-max bg-grey flex row item-center justify-center pointer close" onclick="modal('#modalTicket').hide()">
                        <i class="bx bx-x"></i>
                    </div>
                </div>
            </div>

            <div class="flex row" id="TicketTab">
                <div class="tab-item flex grow-1 justify-center border-bottom pointer h-40 active" style="display: none;" target="TicketForm">Tiket</div>
                {{-- <div class="tab-item flex grow-1 justify-center border-bottom pointer h-40" target="TicketVoucher">Kode Promo</div> --}}
            </div>

            
            <div class="tab-content active" key="TicketForm">
                <div class="group">
                    <input type="text" name="ticket_name" id="ticket_name" required >
                    <label for="ticket_name">Nama Tiket :</label>
                </div>
                <div class="group active">
                    <select name="session_key" id="session_key" onchange="setSession(this)" required></select>
                </div>
                <div class="group">
                    <textarea name="ticket_description" id="ticket_description" required></textarea>
                    <label for="ticket_description">Deskripsi</label>
                </div>
    
                <div class="flex row">
                    <div class="group mt-0 flex column grow-1">
                        <input type="number" name="ticket_quantity" id="ticket_quantity" min="1" value="1" required >
                        <label for="ticket_quantity">Jumlah Tiket :</label>
                    </div>
                    <div class="QuantityControl rounded text bold pointer ml-1 bordered primary" onclick="setTicketQuantity('decrease')">-</div>
                    <div class="QuantityControl rounded text bold pointer ml-1 bordered primary" onclick="setTicketQuantity('increase')">+</div>
                </div>
    
                <div class="group" id="ticketPriceArea">
                    <input type="text" name="ticket_price" id="ticket_price" oninput="typingCurrency(this)" value="Rp 0" required>
                    <label for="ticket_price">Harga :</label>
                </div>
    
                <div class="text bold mt-1">Tanggal Penjualan</div>
                <div class="flex row ticket-sales-dates">
                    <div class="group flex column grow-1 pr-1">
                        <input type="text" name="ticket_start_date" id="ticket_start_date" required>
                        <label class="active" for="ticket_start_date">Mulai</label>
                    </div>
                    <div class="group flex column grow-1 pl-1">
                        <input type="text" name="ticket_end_date" id="ticket_end_date" required>
                        <label class="active" for="ticket_end_date">Hingga</label>
                    </div>
                </div>
            </div>
            <div class="tab-content" key="TicketVoucher">
                <div class="group">
                    <input type="text" name="voucher_name" id="voucher_name">
                    <label for="voucher_name">Nama Voucher :</label>
                </div>
                <div class="group">
                    <input type="text" name="voucher_code" id="voucher_code">
                    <label for="voucher_code">Kode Voucher :</label>
                </div>
                <div class="group">
                    <input type="text" name="voucher_quantity" id="voucher_quantity">
                    <label for="voucher_quantity">Kuota Penggunaan :</label>
                </div>
                <div class="flex row">
                    <div class="group flex column grow-1 pr-1">
                        <select name="voucher_type" id="voucher_type">
                            <option value="amount">Nominal</option>
                            <option value="percentage">Persentase</option>
                        </select>
                        <label for="voucher_type" class="active">Tipe Diskon :</label>
                    </div>
                    <div class="group flex column grow-1 pl-1 pr-1">
                        <input type="number" name="voucher_amount" id="voucher_amount">
                        <label for="voucher_amount">Jumlah Potongan :</label>
                    </div>
                    <div class="group flex column grow-1 pr-1">
                        <input type="text" name="minimum_transaction" id="minimum_transaction">
                        <label for="minimum_transaction">Minimal Transaksi (Rupiah) :</label>
                    </div>
                </div>
                <div class="flex row">
                    <div class="group flex column grow-1 pr-1">
                        <input type="text" name="voucher_start" id="voucher_start">
                        <label class="active" for="voucher_start">Berlaku mulai :</label>
                    </div>
                    <div class="group flex column grow-1 pr-1">
                        <input type="text" name="voucher_end" id="voucher_end">
                        <label class="active" for="voucher_end">Berakhir :</label>
                    </div>
                </div>
            </div>

            <button class="mt-2 w-100 primary">Submit</button>
        </form>
    </div>
</div>