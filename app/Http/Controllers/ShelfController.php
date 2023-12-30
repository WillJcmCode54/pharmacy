<?php

namespace App\Http\Controllers;

use App\Models\Shelf;
use Illuminate\Http\Request;

class ShelfController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shelfs = Shelf::all();
        return view("shelf.index", compact("shelfs"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("shelf.create");
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
        $shelf = Shelf::create($request->all());

        if ($shelf) {
            return redirect()->route("shelf.index")->with("success","Se ha guardado con exito");
        } else {
            return redirect()->back()->with('error','Se ha producido un error');
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shelf = Shelf::find($id);
        return view('shelf.view', compact('shelf'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $shelf = Shelf::find($id);
        return view('shelf.edit', compact('shelf'));
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

        $shelf = Shelf::find($id);
        $shelf->name = $request->name;
        $shelf->decription = $request->decription;
        $shelf->save();

        if ($shelf) {
            return redirect()->route("shelf.index")->with("success","Se ha guardado con exito");
        } else {
            return redirect()->back()->with('error','Se ha producido un error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shelf = Shelf::find($id);
        $name = $shelf->name;
        $shelf->delete();
        return redirect()->route('shelf.index')->with('error','Se ha eliminado a '.$name );
    }
}
