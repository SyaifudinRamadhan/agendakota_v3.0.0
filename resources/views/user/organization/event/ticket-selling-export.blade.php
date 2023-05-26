<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>
<body>

    @php
    use Carbon\Carbon;
    use App\Models\UserCheckin;
    $tanggalSekarang = Carbon::now()->toDateString();
    @endphp

    <div class="table-responsive">
        <table id="table-checkin" class="table table-borderless customers-list">
            <thead>
                <tr>
                    <th>Pembeli</th>
                    <th>E-Mail pembeli</th>
                    <!-- <th>Diberikan Ke- </th>
                            <th>E-Mail penerima</th> -->
                    <th>InvoiceID</th>
                    <th>Ticket</th>
                    <th>Harga</th>
                    <th>Tanggal Beli</th>
                    <th>Terbayar</th>
                    <th>CheckIn</th>
                </tr>
            </thead>

            <tbody class="mt-2">
                {{-- Variabel $tickets diambil dari model purchase bukan ticket --}}
                @foreach ($tickets as $ticket)
                    <tr>
                        {{-- <td>{{ $ticket->users->name }}</td>
                        <td>{{ $ticket->users->email }}</td> --}}
                        <td>{{ $ticket->users->name }}</td>
                        <td>{{ $ticket->users->email }}</td>
                        <td>{{ $ticket->payment->order_id }}</td>
                        <td>{{ $ticket->tickets->name }}</td>
                        <td>@currencyEncode($ticket->tickets->price)</td>
                        <td>{{ $ticket->created_at }}</td>
                        <td>
                            @if ($ticket->payment->pay_state == 'Terbayar')
                                Terbayar
                            @else
                                Belum
                            @endif
                        </td>
                        <td>
                            @php
                                $checkin = UserCheckin::where('purchase_id', $ticket->id)->get();
                            @endphp
                            @if (count($checkin) == 0)
                               Sudah Checkin
                            @else
                                Belum Checkin
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="{{ asset('js/user/exportTable.js') }}"></script>
    <script>
        exportTableToExcel('table-checkin', filename = 'checkin_and_selling');
        window.history.back();
    </script>
    
</body>
</html>