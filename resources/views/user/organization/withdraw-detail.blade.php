@extends('layouts.user')

@section('title', 'Withdraw Detail')

@section('head.dependencies')
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/flatpickr/dist/themes/airbnb.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/eventsPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/organization/profileOrganizationPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/user/connectionPage.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">


@endsection
@php
use Carbon\Carbon;
use App\Models\Organization;
use App\Models\UserWithdrawalEvent;

$timeZone = new DateTimeZone('Asia/Jakarta');

$now = new DateTime();
// $endEvent = new DateTime($startSession->end_date . ' ' . $startSession->end_time, new DateTimeZone('Asia/Jakarta'));
// $paginateController = new App\Http\Controllers\PaginateArrayController();
@endphp
@section('content')

    <div class="row">
        <div class="col-md-7">
            <h2 style="color: #304156;">Detail Withdrawal</h2>
        </div>
        <div class="col-md-5">
            <a onclick="history.back()" id="back-btn" class="btn btn-primer ke-kanan rounded-5" style="width: unset"><i
                    class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <div class="row mt-5 ml-1 mr-1">
        <div class="col-12">
            <h5 style="color: #304156;">Data Pemohon</h5>
        </div>
        <div class="col-md-12">
            <div class="bagi bagi-2 w-100 list-item mb-2">
                <div class="mt-3">
                    <div class="bg-putih bayangan-5 rounded-5 smallPadding">
                        <div class="detail">
                            <div class="row pl-3 pr-3 pt-3 pb-3">
                                <div class="col-sm-3 no-pd-r mb-3">
                                    <img class="rounded-circle img-up asp-rt-1-1"
                                        src="{{ $myData->photo == 'default' ? asset('images/profile-user.png') : asset('storage/profile_photos/' . $myData->photo) }}"
                                        style="width: 90%; height:unset; margin-top:30px;">
                                </div>
                                <div class="col-lg-9">
                                    <div>
                                        <h4 class="mb-1">{{ $myData->name }}</h4>
                                        <p class="text-secondary">{{ $withdraw->bank_name }}
                                            {{ $withdraw->account_number }}</p>
                                        <p class="mt-1 mb-0"><i class="bi bi-envelope"></i> {{ $myData->email }}</p>
                                        <p class="mt-1 mb-0"><i class="bi bi-telephone"></i>
                                            {{ $myData->phone == '' ? 'Belum di set' : $myData->phone }}</p>
                                        <p class="mt-1 mb-0"><i class="bi bi-people"></i>
                                            {{ $organization->name }} Organizer</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="bagi bagi-2 w-100 list-item mb-2">
                <div class="mt-3">
                    <div class="bayangan-5 rounded-5 smallPadding" style="background-color: #ef9c1870;">
                        <div class="detail">
                            <div class="row pl-3 pr-3 pt-3 pb-3">
                                <div class="col-12 text-center mb-3">
                                    <img class="rounded-circle asp-rt-1-1"
                                        src="{{ $organization->logo == '' ? asset('storage/organization_logo/default_logo.png') : asset('storage/organization_logo/' . $organization->logo) }}"
                                        style="max-width: 130px; margin-top:30px;">
                                </div>
                                <div class="col-12 text-center mb-0">
                                    <h5 class="mb-0">{{ $organization->name }}</h5>
                                </div>
                                <div class="col-12 text-center mt-0">
                                    <p class="fs-15">{{ $organization->description }}</p>
                                </div>
                                <hr>
                                <div class="col-12 text-center">
                                    <p class="fs-16 text-left mb-1">
                                        Total Saldo :
                                    </p>
                                    <span class="btn bg-light"
                                        style="border-radius: 4px !important; max-width: 100%; overflow-x:auto;">@currencyEncode($allSaldo),00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="bagi bagi-2 w-100 list-item mb-2">
                <div class="mt-3">
                    <div class="bg-putih bayangan-5 rounded-5 smallPadding">
                        <div class="detail">
                            <div class="row pl-3 pr-3 pt-3 pb-3">
                                <div class="col-lg-8 pl-4 pr-4 mb-4">
                                    <table>
                                        <tr>
                                            <td style="padding-top: 10px !important; padding: unset;"
                                                class="text-secondary">Status</td>
                                            <td style="padding-top: 10px !important; padding: unset;">
                                                @if ($withdraw->status == 'waiting')
                                                    <span class="btn no-pd bg-secondary text-light">Waiting</span>
                                                @elseif($withdraw->status == 'accepted')
                                                    <span class="btn no-pd bg-success text-light">Accepted</span>
                                                @elseif($withdraw->status == 'rejected')
                                                    <span class="btn no-pd bg-danger text-light">Rejected</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 10px !important; padding: unset;"
                                                class="text-secondary">Waktu Pengajuan</td>
                                            <td style="padding-top: 10px !important; padding: unset;">
                                                {{ $withdraw->created_at }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 10px !important; padding: unset;"
                                                class="text-secondary">Bank Mitra</td>
                                            <td style="padding-top: 10px !important; padding: unset;">
                                                {{ $withdraw->bank_name }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 10px !important; padding: unset;"
                                                class="text-secondary">No. Rekekning</td>
                                            <td style="padding-top: 10px !important; padding: unset;">
                                                {{ $withdraw->account_number }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-lg-4 pl-3 pr-3 mb-4">
                                    <div class="bayangan-5 pl-2 pr-2 pt-2 pb-3">
                                        <table>
                                            <tr>
                                                <td style="padding-top: unset !important; padding: unset;"
                                                    class="fs-12">Pengajuan Approval</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 2px !important; padding: unset;"
                                                    class="fs-18 mt-1">@currencyEncode($withdraw->nominal),00</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 10px !important; padding: unset;">
                                                    @if ($withdraw->status == 'waiting')
                                                        <span class="btn btn-secondary text-center w-100"
                                                            style="border-radius: 4px">PROSES</span>
                                                    @elseif($withdraw->status == 'accepted')
                                                        <span class="btn btn-success text-center w-100"
                                                            style="border-radius: 4px">SELESAI</span>
                                                    @elseif($withdraw->status == 'rejected')
                                                        <span class="btn btn-danger text-center w-100"
                                                            style="border-radius: 4px">GAGAL</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
