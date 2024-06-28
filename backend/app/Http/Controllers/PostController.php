<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = DB::table('posts')->orderBy('id', 'desc')->get();
        return response(compact('posts'), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:55',
            'content' => 'required|string|max:255',
            'category' => 'required|integer',
        ]);

        $user = $request->user();
        $user_id = $user['id'];

        Post::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'user_id' => $user_id,
            'category_id' => $data['category'],
        ]);

        $message = 'Article créée avec succès !';

        return response(compact('message'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response(compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $post_user_id = $post->user_id;
        $user_request_id = $request->user()->id;

        if (!($post_user_id == $user_request_id)) {
            return response([
               'message' => 'Vous n\'avez pas le droit de modifier cet article'
            ], 403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:55',
            'content' => 'required|string|max:255',
            'category' => 'required|integer',
        ]);

        $post->title = $data['title'];
        $post->content = $data['content'];
        $post->category_id = $data['category'];

        $post->update();

        $message = 'Article éditée avec succès !';

        return response(compact('message'), 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Post $post)
    {
        $post_user_id = $post->user_id;
        $user_request_id = $request->user()->id;

        if (!($post_user_id == $user_request_id)) {
            return response([
               'message' => 'Vous n\'avez pas le droit de supprimer cet article'
            ], 403);
        }

        $post->delete();
        return response([
            'message' => 'Article supprimée avec succès'
        ]);
    }
}
