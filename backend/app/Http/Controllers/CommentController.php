<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = DB::table('comments')->orderBy('id', 'desc')->get();
        return response(compact('comments'), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $post_id)
    {
        $data = $request->validate([
            'content' =>'required|string|max:255',
        ]);

        $data['post_id'] = $post_id;
        $data['user_id'] = $request->user()->id;

        Comment::create($data);

        return response([
            'message' => 'Commentaire créé avec succès',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return response(compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $comment_user_id = $comment->user_id;
        $user_request_id = $request->user()->id;

        if (!($comment_user_id == $user_request_id)) {
            return response([
               'message' => 'Vous n\'avez pas le droit de modifier cet commentaire'
            ], 403);
        }

        $data = $request->validate([
            'content' =>'required|string|max:255',
        ]);

        $comment->content = $data['content'];
        $comment->update();

        $message = 'Commentaire éditée avec succès !';

        return response(compact('message'), 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Comment $comment)
    {
        $comment_user_id = $comment->user_id;
        $user_request_id = $request->user()->id;

        if (!($comment_user_id == $user_request_id)) {
            return response([
               'message' => 'Vous n\'avez pas le droit de supprimer cet commentaire.',
            ], 403);
        }

        $comment->delete();
        return response([
            'message' => 'Commentaire supprimée avec succès'
        ]);
    }
}
