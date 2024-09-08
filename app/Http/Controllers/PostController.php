<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::paginate(10);
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        $imagesData = $this->uploadImage($request);
        $validated = $request->merge([
            'imagePath' => $imagesData,
        ]);
        $validated = $request->except('image');


        $post = Post::create($validated);

        return response()->json([
            'massage' => 'post add successfully',
            'post' => $post,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $post = Post::findOrFail($id);
        return $post;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request,  Post $post)
    {
        $validated = $request->validated();

        $post->update($validated);

        return response()->json([
            'massage' => 'post updated successfully',
            'post' => $post,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json([
            'massage' => 'post deleted successfully',
            'post' => $post,
        ]);
    }

    //myFunctions
    protected function uploadImage(Request $request)
    {
        if (!$request->hasFile('image')) {
            return;
        }
        $file = $request->file('image');
        $path = $file->store('uploads', [
            'disk' => 'public'
        ]);
        return $path;
    }
}
