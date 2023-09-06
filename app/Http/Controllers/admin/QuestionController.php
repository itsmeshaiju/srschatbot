<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MasterQuestion;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
     public function index(Request $request)
     {
         $filterQuestion = "";
         $filterStatus = "";
       
        
         if (request()->method() == 'POST') {
            $questions = MasterQuestion::query();
            
             $filterQuestion = request('filter_question');
             $filterStatus = request('filter_status');
             
             if ($filterQuestion) {
               
                 $questions->where('question', 'like', '%' . $filterQuestion . '%');
                 
             }
             
             if ($filterStatus !== "") {
                 $questions->where('status', $filterStatus);
             }
             $questions->get();
           
         } else {
             $questions = MasterQuestion::all();
         }
        
         $page_name = 'Questions';
        
         return view('admin.masterQuestions.index', [
             'filterQuestion' => $filterQuestion,
             'filterStatus' => $filterStatus,
             'page_name' => $page_name,
             'questions' => $questions, // Retrieve the filtered results
         ]);
     }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)

    {   $page_name = 'Questions';
        return view('admin.masterQuestions.create',['page_name'=> $page_name]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      
        $this->validate($request, [
            'question' => 'required|unique:master_questions,question',
            'status' => 'required',
          
        ]);
        $input = $request->all();
        $question = MasterQuestion::create($input);
       
       
        return redirect()->route('question.index')
            ->with('success', 'Question created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $question = MasterQuestion::find($id);
        $page_name = 'Questions';
        return view('admin.masterQuestions.show', compact('question', 'page_name'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $question = MasterQuestion::find($id);
      
        $page_name = 'Questions';
        return view('admin.masterQuestions.edit', compact('question', 'page_name'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'question' => 'required|unique:master_questions,question',
            'status' => 'required',
          
        ]);

        $input = $request->all();
        $question = MasterQuestion::find($id);
        $question->update($input);
        //set actvity log here
        
        return redirect()->route('question.index')
            ->with('success', 'question updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        MasterQuestion::find($id)->delete();
        //set actvity log here
        return redirect()->route('category.index')
            ->with('success', 'Question deleted successfully');
    }
}
