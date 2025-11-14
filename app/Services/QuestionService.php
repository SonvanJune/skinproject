<?php

namespace App\Services;

use App\DTOs\AssignQuestionListForUserDTO;
use App\DTOs\CheckQuestionListForUserDTO;
use App\DTOs\CreateQuestionDTO;
use App\DTOs\DeleteQuestionDTO;
use App\DTOs\GetQuestionDTO;
use App\DTOs\PaginatedDTO;
use App\DTOs\UpdateQuestionDTO;
use App\Models\Question;
use App\Models\User;
use App\Models\UserQuestion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class QuestionService
{
    public const PER_PAGE = 10; // Number of items per page for pagination
    public const DEFAULT_PAGE = 1; // Default page for pagination

    // Maximum number of questions a user can be assigned
    public const MAX_QUESTIONS_QUANTITY = 3;


    // Number of questions a user must answer to verify identity
    public const QUESTIONS_QUANTITY_TO_ANSWER = 3;

    /**
     * Create a new question in the database.
     * 
     * @param Request $request Incoming request containing question details.
     * @return CreateQuestionDTO|string DTO if successful, error message if failed.
     */
    public function createQuestion(Request $request): CreateQuestionDTO|string
    {
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        if (Question::where("question_text", $request->input("question_text"))->exists()) {
            return "Question already exists";
        }

        DB::beginTransaction();
        try {
            $question = new Question();
            $question->question_id = Str::uuid()->toString();
            $question->question_text = $request->question_text;
            $question->created_at = now();

            if (!$question->save()) {
                return "Cannot create a new question";
            }

            DB::commit();
            return CreateQuestionDTO::fromModel($question);
        } catch (Exception $e) {
            DB::rollBack();
            return 'Failed to create question: ' . $e->getMessage();
        }
    }

    /**
     * Update an existing question in the database.
     * 
     * @param Request $request Incoming request with updated question details.
     * @return UpdateQuestionDTO|string DTO if successful, error message if failed.
     */
    public function updateQuestion(Request $request): UpdateQuestionDTO|string
    {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|uuid',
            'question_text' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        if (Question::where("question_text", $request->input("question_text"))->exists()) {
            return "Question already exists";
        }

        $question = Question::where("question_id", $request->input("question_id"))->first();

        if (!$question) {
            return "Question not found";
        }

        if ($question->question_text === $request->question_text) {
            return "Question text is unchanged";
        }

        if (UserQuestion::where("question_id", $request->input("question_id"))->exists()) {
            return "Question is already answered by users";
        }

        DB::beginTransaction();
        try {
            $question->question_text = $request->question_text;
            $question->updated_at = now();

            if (!$question->save()) {
                return "Cannot update question";
            }

            DB::commit();
            return UpdateQuestionDTO::fromModel($question);
        } catch (Exception $e) {
            DB::rollBack();
            return 'Failed to update question: ' . $e->getMessage();
        }
    }

    /**
     * Delete an unused question from the database.
     * 
     * @param Request $request Incoming request containing the question ID.
     * @return DeleteQuestionDTO|string DTO if successful, error message if failed.
     */
    public function deleteQuestion(Request $request): DeleteQuestionDTO|string
    {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $question = Question::where("question_id", $request->input("question_id"))->first();

        if (!$question) {
            return "Question not found";
        }

        if (UserQuestion::where("question_id", $request->input("question_id"))->exists()) {
            return "Question is already answered by users";
        }

        DB::beginTransaction();
        try {
            if (!$question->delete()) {
                return "Cannot delete question";
            }

            DB::commit();
            return DeleteQuestionDTO::fromModel($question);
        } catch (Exception $e) {
            DB::rollBack();
            return 'Failed to delete question: ' . $e->getMessage();
        }
    }

    /**
     * Retrieve all questions from the database.
     * 
     * @param Request $request Incoming request.
     * @return PaginatedDTO List of questions as DTOs.
     */
    public function getQuestionList(Request $request): PaginatedDTO | string
    {
        $validator = Validator::make(
            $request->all(),
            [
                'page' => 'nullable|numeric|integer',
                'per_page' => 'nullable|numeric|integer',
                'key' => 'nullable|string'
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $perPage = $request->input('per_page', $this::PER_PAGE);
        $page = $request->input('page', $this::DEFAULT_PAGE);
        $skip = ($page - 1) * $perPage;
        $key = $request->has('key') ? $request->key : "";

        $questions = Question::orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->where('question_text', 'LIKE', '%' . $key . '%')
            ->skip($skip)->take($perPage)
            ->orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $questions->load("users");

        $total = Question::where('question_text', 'LIKE', '%' . $key . '%')->count();

        return PaginatedDTO::fromData(GetQuestionDTO::fromModels($questions), $page, $perPage, $total, $key ?? "");
    }

    /**
     * Retrieve questions assigned to a specific user.
     * 
     * @param Request $request Incoming request.
     * @param User $user User model instance.
     * @return array List of user-specific questions as DTOs.
     */
    public function getQuestionListOfUser(Request $request, User $user): array
    {
        //get questions of user in a specific order
        $questions = Question::whereHas('users', function ($query) use ($user) {
            $query->where('users.user_id', $user->user_id);
        })->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        return GetQuestionDTO::fromModels($questions);
    }

    /**
     * Assign a set of questions to a user.
     * 
     * @param Request $request Incoming request containing user ID and question list.
     * @param UserService $userService service which check that password level 2 of user.
     * @return string|AssignQuestionListForUserDTO DTO if successful, error message if failed.
     */
    public function assignQuestionListForUser(Request $request, User $user, UserService $userService): string|AssignQuestionListForUserDTO
    {
        Validator::extend('question_list', function ($attribute, $value) {
            try {
                foreach ($value as $item) {
                    if (
                        !isset($item['question_id']) ||
                        !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $item['question_id']) ||
                        !isset($item['user_answer']) ||
                        strlen($item['user_answer']) > 255
                    ) {
                        return false;
                    }
                }
                return true;
            } catch (\ErrorException $e) {
                return false;
            }
        });

        $didHaveSecurityQuestions = count(UserQuestion::where('user_id', $user->user_id)->get()) == self::MAX_QUESTIONS_QUANTITY;

        $validateArray = [
            'question_list' => 'required|question_list',
        ];

        if ($didHaveSecurityQuestions) {
            $validateArray = [
                'user_password_level_2' => 'required|max:255|min:' . UserService::MIN_PASS_LENGTH,
                'question_list' => 'required|question_list',
            ];
        }

        $validator = Validator::make($request->all(), $validateArray);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        if ($didHaveSecurityQuestions && !$userService->checkPasswordLevel2($user, $request->input('user_password_level_2'))) {
            return "Invalid user password level 2";
        }

        if (count($request->input("question_list")) != self::MAX_QUESTIONS_QUANTITY) {
            return "The amount of questions for the user does not match the system policy";
        }

        $questionIds = [];
        foreach ($request->input('question_list') as $question) {
            $questionIds[] = $question['question_id'];
        }
        if (count(Question::whereIn("question_id", $questionIds)->get()) != self::MAX_QUESTIONS_QUANTITY) {
            return "Questions not found enough to be assigned";
        }
        DB::beginTransaction();
        try {
            $user->questions()->detach();
            $syncData = [];
            $user->save();

            foreach ($request->input('question_list') as $answer) {
                $syncData[$answer['question_id']] = ['user_answer' => Hash::make($answer['user_answer'])];
            }

            $user->questions()->attach($syncData);

            if (!$user->save()) {
                return "Cannot update questions of user";
            }

            DB::commit();
            return AssignQuestionListForUserDTO::fromModel($user);
        } catch (Exception $e) {
            DB::rollBack();
            return "Update list of questions for user failed: " . $e->getMessage();
        }
    }

    /**
     * Verify answers for a user's assigned questions.
     * 
     * @param Request $request Incoming request containing user ID and answers.
     * @return string|CheckQuestionListForUserDTO DTO if successful, error message if failed.
     */
    public function checkQuestionListForUser(Request $request, User $user): string|CheckQuestionListForUserDTO
    {
        Validator::extend('question_list', function ($attribute, $value) {
            try {
                foreach ($value as $item) {
                    if (
                        !isset($item['question_id']) ||
                        !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $item['question_id']) ||
                        !isset($item['user_answer']) ||
                        strlen($item['user_answer']) > 255
                    ) {
                        return false;
                    }
                }
                return true;
            } catch (\ErrorException $e) {
                return false;
            }
        });

        $validator = Validator::make($request->all(), [
            'question_list' => 'required|question_list',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        if (count($request->input("question_list")) != self::QUESTIONS_QUANTITY_TO_ANSWER) {
            return "The amount of questions for the user does not match the system policy";
        }

        $questionsOfUser = UserQuestion::where("user_id", $user->user_id)->get();

        if (!$questionsOfUser || count($questionsOfUser) != self::MAX_QUESTIONS_QUANTITY) {
            return "User has not set security questions yet";
        }

        foreach ($questionsOfUser as $question) {
            $isValid = false;
            foreach ($request->input('question_list') as $inputQuestion) {
                if (
                    $inputQuestion['question_id'] === $question->question_id &&
                    Hash::check($inputQuestion['user_answer'], $question->user_answer)
                ) {
                    $isValid = true;
                    break;
                }
            }

            if (!$isValid) {
                return "Incorrect answers";
            }
        }

        return CheckQuestionListForUserDTO::fromModel($user, true);
    }
}
