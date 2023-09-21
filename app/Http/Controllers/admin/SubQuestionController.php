<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
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
        $questions = MasterQuestion::all();
        $levels = Level::all();
        $page_name = 'Sub Questions';
        return view('admin.subQuestions.index', [
            'questions' => $questions,
            'levels' => $levels,
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
        $levels = Level::all();
        return view('admin.subQuestions.create', [
            'page_name' => $page_name,
            'questions' => $questions,
            'levels' => $levels,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $count = $request->get('row_count');
        $questions = $request->get('question');
        $answers = $request->get('answer');
        $is_repeat = $request->get('is_repeat');
        $is_repeat = isset($is_repeat) ? $is_repeat : [];
        $level_id = $request->get('level');
        $master_id = $request->get('main_question_id');



        for ($i = 0; $i < $count; $i++) {

            $input['question'] = $questions[$i];
            $input['answer'] = $answers[$i];
            $input['level_id'] = $level_id;
            $input['master_id'] = $master_id;
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
        $sub_question = SubQuestion::find($id);
        $page_name = 'Questions';
        return view('admin.subQuestions.show', compact('sub_question', 'page_name'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $question = SubQuestion::find($id);
        $page_name = 'Sub Questions';
        return view('admin.SubQuestions.edit', compact('question', 'page_name'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $this->validate($request, [
            'question' => 'required',
        ]);

        $input = $request->all();
        $question = SubQuestion::find($id);
        $question->update($input);
        return redirect()->route('subquestion.index')->with('success', 'Sub Question updated successfully');;
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
