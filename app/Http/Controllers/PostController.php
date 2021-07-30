<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    public function index()
    {
        // Add all current query string values to the paginator, so that we have pagination with category
        return view('posts.index', [
            'posts' => Post::latest()->filter(
                        request(['search', 'category', 'author'])
                    )->paginate(18)->withQueryString()
        ]);
    }

    public function show(Post $post)
    {
        return view('posts.show', [
            'post' => $post
        ]);
    }

    public function create()
    {
        if (auth()->guest()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        // if (auth()->user()?->username !== 'JeffreyWay') {
        //     abort(Response::HTTP_FORBIDDEN);
        // }
        return view('posts.create');
    }
}
