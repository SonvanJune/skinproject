<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\PostService;
use App\Services\UserService;
use Illuminate\Http\Request;

class AdminPostController extends Controller
{
    protected $postService;
    protected $userService;
    public function __construct(PostService $postService, UserService $userService)
    {
        $this->postService = $postService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);

        $page = $request->query('page');
        $per_page = $request->query('per_page');

        if (!$page || !is_numeric($page) || $page < 1) {
            $page = 1;
        }

        if (!$per_page || !is_numeric($per_page) || $per_page < 1) {
            $per_page = UserService::PER_PAGE;
        }

        $request->merge(["page" => $page, "per_page" => $per_page]);

        $paginatedDTO = $this->postService->getAllPostsPerPage($request, PostService::TYPE_ADMIN, $this->userService);
        return view(
            'admin.posts.index',
            compact('paginatedDTO')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        return view(
            'admin.posts.create',
            compact('user')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $createdPostDTO = $this->postService->createPost($request, $user->user_id);

        if (parent::checkIsString($createdPostDTO)) {
            return back()->with('error', $createdPostDTO);
        }

        return redirect()->route('admin.posts')->with('success', 'Post created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, string $slug)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);

        $request->merge(['post_slug' => $slug]);
        $postDTO = $this->postService->getRawPostBySlug($request, $this->userService);
        if(parent::checkIsString($postDTO)){
            abort(404);
        }

        return view(
            'admin.posts.edit',
            compact('postDTO', 'user')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);

        $updatedPostDTO = $this->postService->updatePost($request, $user->user_id);

        if (parent::checkIsString($updatedPostDTO)) {
            return back()->with('error', $updatedPostDTO);
        }

        return redirect()->route('admin.posts')->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $slug)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);

        $post = Post::where('post_slug', $slug)->first();

        if (!$post) {
            return back()->with('error', 'Post not found');
        }

        $request->merge(['post_id' => $post->post_id]);
        $deletedPostDTO = $this->postService->deletePost($request, $this->userService);

        if (is_string($deletedPostDTO)) {
            return back()->with('error', $deletedPostDTO);
        }

        if ($deletedPostDTO) {
            return redirect()->route('admin.posts')->with('success', 'Post deleted successfully!');
        } else {
            return back()->with('error', 'Failed to delete post');
        }
    }
}
