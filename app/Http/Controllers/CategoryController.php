<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Tampilkan daftar kategori
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Tampilkan form untuk membuat kategori baru
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Simpan kategori baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Tampilkan form untuk mengedit kategori
     */
    public function edit(Category $category)
    {
        // Menggunakan Route Model Binding (otomatis mencari berdasarkan ID)
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update data kategori di database
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            // Validasi unik, namun mengabaikan ID kategori yang sedang diedit agar tidak error
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Hapus kategori dari database
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}