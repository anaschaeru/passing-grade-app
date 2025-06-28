<?php

namespace App\Http\Controllers;

use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function index()
    {
        $majors = Major::all();
        return view('admin.majors.index', compact('majors'));
    }
    public function create()
    {
        return view('admin.majors.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:majors,name',
            'quota' => 'required|integer|min:0',
        ]);
        Major::create($request->all());
        return redirect()->route('majors.index')->with('success', 'Jurusan berhasil ditambahkan.');
    }
    public function show(Major $major)
    {
        return view('admin.majors.show', compact('major'));
    }
    public function edit(Major $major)
    {
        return view('admin.majors.edit', compact('major'));
    }
    public function update(Request $request, Major $major)
    {
        $request->validate([
            'name' => 'required|unique:majors,name,' . $major->id,
            'quota' => 'required|integer|min:0',
        ]);
        $major->update($request->all());
        return redirect()->route('majors.index')->with('success', 'Jurusan berhasil diperbarui.');
    }
    public function destroy(Major $major)
    {
        $major->delete();
        return redirect()->route('majors.index')->with('success', 'Jurusan berhasil dihapus.');
    }
}
