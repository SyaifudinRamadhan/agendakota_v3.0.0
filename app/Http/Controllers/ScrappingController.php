<?php

namespace App\Http\Controllers;

use App\Models\BoothProduct;
use App\Models\Event;
use Illuminate\Http\Request;
use voku\helper\HtmlDomParser;
use File;
use Image;
use Str;
// require_once '../vendor/autoload.php';

class ScrappingController extends Controller
{
    public function init($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $htmlView = curl_exec($curl);
        curl_close($curl);
        // dd($htmlView);
        return HtmlDomParser::str_get_html($htmlView);
    }

    public function getProducts($eventID, Request $request)
    {
        $url = $request->url;
        if (strpos($url, 'tokopedia') == false && strpos($url, 'shopee') == false && strpos($url, 'bukalapak') == false) {
            return redirect()->back()->with([
                'gagal' => 'Jenis link produk tidak terdaftar di sistem kami',
                'from' => 'scrapper'
            ]);
        }

        $thisIsExhibitor = UserController::isExihibitor($eventID);
        if (count($thisIsExhibitor) == 0) {
            return abort('403');
        }

        $view = $this->init($url);
        // dd($view);property='og:image'
        $metas = $view->find("meta");
        $images = '';
        $title = '';
        $price = '';
        foreach ($metas as $meta) {
            $tmp = $meta->getAttribute('property');
            if ($tmp == "og:title") {
                $title = $meta->getAttribute('content');
            } else if ($tmp == "og:image") {
                $images = $meta->getAttribute('content');
            } else if ($tmp == "product:price:amount") {
                $price = $meta->getAttribute('content');
            }

            $tmpPrc = $meta->getAttribute('itemprop');
            if ($tmpPrc == 'price') {
                $price = $meta->getAttribute('content');
            }
        }

        if ($price == "") {
            $tmp = $view->find('.c-product-price')->innertext;
            if ($tmp != null || $tmp != 'null') {
                $price = $view->findOne('.c-product-price span')->innertext;
            }
        }

        if ($price != "") {
            $prcEx = explode('Rp', $price);
            // dump($prcEx);
            if (count($prcEx) == 1) {
                $price = "Rp." . $price;
            }
        }

        // dd($view->find('div'), $title, $images, $price);
        if($title == "" || $price == "" || $images == ""){
            return redirect()->back()->with([
                'gagal'=>'URL yang kamu inputkan tidak sesuai',
                'from' => 'scrapper'
            ]);
        }
        $checkDouble = BoothProduct::where('booth_id', $thisIsExhibitor[0]->id)->where('name', $title)->get();
        if(count($checkDouble) > 0){
            return redirect()->back()->with([
                'gagal'=>'Produk sudah pernah kamu inputkan',
                'from' => 'scrapper'
            ]);
        }
        $datasave = [
            'booth_id' => $thisIsExhibitor[0]->id,
            'name' => $title,
            'price' => $price,
            'image' => $images,
            'url' => $url,
        ];
        BoothProduct::create($datasave);
        return redirect()->back()->with([
            'berhasil' => 'Booth / Exhibitor produk berhasil ditambahkan',
            'from' => 'scrapper'
        ]);
    }

    public function saveProducts($eventID, Request $request)
    {

        $thisIsExhibitor = UserController::isExihibitor($eventID);
        $event = Event::where('id', $eventID)->first();
        if (count($thisIsExhibitor) == 0) {
            return abort('403');
        }

        $validateRule = [
            'prd_img' => 'required|mimes:jpg,png,jpeg,webp|max:' . ($event->organizer->user->package->max_attachment * 1024),
            'url' => 'required|active_url',
        ];


        $validateMsg = [
            'max' => 'Kolom :Attribute maksimal 191 karakter',
            'mimes' => 'File yang diterima bertipe jpg, png atau jpeg',
            'prd_img.max' => 'File upload maksimal ' . $event->organizer->user->package->max_attachment . ' Mb',
            'url' => 'Kolom :Attribute harus berupa url link',
        ];

        $validateData = $this->validate($request, $validateRule, $validateMsg);

        $imgFile = $request->file('prd_img');

        $title = '';
        $price = '';
       
        $checkDouble = BoothProduct::where('booth_id', $thisIsExhibitor[0]->id)->where('url', $request->url)->get();
        if(count($checkDouble) > 0){
            return redirect()->back()->with([
                'gagal'=>'Produk sudah pernah kamu inputkan',
                'from' => 'scrapper'
            ]);
        }

        $datasave = [
            'booth_id' => $thisIsExhibitor[0]->id,
            'name' => $title,
            'price' => $price,
            'image' => Str::slug($thisIsExhibitor[0]->name) . '_' . $imgFile->getClientOriginalName(),
            'url' => $request->url,
        ];
        BoothProduct::create($datasave);

        $filePathImg = public_path('storage/event_assets/' . $event->slug . '/exhibitors/exhibitor_products/');
        $img = Image::make($imgFile->path());
        File::makeDirectory($filePathImg, $mode = 0777, true, true);
        $img->save($filePathImg . '/' . Str::slug($thisIsExhibitor[0]->name) . '_' . $imgFile->getClientOriginalName());

        return redirect()->back()->with([
            'berhasil' => 'Booth / Exhibitor produk berhasil ditambahkan',
            'from' => 'scrapper'
        ]);
    }

    public function delete($eventID, $id)
    {
        $thisIsExhibitor = UserController::isExihibitor($eventID);
        $event = Event::where('id', $eventID)->first();
        if (count($thisIsExhibitor) == 0) {
            return abort('403');
        }
        $filePathImg = public_path('storage/event_assets/' . $event->slug . '/exhibitors/exhibitor_products/');
        $obj = BoothProduct::where('id', $id);
        File::delete($filePathImg . $obj->first()->image);
        $obj->delete();
        
        return redirect()->back()->with([
            'berhasil' => 'Booth / Exhibitor produk berhasil dihapus',
            'from' => 'scrapper'
        ]);
    }
}
