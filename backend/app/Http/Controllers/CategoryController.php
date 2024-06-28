<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = DB::table('categories')->orderBy('id', 'desc')->get();
        return response(compact('categories'), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:55',
        ]);

        Category::create($data);
        $message = 'Catégorie créée avec succès !';

        return response(compact('message'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response(compact('category'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:55',
        ]);

        $category->update($data);
        $message = 'Catégorie éditée avec succès !';

        return response(compact('message'), 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        $message = 'Catégorie supprimée avec succès !';

        return response(compact('message'), 200);
    }
}
