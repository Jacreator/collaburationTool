<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class PostController
 * 
 * @category PostController_Class
 * @package  App\Http\Controllers
 * @author   James Adakole <jambone.james82@gmail.com>

 * @license MIT <https://opensource.org/licenses/MIT>
 */
 
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::query()->get();
        
        return new JsonResponse(
            [
                'message' => 'Posts retrieved successfully',
                'code' => Response::HTTP_FOUND,
                'data' => $posts,
            ], Response::HTTP_FOUND
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StorePostRequest $request 
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $post = Post::query()->create($request->validated());
        
        return new JsonResponse(
            [
                'message' => 'Post created successfully',
                'code' => Response::HTTP_CREATED,
                'data' => $post,
            ], Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Post $post 
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return new JsonResponse(
            [
                'message' => 'Post retrieved successfully',
                'code' => Response::HTTP_FOUND,
                'data' => $post,
            ], Response::HTTP_FOUND
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdatePostRequest $request 
     * @param \App\Models\Post                     $post 
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $post = $post->query()->update($request->validated());

        return new JsonResponse(
            [
                'message' => 'Post updated successfully',
                'code' => Response::HTTP_OK,
                'data' => $post,
            ], Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Post $post 
     * 
     * @return \Illuminate\Http\JsonResponse 
     */
    public function destroy(Post $post)
    {
        $post->query()->delete();

        return new JsonResponse(
            [
                'message' => 'Post deleted successfully',
                'code' => Response::HTTP_NO_CONTENT,
                'data' => $post,
            ], Response::HTTP_NO_CONTENT
        );
    }
}
