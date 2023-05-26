<?php 

namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;

use App\Models\Handbook;
use App\Models\Event;
// use App\Models\Ticket;
// use App\Models\Faq;
// use App\Models\Page;
use Str;
use Image;
use File;
use Carbon\Carbon;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HandbookController extends Controller
{
    public function store($organizationID, $eventID, Request $request){
        
        $exhibitorID = null;
        session()->flash('from', 'handbook');

        if($organizationID == 0 || $organizationID == '0'){
            $isThisExhibitor = UserController::isExihibitor($eventID);
            // dd($isThisExhibitor);
            if(count($isThisExhibitor) == 0){
                return abort('403');
            }
            $exhibitorID = $isThisExhibitor[0]->id;
        }else{
            $nameRoute = $request->route()->getName();
            if($nameRoute == "myHandbookStore"){
                return abort('403');
                // dd($request);
            }
        }
        
        $event = Event::where('id', $eventID)->first();
        $typeFile = $request->uploadType;
        //type photo
        if($typeFile == "photo"){
            // dd($request);
            $validateRule = [
                'photo' => 'required|image|mimes:jpg,png,jpeg|max:'.($event->organizer->user->package->max_attachment * 1024),
            ];
            $validateMsg = [
                'required' => 'Input fle foto wajib diisi',
                'mimes' => 'File harus JPG atau PNG',
                'max' => 'Filenya terlalu besar max '.$event->organizer->user->package->max_attachment.' Mb'
            ];

            $this->validate($request, $validateRule, $validateMsg);
            // $validator = Validator::make($request)
            
            $file = $request->file('photo');
            $fileNameOri = $file->getClientOriginalName();
            //Path store image
            $pathImg = public_path('storage/event_assets/'.$event->slug.'/event_handbooks/');
            $slug = Str::slug($fileNameOri);
            $dataSave = [
                'event_id'=>$eventID,
                'slug' => $slug,
                'file_name' => $fileNameOri,
                'type_file' => $typeFile,
                'exhibitor_id' => $exhibitorID,
            ];
            Handbook::create($dataSave);

            //Save file to storage
            $photo = Image::make($file->path());
            File::makeDirectory($pathImg, $mode = 0777, true, true);


            $photo->save($pathImg.$fileNameOri);

            if($organizationID == 0 || $organizationID == '0'){
                return redirect()->back()->with([
                    'berhasil' => 'Photo handbooks berhasil di upload',
                    'from' => 'handbook'
                ]);
            }

            return redirect()->route('organization.event.handbooks',[$organizationID, $eventID])->with([
                'berhasil' => 'Photo handbooks berhasil di upload',
                'from' => 'handbook'
            ]);
        }else if($typeFile == "documents"){
            $validateRule = [
                'document' => 'required|mimes:pdf|max:'.($event->organizer->user->package->max_attachment * 1024),
            ];
            $validateMsg = [
                'required' => 'Input document wajib diisi',
                'mimes' => 'File harus bertipe PDF',
                'max' => 'Filenya terlalu besar max '.$event->organizer->user->package->max_attachment.' Mb'
            ];

            $this->validate($request, $validateRule, $validateMsg);
            
            $file = $request->file('document');
            $fileNameOri = $file->getClientOriginalName();
            //Path store image
            $pathDoc = public_path('storage/event_assets/'.$event->slug.'/event_handbooks/');
            $slug = Str::slug($fileNameOri);
            $dataSave = [
                'event_id'=>$eventID,
                'slug' => $slug,
                'file_name' => $fileNameOri,
                'type_file' => $typeFile,
                'exhibitor_id' => $exhibitorID,
            ];
            Handbook::create($dataSave);

            //Save file to storage
            File::makeDirectory($pathDoc, $mode = 0777, true, true);


            $file->move($pathDoc, $fileNameOri);

            if($organizationID == 0 || $organizationID == '0'){
                return redirect()->back()->with([
                    'berhasil' => 'Photo handbooks berhasil di upload',
                    'from' => 'handbook'
                ]);
            }

            return redirect()->route('organization.event.handbooks',[$organizationID, $eventID])->with([
                'berhasil' => 'Document handbooks berhasil di upload',
                'from' => 'handbook'
            ]);
        }else if($typeFile == "video"){
            $validateRule = [
                'video' => 'required|url|active_url',
            ];
            $validateMsg = [
                'required' => 'Input link youtube wajib diisi ex(https://www.youtube.com/watch?v=8RpCLKoa3_A)',
                'url' => 'File harus berupa link youtube ex(https://www.youtube.com/watch?v=8RpCLKoa3_A)'
            ];

            $this->validate($request, $validateRule, $validateMsg);

            $event = Event::where('id', $eventID)->first();
            
            $url = $request->video;
            // dd($url);
            
            $slug = Str::slug($url);
            $dataSave = [
                'event_id'=>$eventID,
                'slug' => $slug,
                'file_name' => $url,
                'type_file' => $typeFile,
                'exhibitor_id' => $exhibitorID,
            ];

            Handbook::create($dataSave);

            if($organizationID == 0 || $organizationID == '0'){
                return redirect()->back()->with([
                    'berhasil' => 'Photo handbooks berhasil di upload',
                    'from' => 'handbook'
                ]);
            }

            return redirect()->route('organization.event.handbooks',[$organizationID, $eventID])->with([
                'berhasil' => 'Video handbooks berhasil di upload',
                'from' => 'handbook'
            ]);
        }
    }

    public function delete($organizationID, $eventID, Request $request){
        
        if($organizationID == 0 || $organizationID == '0'){
            $isThisExhibitor = UserController::isExihibitor($eventID);
            // dd($isThisExhibitor);
            if(count($isThisExhibitor) == 0){
                return abort('403');
            }
            $exhibitorID = $isThisExhibitor[0]->id;
        }else{
            $nameRoute = $request->route()->getName();
            if($nameRoute == "myHandbookDelete"){
                return abort('403');
                // dd($request);
            }
        }
        
        $typeFiles = $request->typeFile;
        $event = Event::where('id', $eventID)->first();
        $handbook = Handbook::where('id', $request->ID)->first();
        $regulerPath = 'storage/event_assets/'.$event->slug.'/event_handbooks/';

        if($typeFiles == "photo" || $typeFiles == "documents"){
            $path = public_path($regulerPath.$handbook->file_name);
            File::delete($path);
            Handbook::where('id', $request->ID)->delete();

            if($organizationID == 0 || $organizationID == '0'){
                return redirect()->back()->with([
                    'berhasil' => 'File handbooks berhasil di upload',
                    'from' => 'handbook'
                ]);
            }

            return redirect()->route('organization.event.handbooks',[$organizationID, $eventID])->with([
                'berhasil' => 'File handbooks berhasil di hapus',
                'from' => 'handbook'
            ]);
        }else if($typeFiles == "video"){
            Handbook::where('id', $request->ID)->delete();

            if($organizationID == 0 || $organizationID == '0'){
                return redirect()->back()->with([
                    'berhasil' => 'Video handbooks berhasil di upload',
                    'from' => 'handbook'
                ]);
            }

            return redirect()->route('organization.event.handbooks',[$organizationID, $eventID])->with([
                'berhasil' => 'File handbooks berhasil di hapus',
                'from' => 'handbook'
            ]);
        }
    }

}
 
 ?>