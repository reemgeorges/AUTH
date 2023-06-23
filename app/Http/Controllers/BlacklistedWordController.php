<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BlacklistedWord;
use Illuminate\Http\Request;

class BlacklistedWordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blacklistedWords = BlacklistedWord::all();
        return view('blacklistedWord.index', compact('blacklistedWords'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blacklistedWord.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'word' => 'required|unique:blacklisted_words'
        ]);

        BlacklistedWord::create([
            'word' => $request->input('word')
        ]);

        return redirect()->route('blacklistedWords.index')->with('success', 'Blacklisted word added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlacklistedWord $blacklistedWord)
    {
        $blacklistedWord->delete();
        return redirect()->route('blacklistedWords.index')->with('success', 'Blacklisted word deleted successfully.');
    }
}
