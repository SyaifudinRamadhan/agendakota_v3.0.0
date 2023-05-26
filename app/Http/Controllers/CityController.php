<?php

namespace App\Http\Controllers;

use Storage;
use File;
use Image;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public static function get($filter = NULL) {
        if ($filter == NULL) {
            return new City;
        }
        return City::where($filter);
    }
    public function store(Request $request) {
        $image = $request->file('image');
        $imageFileName = $image->getClientOriginalName();

        $saveData = City::create([
            'name' => $request->name,
            'image' => $imageFileName,
            'priority' => 0
        ]);

        $path=public_path('storage/city_image');
        $img = Image::make($image->path());
        File::makeDirectory($path, $mode=0777, true, true);
        $img->save($path.'/'.$imageFileName);

        // $image->storeAs('public/city_image', $imageFileName);

        return redirect()->route('admin.city')->with(['message' => "Kota baru berhasil ditambahkan"]);
    }
    public function delete($id) {
        $data = City::where('id', $id);
        $city = $data->first();

        $path = public_path('storage/city_image');
        File::delete($path.'/'.$city->image);

        $deleteData = $data->delete();
        // $deleteImage = Storage::delete('public/city_image/' . $city->image);

        return redirect()->route('admin.city')->with(['message' => "Kota ". $city->name ." berhasil dihapus"]);
    }
    public function priority($id, $type) {
        $data = City::where('id', $id);
        if ($type == 'increase') {
            $data->increment('priority');
        } else {
            $data->decrement('priority');
        }
        return redirect()->route('admin.city');
    }
}
