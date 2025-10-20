<?php

namespace App\Http\Controllers;

use App\Models\BidangStudi;
use Illuminate\Http\Request;

class BidangStudiController extends Controller
{
    public function index()
    {
        $bidangStudi = BidangStudi::latest()->paginate(10);
        return view('admin.bidangstudi.index', compact('bidangStudi'));
    }

    public function create()
    {
        return view('admin.bidangstudi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        BidangStudi::create($request->only('name'));

        return redirect()->route('admin.bidang-studi.index')
            ->with('success', 'Bidang studi berhasil ditambahkan.');
    }

    public function edit(BidangStudi $bidangStudi)
    {
        return view('admin.bidangstudi.edit', compact('bidangStudi'));
    }

    public function update(Request $request, BidangStudi $bidangStudi)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $bidangStudi->update($request->only('name'));

        return redirect()->route('admin.bidang-studi.index')
            ->with('success', 'Bidang studi berhasil diperbarui.');
    }

    public function destroy(BidangStudi $bidangStudi)
    {
        $bidangStudi->delete();

        return redirect()->route('admin.bidang-studi.index')
            ->with('success', 'Bidang studi berhasil dihapus.');
    }
}
