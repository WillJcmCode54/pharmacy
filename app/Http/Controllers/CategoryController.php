<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view("category.index", compact("categories"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("category.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "name"=> ["string","required"],
            "decription"=> ["string","required"]
        ]);
        $category = Category::create($request->all());

        if ($category) {
            return redirect()->route("category.index")->with("success","Se ha guardado con exito");
        } else {
            return redirect()->back()->with('error','Se ha producido un error');
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);
        return view('category.view', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::find($id);
        return view('category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            "name"=> ["string","required"],
            "decription"=> ["string","required"]
        ]);

        $category = Category::find($id);
        $category->name = $request->name;
        $category->decription = $request->decription;
        $category->save();

        if ($category) {
            return redirect()->route("category.index")->with("success","Se ha guardado con exito");
        } else {
            return redirect()->back()->with('error','Se ha producido un error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        $name = $category->name;
        $category->delete();
        return redirect()->route('category.index')->with('error','Se ha eliminado a '.$name );
    }
}
