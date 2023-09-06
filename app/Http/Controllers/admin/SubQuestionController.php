<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MasterQuestion;
use App\Models\SubQuestion;
use Illuminate\Http\Request;

class SubQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $mainQuestions = MasterQuestion::whereHas('SubQuestion')->get();

        // $mainQuestions = MasterQuestion::all();
        $page_name = 'Sub Questions';
        return view('admin.subQuestions.index', [
            'page_name' => $page_name,
            'mainQuestions' => $mainQuestions, // Retrieve the filtered results
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)

    {

        $page_name = 'Sub Questions';
        $questions = MasterQuestion::all();
        return view('admin.subQuestions.create', ['page_name' => $page_name, 'questions' => $questions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       //dd($request->all());

        $count = $request->get('row_count');
        $questions = $request->get('question');
        $answers = $request->get('answer');
        $nextQuestionIds = $request->get('next_question_id');
        $mainQuestionId = $request->get('main_question_id');
        $is_repeat = $request->get('is_repeat');
        

        for ($i = 0; $i < $count; $i++) {
           
            $input['question'] = $questions[$i];
            $input['answer'] = $answers[$i];
            $input['next_question_id'] = $nextQuestionIds[$i];
            $input['main_question_id'] = $mainQuestionId;
            $input['is_repeat'] = (in_array($i, $is_repeat)) ? 1 : 0;
           
            SubQuestion::create($input);
        }

        return redirect()->route('subquestion.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subQuestion = MasterQuestion::find($id);
        $page_name = 'Questions';
        return view('admin.subQuestions.show', compact('subQuestion', 'page_name'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mainQuestion = MasterQuestion::find($id);
        $questions = MasterQuestion::all();
        $page_name = 'Sub Questions';
        return view('admin.SubQuestions.edit', compact('questions', 'mainQuestion', 'page_name'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       $res =  SubQuestion::where('main_question_id', $id)->delete();
        $count = $request->get('row_count');
        $questions = $request->get('question');
        $answers = $request->get('answer');
        $nextQuestionIds = $request->get('next_question_id');
        $mainQuestionId = $request->get('main_question_id');

        for ($i = 0; $i < $count - 1; $i++) {
            $input['question'] = $questions[$i];
            $input['answer'] = $answers[$i];
            $input['next_question_id'] = $nextQuestionIds[$i];
            $input['main_question_id'] = $mainQuestionId;
            SubQuestion::create($input);
        }

        return redirect()->route('subquestion.index') ->with('success', 'Sub Question updated successfully');;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        SubQuestion::find($id)->delete();
        //set actvity log here
        return redirect()->back()
            ->with('success', 'Sub Question deleted successfully');
    }
}
