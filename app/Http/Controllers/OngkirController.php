<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OngkirController extends Controller
{
    public function get_province()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
            CURLOPT_RETURNTRANSFER => true,           
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: 5d7cf17b8d0e2d0de3a34a027e7f82ec"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            //ini kita decode data nya terlebih dahulu
            $response=json_decode($response, true);
            //ini untuk mengambil data provinsi yang ada di dalam rajaongkir resul
            $data_pengirim = $response['rajaongkir']['results'];
            return $data_pengirim;
        }
    }

    public function get_city($id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/city?&province=$id",
            CURLOPT_RETURNTRANSFER => true,           
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: 5d7cf17b8d0e2d0de3a34a027e7f82ec"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response=json_decode($response, true);
            $data_kota = $response['rajaongkir']['results'];
            return json_encode($data_kota);
        }
    }

    public function get_ongkir($asal, $tujuan, $berat, $kurir)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,                        
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=$asal&destination=$tujuan&weight=$berat&courier=$kurir",
            CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded",
            "key: 5d7cf17b8d0e2d0de3a34a027e7f82ec"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response=json_decode($response, true);
            $data_ongkir = $response['rajaongkir']['results'];
            return json_encode($data_ongkir);
        }
    }
}
