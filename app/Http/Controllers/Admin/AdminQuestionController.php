<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Services\QuestionService;
use App\Services\UserService;
use Illuminate\Http\Request;

class AdminQuestionController extends Controller
{
    // Dependency injection for QuestionService
    protected $questionService;
    protected $userService;
    public function __construct(QuestionService $questionService, UserService $userService)
    {
        $this->questionService = $questionService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the security questions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $page = $request->query('page');
        $per_page = $request->query('per_page');

        if (!$page || !is_numeric($page) || $page < 1) {
            $page = 1;
        }

        if (!$per_page || !is_numeric($per_page) || $per_page < 1) {
            $per_page = QuestionService::PER_PAGE;
        }

        $request->merge(["page" => $page, "per_page" => $per_page]);

        $paginatedDTO = $this->questionService->getQuestionList($request);

        if (is_string($paginatedDTO)) {
            return redirect()->route('admin.index')->with('error', $paginatedDTO);
        }

        return view('admin.questions.index', compact('paginatedDTO'));
    }

    /**
     * Display a form to create new question.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);

        return view('admin.questions.create');
    }

    /**
     * Store a newly created question in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $createQuestionDTO = $this->questionService->createQuestion($request);

        if (is_string($createQuestionDTO)) {
            return redirect()->route('admin.questions')->with('error', $createQuestionDTO);
        }

        return redirect()->route('admin.questions')->with('success', 'Add new question successfully!');
    }

    /**
     * Display a form to edit new question.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, string $id, bool $duplicated = false)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);

        $question = Question::where('question_id', $id)->first();

        if (!$question) {
            return back()->with('error', 'Question not found');
        }

        return view('admin.questions.edit', compact('question', 'duplicated'));
    }


    /**
     * Update the specified question in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, bool $duplicated = false)
    {
        if ($duplicated) {
            return $this->store($request);
        }

        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $updateQuestionDTO = $this->questionService->updateQuestion($request);

        if (is_string($updateQuestionDTO)) {
            return redirect()->route('admin.questions')->with('error', $updateQuestionDTO);
        }

        return redirect()->route('admin.questions')->with('success', 'Update question successfully!');
    }

    /**
     * Remove the specified question from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $id)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $request->merge(["question_id" => $id]);

        $deleteQuestionDTO = $this->questionService->deleteQuestion($request);

        if (is_string($deleteQuestionDTO)) {
            return redirect()->route('admin.questions')->with('error', $deleteQuestionDTO);
        }

        return redirect()->route('admin.questions')->with('success', 'Delete successfully!');
    }
}
