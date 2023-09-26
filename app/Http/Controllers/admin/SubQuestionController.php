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
    /**
     * Display a listing of the sub-questions.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        try {
            $mainQuestions = MasterQuestion::whereHas('SubQuestion')->get();
            $questions = MasterQuestion::all();
            $levels = Level::all();
            $page_name = 'Sub Questions';

            return view('admin.subQuestions.index', compact('questions', 'levels', 'page_name', 'mainQuestions'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong!');
        }
    }






    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $page_name = 'Sub Questions';
            $questions = MasterQuestion::all();
            $levels = Level::all();

            return view('admin.subQuestions.create', compact('page_name', 'questions', 'levels'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong!');
        }
    }




    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $count = $request->input('row_count');
            $questions = $request->input('question', []);
            $answers = $request->input('answer', []);
            $is_repeat = $request->input('is_repeat', []);
            $level_id = $request->input('level');
            $master_id = $request->input('main_question_id');

            for ($i = 0; $i < $count; $i++) {
                $input = [
                    'question' => $questions[$i] ?? null,
                    'answer' => $answers[$i] ?? null,
                    'level_id' => $level_id,
                    'master_id' => $master_id,
                    'is_repeat' => in_array($i, $is_repeat) ? 1 : 0,
                ];

                SubQuestion::create($input);
            }

            return redirect()->route('subquestion.index')
                ->with('success', 'Sub-questions created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong!');
        }
    }







    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $sub_question = SubQuestion::find($id);
            $page_name = 'Questions';

            return view('admin.subQuestions.show', compact('sub_question', 'page_name'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong!');
        }
    }






    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $question = SubQuestion::find($id);
            $page_name = 'Sub Questions';

            return view('admin.subQuestions.edit', compact('question', 'page_name'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong!');
        }
    }






    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $this->validate($request, [
                'question' => 'required',
            ]);

            $input = $request->all();
            $question = SubQuestion::find($id);

            if ($question) {
                $question->update($input);
                return redirect()->route('subquestion.index')
                    ->with('success', 'Sub Question updated successfully');
            } else {
                return redirect()->route('subquestion.index')
                    ->with('error', 'Sub Question not found');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong!');
        }
    }





    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       
        try {
            $question = SubQuestion::find($id);

            if ($question) {
                $question->delete();
                return redirect()->back()
                    ->with('success', 'Sub Question deleted successfully');
            } else {
                return redirect()->back()
                    ->with('error', 'Sub Question not found');
            }
        } catch (\Exception $e) {
          
            return redirect()->back()
                ->with('error', 'Something went wrong!');
        }
    }
}
