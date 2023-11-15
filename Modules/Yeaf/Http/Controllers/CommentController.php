<?php

namespace Modules\Yeaf\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Modules\Yeaf\Entities\Comment;
use Modules\Yeaf\Entities\Student;
use Modules\Yeaf\Http\Requests\CommentStoreRequest;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CommentStoreRequest $request)
    {
        $comment = Comment::create($request->validated());
        $student = Student::where('student_id', $comment->student_id)->first();

        return Redirect::route('yeaf.students.show', [$student->id]);
    }
}
