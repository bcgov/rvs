<?php

namespace Modules\Yeaf\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Modules\Yeaf\Entities\Comment;
use Modules\Yeaf\Entities\Student;
use Modules\Yeaf\Http\Requests\CommentStoreRequest;

class CommentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param CommentStoreRequest $request
     *
     * @return RedirectResponse
     */
    public function store(CommentStoreRequest $request): RedirectResponse {
        $comment = Comment::create($request->validated());
        $student = Student::where('student_id', $comment->student_id)->first();

        return Redirect::route('yeaf.students.show', [$student->id]);
    }
}
