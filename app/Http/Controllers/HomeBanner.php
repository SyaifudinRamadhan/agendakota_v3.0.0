<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\FrontBanner;
use File;
use Image;
use Illuminate\Http\Request;

class HomeBanner extends Controller
{
    public function priority($id, $type) {
        $data = FrontBanner::where('id', $id);
        if ($type == 'increase') {
            $data->increment('priority');
        } else {
            $data->decrement('priority');
        }
        return redirect()->back();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'event' => 'url|active_url|required'
        ]);

        $image = $request->file('image');
        $imageFileName = $image->getClientOriginalName();
        $eventUrl = $request->event;
        // dd($eventUrl);
        $saveData = FrontBanner::create([
            'name' => $request->name,
            'image' => $imageFileName,
            'url' => $eventUrl,
            'priority' => 0
        ]);

        $path=public_path('storage/banner_image');
        $img = Image::make($image->path());
        File::makeDirectory($path, $mode=0777, true, true);
        $img->resize(1084, 217)->save($path.'/'.$imageFileName);

        return redirect()->back();
    }

    public function delete($id)
    {
        $data = FrontBanner::where('id', $id);
        $banner = $data->first();
        $path = public_path('storage/banner_image');
        File::delete($path.'/'.$banner->image);

        $deleteData = $data->delete();
        return redirect()->back();
    }
}
