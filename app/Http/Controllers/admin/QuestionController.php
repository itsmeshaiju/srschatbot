<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index()
    {
        $page_name = 'Questions';
        return view('admin.masterQuestions.index',['page_name'=> $page_name]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $page_name = 'Questions';
        return view('admin.masterQuestions.create', ['page_name' => $page_name]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       // $category = Category::find($id);
       $category = [];
        $page_name = 'Questions';
        return view('admin.masterQuestions.show', compact('category', 'page_name'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       // $category = Category::find($id);
       $category = [];
        $page_name = 'Questions';
        return view('admin.masterQuestions.edit', compact('category', 'page_name'));
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
