<?php

namespace App\Http\Controllers;

use Storage;
use App\Models\BoothProduct as Product;
use Illuminate\Http\Request;

class BoothProductController extends Controller
{
    public static function get($filter = NULL) {
        if ($filter == NULL) {
            return new Product;
        }
        return Product::where($filter);
    }
    public function store(Request $request) {
        $myData = AgentController::me();
        $booth = ExhibitorController::get([
            ['id', $myData->booth_id]
        ])->with('event')->first();
        $event = $booth->event;

        $image = $request->file('image');
        $imageFileName = $image->getClientOriginalName();

        $saveData = Product::create([
            'booth_id' => $myData->booth_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imageFileName,
        ]);

        $image->storeAs('public/event_assets/'.$event->slug."/exhibitors/products/", $imageFileName);

        return redirect()->route('agent.product')->with([
            'message' => "Produk baru berhasil ditambahkan"
        ]);
    }
    public function update(Request $request) {
        $id = $request->id;
        $data = Product::where('id', $id);
        $product = $data->first();

        $toUpdate = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageFileName = $image->getClientOriginalName();
            $deleteOldImage = Storage::delete("public/event_assets/".$event->slug."/exhibitors/products/".$product->image);
            $uploadeNewImage = $image->storeAs("public/event_assets/".$event->slug."/exhibitors/products/", $imageFileName);
            $toUpdate['image'] = $imageFileName;
        }

        $updateData = $data->update($toUpdate);

        return redirect()->route('agent.product')->with([
            'message' => "Data produk ".$product->name." berhasil diubah"
        ]);
    }
    public function delete(Request $request) {
        $id = $request->id;
        $data = Product::where('id', $id);
        $product = $data->with('booth.event')->first();
        $event = $product->booth->event;
        
        $deleteData = $data->delete();
        $deleteImage = Storage::delete("public/event_assets/".$event->slug."/exhibitors/products/".$product->image);

        return redirect()->route('agent.product')->with([
            'message' => "Berhasil menghapus produk ".$product->name
        ]);
    }
}
