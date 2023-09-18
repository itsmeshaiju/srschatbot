<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MasterQuestion;
use App\Models\SubQuestion;
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

    {
        $page_name = 'Questions';
        return view('admin.masterQuestions.create', ['page_name' => $page_name]);
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
    public function updateFirstQuestion(Request $request)
    {
        MasterQuestion::updateAllRows(['is_first_question' => 0]);
        $input['is_first_question'] = $request->is_first_question;
        $question = MasterQuestion::find($request->id);
        $question->update($input);
        //set actvity log here

        return redirect()->route('question.index')
            ->with('success', 'First question updated successfully');
    }
    public function updateLastQuestion(Request $request)
    {
        MasterQuestion::updateAllRows(['is_last_question' => 0]);
        $input['is_last_question'] = $request->is_last_question;
        $question = MasterQuestion::find($request->id);
        $question->update($input);
        //set actvity log here

        return redirect()->route('question.index')
            ->with('success', 'Last question updated successfully');
    }
    public function getLevelQuestions(Request $request)
    {
        if ($request->ajax()) {
            if ($request->level == 1) {
                $questions = MasterQuestion::all();
            } else {
                $level = $request->level  - 1;
                $questions = SubQuestion::where('level_id', $level)->get();
            }
            return response()->json($questions);
        }
    }
    public function getMasterSubQuestions(Request $request)
    {

        if ($request->ajax()) {
            $master_id = $request->qt_id;
            $questions = SubQuestion::where('main_question_id', $master_id)->get();
            return response()->json($questions);
        }
    }
    public function masterQuestionsAndSubQuestions(Request $request){
        if ($request->ajax()) {
            $sub_question = SubQuestion::where('main_question_id', $request->main_question_id)->where('level_id','!=',1)->get();
            return response()->json($sub_question);
        }
    }
}
