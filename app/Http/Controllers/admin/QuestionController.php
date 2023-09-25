<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MasterQuestion;
use App\Models\SubQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        try {

            $questions = MasterQuestion::all();


            $page_name = 'Questions';

            return view('admin.masterQuestions.index', [
                'page_name' => $page_name,
                'questions' => $questions,
            ]);
        } catch (\Exception $e) {
            redirect()->back()
                ->with('error', 'Something went wrong!');
        }
    }







    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $page_name = 'Questions';
            return view('admin.masterQuestions.create', ['page_name' => $page_name]);
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
            $this->validate($request, [
                'question' => 'required|unique:master_questions,question',
                'status' => 'required',
            ]);

            $input = $request->all();
            $question = MasterQuestion::create($input);

            return redirect()->route('question.index')
                ->with('success', 'Question created successfully');
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
            $question = MasterQuestion::find($id);
            $page_name = 'Questions';

            return view('admin.masterQuestions.show', compact('question', 'page_name'));
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
            $question = MasterQuestion::find($id);
            $page_name = 'Questions';

            return view('admin.masterQuestions.edit', compact('question', 'page_name'));
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
                'question' => 'required|unique:master_questions,question,' . $id,
                'status' => 'required',
            ]);

            $input = $request->all();
            $question = MasterQuestion::find($id);

            if ($question) {
                $question->update($input);

                return redirect()->route('question.index')
                    ->with('success', 'Question updated successfully');
            } else {
                return redirect()->route('question.index')
                    ->with('error', 'Question not found');
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
            $question = MasterQuestion::find($id);

            if ($question) {
                $question->delete();

                return redirect()->route('question.index')
                    ->with('success', 'Question deleted successfully');
            } else {
                return redirect()->route('question.index')
                    ->with('error', 'Question not found');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong!');
        }
    }






    /**
     * Update the first question flag for a specific question.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateFirstQuestion(Request $request)
    {
        try {
            // Update all rows to set 'is_first_question' to 0
            MasterQuestion::updateAllRows(['is_first_question' => 0]);

            // Prepare the input data for the current question
            $input['is_first_question'] = $request->is_first_question;
            $question = MasterQuestion::find($request->id);

            if ($question) {
                // Update the 'is_first_question' flag for the current question
                $question->update($input);

                return redirect()->route('question.index')
                    ->with('success', 'First question updated successfully');
            } else {
                return redirect()->route('question.index')
                    ->with('error', 'Question not found');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong!');
        }
    }





    /**
     * Update the last question flag for a specific question.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateLastQuestion(Request $request)
    {
        try {
            // Update all rows to set 'is_last_question' to 0
            MasterQuestion::updateAllRows(['is_last_question' => 0]);

            // Prepare the input data for the current question
            $input['is_last_question'] = $request->is_last_question;
            $question = MasterQuestion::find($request->id);

            if ($question) {
                // Update the 'is_last_question' flag for the current question
                $question->update($input);

                return redirect()->route('question.index')
                    ->with('success', 'Last question updated successfully');
            } else {
                return redirect()->route('question.index')
                    ->with('error', 'Question not found');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong!');
        }
    }






    /**
     * Get questions based on the specified level.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLevelQuestions(Request $request)
    {
        if ($request->ajax()) {
            // Check if the request is an AJAX request
            if ($request->level == 1) {
                // If the requested level is 1, retrieve all questions from the MasterQuestion model
                $questions = MasterQuestion::all();
            } else {
                // If the requested level is not 1, calculate the previous level and retrieve questions from the SubQuestion model
                $level = $request->level - 1;
                $questions = SubQuestion::where('level_id', $level)->get();
            }
            return response()->json($questions);
        }
    }






    /**
     * Get sub-questions based on the specified master question and level.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMasterSubQuestions(Request $request)
    {
        if ($request->ajax()) {
            $master_id = $request->qt_id;

            if ($request->level == 0) {
                // If the requested level is 0, retrieve sub-questions with 'level_id' equal to 1
                $questions = SubQuestion::where('master_id', $master_id)->where('level_id', 1)->get();
            } else {
                // If the requested level is not 0, retrieve sub-questions with 'level_id' not equal to 1
                $questions = SubQuestion::where('master_id', $master_id)->where('level_id', '!=', 1)->get();
            }

            if (count($questions) > 0) {
                // Check if there are sub-questions and determine if each has sub-questions
                foreach ($questions as $qt) {
                    $qt_count = SubQuestion::where('master_id', $qt->id)->where('level_id', '!=', 1)->count();
                    $qt['is_sub_qt'] = ($qt_count > 0) ? 1 : 0;
                }
            }

            return response()->json($questions);
        }
    }






    /**
     * Get sub-questions for a specific master question.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function masterQuestionsAndSubQuestions(Request $request)
    {
        if ($request->ajax()) {
            // Check if the request is an AJAX request
            $sub_question = SubQuestion::where('master_id', $request->main_question_id)->where('level_id', '!=', 1)->get();

            // Retrieve sub-questions for the specified master question

            return response()->json($sub_question);
        }
    }
}
