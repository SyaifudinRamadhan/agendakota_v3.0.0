<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\Page\PageStoreRequest;

class PageController extends Controller
{
    public function index()
    {
        //
    }


    public function create()
    {
        return view('admin.page.page');
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            //get filename with extension
            $filenamewithextension = $request->file('upload')->getClientOriginalName();


            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

            //get file extension
            $extension = $request->file('upload')->getClientOriginalExtension();

            //filename to store
            $filenametostore = $filename . '_' . time() . '.' . $extension;

            //Upload File
            $request->file('upload')->storeAs('public/uploads', $filenametostore);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('storage/uploads/' . $filenametostore);
            $msg = 'Image successfully uploaded';
            $re = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            // Render HTML output
            @header('Content-type: text/html; charset=utf-8');
            echo $re;
        }
    }

    public function store(PageStoreRequest $request)
    {
        $slug = Str::slug($request->txtTitle);
        Page::create([
            'slug' => $slug,
            'title' => $request->txtTitle,
            'body' => $request->konten,
        ]);
        return redirect()->back()->with('berhasil', 'Konten Telah Berhasil Dibuat');
    }


    public function show()
    {
        $show = Page::get();

        return view('admin.page', compact('show'));
    }


    public function edit($id)
    {
        $page = Page::find($id);
        return view('admin.page.edit_page', compact('page'));
    }



    public function update(Request $request)
    {        
        $slug = Str::slug($request->txtTitle);
        Page::where('id', $request->id)->update([
            'slug' => $slug,
            'title' => $request->txtTitle,
            'body' => $request->konten
        ]);
        return redirect()->back()->with('berhasil', 'Konten Telah Berhasil Diedit');
    }

    public function destroy($id)
    {
        $page = Page::find($id);
        $page->delete();

        return redirect()->back()->with('berhasil', 'Konten Telah Berhasil dihapus');
    }

    public function read($slug) {
        $page = Page::where('slug', $slug)->first();

        if ($page == "") {
            return abort(404);
        }
    }

}
