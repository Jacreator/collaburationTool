<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PostResource;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Repositories\PostRepository;
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

        return PostResource::collection($posts)->additional(
            [
                'message' => 'Posts retrieved successfully',
                'code' => Response::HTTP_FOUND,
            ],
            Response::HTTP_FOUND
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StorePostRequest $request 
     * @param PostRepository                      $postRepository 
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request, PostRepository $postRepository)
    {

        return (new PostResource(
            $postRepository->create($request->validated())
        ))
            ->additional(
                [
                    'message' => 'Post created successfully',
                    'code' => Response::HTTP_CREATED,
                ],
                Response::HTTP_CREATED
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

        return (new PostResource($post))->additional(
            [
                'message' => 'Post retrieved successfully',
                'code' => Response::HTTP_FOUND,
            ],
            Response::HTTP_FOUND
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdatePostRequest $request 
     * @param \App\Models\Post                     $post 
     * @param PostRepository                       $postRepository 
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdatePostRequest $request,
        Post $post,
        PostRepository $postRepository
    ) {

        return (new PostResource(
            $postRepository->update(
                $post,
                $request->validate()
            )
        ))->additional(
            [
                'message' => 'Post updated successfully',
                'code' => Response::HTTP_OK,
            ],
            Response::HTTP_OK,
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Post $post 
     * @param PostRepository   $postRepository - repository for post
     * 
     * @return \Illuminate\Http\JsonResponse 
     */
    public function destroy(Post $post, PostRepository $postRepository)
    {

        return (new PostResource($postRepository->delete($post)))->additional(
            [
                'message' => 'Comment deleted successfully',
                'code' => Response::HTTP_NO_CONTENT,
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
