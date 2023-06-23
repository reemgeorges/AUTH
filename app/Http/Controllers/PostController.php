<?php

namespace App\Http\Controllers;

use App\Models\BlacklistedWord;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // if ($request->has('trashed')) {
        //     $posts = Post::onlyTrashed()->get();
        // } else {
        //     // $posts =Post::all();
        //     $posts = Post::with('comments')->get();


        // }
        if ($request->has('trashed')) {
            $posts = Post::onlyTrashed()->get();
        } else {
            $posts = Post::with('comments')->get();

            $blacklistedWords = BlacklistedWord::pluck('word')->toArray();//استرجاع فقط الكلمات وتحويلها لمصفوفة

            foreach ($posts as $post) {
                $postContent = $post->content;
                $author = $post->user;
                $wordCount = 0;

                foreach ($blacklistedWords as $blacklistedWord) {
                    // if (strpos($postContent, $blacklistedWord) !== false) {
                    //     $postContent = str_replace($blacklistedWord, '***', $postContent);
                    //     $wordCount++;
                    // }
                    foreach ($blacklistedWords as $blacklistedWord) {
                        $pattern = '/\b' . preg_quote($blacklistedWord, '/') . '\b/';
                        $replacement = '***';
                        $postContent = preg_replace($pattern, $replacement, $postContent, -1, $wordCount);
                    }
                }

                $post->content = $postContent;
                $post->published_date = \Carbon\Carbon::parse($post->created_at)->diffForHumans(); // تنسيق التاريخ بشكل "منذ X وقت"
            }
        }

        return view('posts.index', compact('posts'));

    }
    public function userPosts(Request $request)
    {
        if ($request->has('trashed')) {
            $posts = Post::onlyTrashed()
                ->where('user_id', '=', Auth::id())->get();
        } else {
            // $posts = Auth::user()->posts;
            $posts = Post::with('comments')->where('user_id', Auth::id())->get();

        }

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $postContent = $request->input('content');
        $blacklistedWords = BlacklistedWord::pluck('word')->toArray();
        $wordCount = 0;

        foreach ($blacklistedWords as $word) {
            $wordCount += substr_count(strtolower($postContent), strtolower($word));
        }

        $user = User::find(Auth::user()->id);

        if ($wordCount > 5 && !$user->roles()->where('role_id', '=', 1)->first()) {
            $postContent = '*** This user has exceeded the allowed number of blacklisted words.';

            // تحديث دور المستخدم إلى الدور الزائر
            $visitorRole = Role::where('role_name', 'visitor')->first();
            $user->roles()->sync([$visitorRole->id]);

            // إنشاء المنشور بدون تعيين المستخدم
            // $post = new Post();
            // $post->content = $postContent;
            // $post->save();
        } else {
            // قم بحفظ المنشور باستخدام المستخدم الحالي
            $post = new Post($request->all());
            $post->user_id = Auth::id();
            $post->save();
        }

        return redirect('/posts');
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd('hi');
        $post = Post::find($id);
        $input = $request->all();
        $post->update($input);
        $post->save();

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post=Post::find($id);
        if($post->comments()) {
            $post->comments()->delete();
        }
        $post->delete();

        return redirect('/');
    }
    public function restore($id)
    {
        Post::withTrashed()->find($id)->restore();
        // dd( Post::withTrashed()->count());
        return redirect()->back();
    }

    /**
     * restore all post
     *
     * @return response()
     */
    public function restoreAll()
    {
        Post::onlyTrashed()->restore();
        return redirect()->route('/');
    }
}
