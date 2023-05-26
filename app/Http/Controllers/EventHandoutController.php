<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\EventHandout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\event\EventHandoutStoreRequest;

class EventHandoutController extends Controller
{
    public static function get($filter = NULL) {
        if ($filter == NULL) {
            return new EventHandout;
        }
        return EventHandout::where($filter);
    }

    public function storeFoto($organizationID, $eventID, Request $request)
    {
        $getSlug = Event::where('id', $eventID)->get('slug');
        foreach ($getSlug as $sl){
            $slug = $sl->slug;
        }
        $validateData = $this->validate($request, [
            'type' => 'required',
            'value' => 'required|image'
        ]);

        $value = $request->file('value');
        $valueFileName = $value->getClientOriginalName();
        $saveData = EventHandout::create([
            'event_id' => $eventID,
            'type' => $request->type,
            'value' => $valueFileName,
        ]);
        $value->storeAs('/public/event_assets/'.$slug.'/event_handouts/', $valueFileName);
        return redirect()->route('organization.event.handouts', [$organizationID, $eventID])->with('berhasil', 'Handouts Telah Berhasil ditambahkan');
    }

    public function storeVideo($organizationID, $eventID, Request $request)
    {
        $validateData = $this->validate($request, [
            'type' => 'required',
            'value' => 'required|url|active_url'
        ]);
        $saveData = EventHandout::create([
            'event_id' => $eventID,
            'type' => $request->type,
            'value' => $request->value,
        ]);
        return redirect()->route('organization.event.handouts', [$organizationID, $eventID])->with('berhasil', 'Handouts Telah Berhasil ditambahkan');
    }

    public function storeDokumen($organizationID, $eventID, Request $request)
    {
        $getSlug = Event::where('id', $eventID)->get('slug');
        foreach ($getSlug as $sl){
            $slug = $sl->slug;
        }

        $validateData = $this->validate($request, [
            'type' => 'required',
            // 'value' => 'required|file'
        ]);

        $value = $request->file('value');
        $valueFileName = $value->getClientOriginalName();
        $saveData = EventHandout::create([
            'event_id' => $eventID,
            'type' => $request->type,
            'value' => $valueFileName,
        ]);
        $value->storeAs('/public/event_assets/'.$slug.'/event_handouts/', $valueFileName);
        return redirect()->route('organization.event.handouts', [$organizationID, $eventID])->with('berhasil', 'Handouts Telah Berhasil ditambahkan');
    }

    public function delete($organizationID, $eventID, $handoutID) {
        $deleteData = EventHandout::where('id', $handoutID)->delete();

        return redirect()->route('organization.event.handouts', [$organizationID, $eventID])->with('berhasil', 'Handout telah berhasil dihapus');
    }

    public function preview($organizationID, $eventID, $handoutvalue)
    {
        $event = Event::find($eventID);
        $getSlug = Event::where('id', $eventID)->get('slug');
        foreach ($getSlug as $sl){
            $slug = $sl->slug;
        }
        return view('user.organization.event.preview',[
            'event' => $event,
            'slug'=>$slug,
            'handoutvalue'=> $handoutvalue
        ]);
    }

    public function updatefoto($organizationID, $eventID, Request $request)
    {
        $fotoID=$request->handoutID;
        $foto =EventHandout::find($fotoID);
        $getSlug = Event::where('id', $eventID)->get('slug');
        foreach ($getSlug as $sl){
            $slug = $sl->slug;
        }
        $validateData = $this->validate($request, [
            'type' => 'required',
            'value' => 'required|image'
        ]);

        $updatefoto=$request->file('value');
        if($updatefoto){
            Storage::delete('/public/event_assets/'.$slug.'/event_handouts/'.$foto->value);
            $namafoto =$updatefoto->getClientOriginalName();
            $updatefoto->storeAs('/public/event_assets/'.$slug.'/event_handouts/', $namafoto);
        }else{
            $namafoto= $foto->value;
        }

        $update=EventHandout::where('id', $fotoID)->update([
            'value' => $namafoto
        ]);

        return redirect()->route('organization.event.handouts',[$organizationID, $eventID])->with('berhasil','Handout Foto Berhasil Diedit');

    }
    public function updatevideo($organizationID, $eventID, Request $request)
    {
        $videoID=$request->handoutID;

        $update=EventHandout::where('id', $videoID)->update([
            'value' =>$request->value
        ]);

        return redirect()->route('organization.event.handouts',[$organizationID, $eventID])->with('berhasil','Handout Video Berhasil Diedit');
    }
    public function updatedokumen($organizationID, $eventID, Request $request)
    {
        $dokumenID=$request->handoutID;
        $foto =EventHandout::find($dokumenID);
        $getSlug = Event::where('id', $eventID)->get('slug');
        foreach ($getSlug as $sl){
            $slug = $sl->slug;
        }

        $validateData = $this->validate($request, [
            'type' => 'required',
            // 'value' => 'required|file'
        ]);

        $updateDokumen=$request->file('value');
        if($updateDokumen){
            Storage::delete('/public/event_assets/'.$slug.'/event_handouts/'.$foto->value);
            $namadokumen =$updateDokumen->getClientOriginalName();
            $updateDokumen->storeAs('/public/event_assets/'.$slug.'/event_handouts/', $namadokumen);
        }else{
            $namadokumen= $foto->value;
        }

        $update=EventHandout::where('id', $dokumenID)->update([
            'value' => $namadokumen
        ]);

        return redirect()->route('organization.event.handouts',[$organizationID, $eventID])->with('berhasil','Handout Dokumen Berhasil Diedit');
    }
}
