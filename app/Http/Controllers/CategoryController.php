<?php

namespace App\Http\Controllers;

use Str;
use App\Models\Category;
use Illuminate\Http\Request;
use Image;
use File;

class CategoryController extends Controller
{
    public static function get($filter = NULL) {
        if ($filter == NULL) {
            return new Category;
        }
        return Category::where($filter);
    }
    public function store(Request $request) {
        $cover = $request->file('cover');
        // $coverFileName = $cover->getClientOriginalName();

        $saveData = Category::create([
            'name' => $request->name,
        ]);
        // dd($coverFileName);
        $path = public_path('images/category');
        $img = Image::make($cover->path());
        File::makeDirectory($path, $mode = 0777, true, true);
        $img->save($path.'/'.$request->name.'.jpg');
        // $cover->storeAs($path, $request->name.'.jpg');

        return redirect()->route('admin.category')->with([
            'message' => "Kategori ".$saveData->name." berhasil ditambahkan"
        ]);
    }
    public function update(Request $request) {
        $id = $request->id;

        $data = Category::where('id', $id);
        $category = $data->first();
        $slug = Str::slug($request->name);

        $toUpdate = [
            'name' => $request->name,
        ];

        if ($request->hasFile('cover')) {
            $cover = $request->file('cover');
            // $coverFileName = $cover->getClientOriginalName();
            // $toUpdate['cover'] = $coverFileName;
            $path = public_path('images/category');
            File::delete($path."/".$category->name.'jpg');

            $img = Image::make($cover->path());
            File::makeDirectory($path, $mode=0777, true, true);
            $img->save($path.'/'.$request->name.'.jpg');
            // $cover->storeAs('public/admin/category_cover', $coverFileName);
        }

        $updateData = $data->update($toUpdate);

        return redirect()->route('admin.category')->with([
            'message' => "Kategori ".$category->name." berhasil diubah"
        ]);
    }
    public function delete($id) {
        $data = Category::where('id', $id);
        $category = $data->first();

        $path = public_path('images/category');
        File::delete($path.'/'.$category->name.'.jpg');

        $deleteData = $data->delete();

        return redirect()->route('admin.category')->with([
            'message' => "Kategori ".$category->name." berhasil dihapus"
        ]);
    }
}
