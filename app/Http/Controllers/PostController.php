<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PostResource;
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
        $pageSize = request()->has('page_size') ? request()->get('page_size') : 10;

        $posts = Post::query()->paginate($pageSize);

        // return new JsonResponse(
        //     [
        //         'message' => 'Posts retrieved successfully',
        //         'code' => Response::HTTP_FOUND,
        //         'data' => $posts,
        //     ],
        //     Response::HTTP_FOUND
        // );

        return PostResource::collection($posts);
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

        $postCreated = DB::transaction(
            function () use ($request) {
                $post = Post::query()->create($request->validated());

                $post->users()->sync($request->user_ids);

                return $post;
            }
        );
        // return new JsonResponse(
        //     [
        //         'message' => 'Post created successfully',
        //         'code' => Response::HTTP_CREATED,
        //         'data' => $postCreated,
        //     ],
        //     Response::HTTP_CREATED
        // );

        return new PostResource($postCreated);
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
        // return new JsonResponse(
        //     [
        //         'message' => 'Post retrieved successfully',
        //         'code' => Response::HTTP_FOUND,
        //         'data' => $post,
        //     ],
        //     Response::HTTP_FOUND
        // );

        return new PostResource($post);
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

        // return new JsonResponse(
        //     [
        //         'message' => 'Post updated successfully',
        //         'code' => Response::HTTP_OK,
        //         'data' => $post,
        //     ],
        //     Response::HTTP_OK
        // );

        return new PostResource($post);
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

        // return new JsonResponse(
        //     [
        //         'message' => 'Post deleted successfully',
        //         'code' => Response::HTTP_NO_CONTENT,
        //         'data' => $post,
        //     ],
        //     Response::HTTP_NO_CONTENT
        // );

        return new PostResource($post);
    }
}
