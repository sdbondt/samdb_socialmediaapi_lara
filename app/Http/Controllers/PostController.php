<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store() {
        $attr = request()->validate([
            'content' => 'required'
        ]);
        $post = request()->user()->posts()->create($attr);
        return [
            'post' => $post
        ];
    }

    public function update(Post $post) {
        $this->authorize('update', [Post::class, $post]);
        $attr = request()->validate([
            'content' => 'required'
        ]);
        $post->update($attr);
        return [
            'post' => $post
        ];
    }

    public function destroy(Post $post) {
        $this->authorize('delete', [Post::class, $post]);
        $post->delete();
        return [
            'msg' => 'Post got deleted'
        ];
    }

    public function show(Post $post) {
        $this->authorize('view', [Post::class, $post]);
        return [
            'post' => $post
        ];
    }

    public function index() {
        $followIds = request()->user()->following()->pluck('profiles.user_id');
        $posts = Post::whereIn('user_id', $followIds)->paginate();
        return [
            'posts' => $posts
        ];
    }
}
