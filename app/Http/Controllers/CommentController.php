<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $pageSize = request()->has('page_size') ? request()->get('page_size') : 10;

        $comments = Comment::query()->paginate($pageSize);

        // return new JsonResponse(
        //     [
        //         'message' => 'Comments retrieved successfully',
        //         'code' => Response::HTTP_FOUND,
        //         'data' => $comments,
        //     ], Response::HTTP_FOUND
        // );

        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreCommentRequest $request - request object
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCommentRequest $request)
    {
        $comment = Comment::query()->create($request->validated());
        
        // return new JsonResponse(
        //     [
        //         'message' => 'Comment created successfully',
        //         'code' => Response::HTTP_CREATED,
        //         'data' => $comment,
        //     ], Response::HTTP_CREATED
        // );

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Comment $comment - comment object
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Comment $comment)
    {
        // return new JsonResponse(
        //     [
        //         'message' => 'Comment retrieved successfully',
        //         'code' => Response::HTTP_FOUND,
        //         'data' => $comment,
        //     ], Response::HTTP_FOUND
        // );

        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateCommentRequest $request - request object
     * @param \App\Models\Comment                     $comment - comment object
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->query()->update($request->validated());
        
        // return new JsonResponse(
        //     [
        //         'message' => 'Comment updated successfully',
        //         'code' => Response::HTTP_OK,
        //         'data' => $comment,
        //     ], Response::HTTP_OK
        // );

        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Comment $comment - comment object
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $comment->query()->delete();
        
        // return new JsonResponse(
        //     [
        //         'message' => 'Comment deleted successfully',
        //         'code' => Response::HTTP_NO_CONTENT,
        //         'data' => $comment,
        //     ], Response::HTTP_NO_CONTENT
        // );
        return new CommentResource($comment);
    }
}
