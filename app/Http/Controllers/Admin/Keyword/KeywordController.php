<?php

namespace App\Http\Controllers\Admin\Keyword;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Keyword;

class KeywordController extends Controller
{
    public function getKeywords(Request $request){
        $keywords = Keyword::query();
        if ($request->has('q')) {
            $search = $request->q;
            $keywords->where('name', 'LIKE', "%{$search}%");
        }
    
        $results = $keywords->select('id', 'name')->get();
        return response()->json($results);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $keyword = Keyword::create($inputs);
        return response()->json($keyword);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
