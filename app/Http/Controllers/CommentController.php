<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Repositories\CommentRepository;
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

        return CommentResource::collection($comments)->additional(
            [
                'message' => 'Comments retrieved successfully',
                'code' => Response::HTTP_FOUND,
            ], Response::HTTP_FOUND
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCommentRequest $request           - request object
     * @param CommentRepository   $commentRepository - repository for comment
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(
        StoreCommentRequest $request,
        CommentRepository $commentRepository
    ) {
        $comment = $commentRepository->create($request->validated());

        return (new CommentResource($comment))->additional(
            [
                'message' => "Comment created successfully",
                'statusCode' => Response::HTTP_CREATED
            ],
            Response::HTTP_CREATED
        );
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

        return (new CommentResource($comment))->additional(
            [
                'message' => "Comment retrieved successfully",
                'statusCode' => Response::HTTP_FOUND
            ],
            Response::HTTP_FOUND
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCommentRequest $request           - request object
     * @param Comment              $comment           - comment object
     * @param CommentRepository    $commentRepository - repository for comment 
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdateCommentRequest $request,
        Comment $comment,
        CommentRepository $commentRepository
    ) {

        return (new CommentResource(
            $commentRepository->update($comment, $request->validated())
        ))->additional(
            [
                'message' => 'Comment updated successfully',
                'code' => Response::HTTP_OK,
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Comment           $comment           - comment object
     * @param CommentRepository $commentRepository - repository for comment
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment, CommentRepository $commentRepository)
    {

        return (new CommentResource(
            $commentRepository->delete($comment)
        ))->additional(
            [
                'message' => 'Comment deleted successfully',
                'code' => Response::HTTP_NO_CONTENT,
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
