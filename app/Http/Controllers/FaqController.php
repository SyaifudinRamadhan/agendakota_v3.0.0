<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Requests\admin\faq\FaqStoreRequest;

class FaqController extends Controller
{

    public function index()
    {
        $faq = Faq::all();
        return view('admin.faq.index', compact('faq'));
    }


    public function create()
    {
        return view('admin.faq.create');
    }


    public function store(FaqStoreRequest $request)
    {
        Faq::create([
            'pertanyaan' => $request->pertanyaan,
            'jawaban' => $request->jawaban
        ]);
        return redirect(route('admin.faq.index'))->with('berhasil', 'FAQ Berhasil Ditambahkan');
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $faq = Faq::find($id);
        return view('admin.faq.edit', compact('faq'));
    }

    public function update(Request $request)
    {
        Faq::where('id', $request->id)->update([
            'pertanyaan' => $request->pertanyaan,
            'jawaban' => $request->jawaban
        ]);
        return redirect(route('admin.faq.index'))->with('berhasil', 'FAQ Berhasil Diedit');
    }

    public function destroy($id)
    {

        $faq = Faq::find($id);
        $faq->delete();
        return redirect(route('admin.faq.index'))->with('berhasil', 'FAQ Berhasil Dihapus');
    }
}
