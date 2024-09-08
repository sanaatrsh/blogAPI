<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\CommentNotification;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(string $id)
    {
        $posts = Comment::where('post_id', $id)->get();
        return CommentResource::collection($posts);
    }

    public function store(string $post_id, StoreCommentRequest $request)
    {

        $validated = $request->validated();
        $comment = Comment::create($validated);

        $post = Post::findOrFail($post_id)->author_id;
        $postOwner = User::findOrFail($post);

        $notification = new CommentNotification();
        $postOwner->notify($notification);

        return response()->json([
            'massage' => 'comment add successfully',
            'post' => $comment,
            // 'notification' => ''
        ]);
    }

    public function update(string $post_id, string $id, UpdateCommentRequest $request)
    {

        $comment = Comment::findOrFail($id);
        $validated = $request->validated();
        $comment->update($validated);

        return response()->json([
            'massage' => 'comment updated successfully',
            'post' => $comment,
        ]);
    }

    public function destroy(string $post_id, string $id,)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->post_id == $post_id) {
            // dd($comment);

            $comment->delete();
            return response()->json([
                'massage' => 'post deleted successfully',
                'post' => $comment,
            ]);
        } else {
            return response()->json([
                'massage' => 'comment not found in this post :<',
                'code' => '404',
            ]);
        }
    }
}
