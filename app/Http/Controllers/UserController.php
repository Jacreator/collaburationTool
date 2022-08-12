<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use App\Http\Requests\UpdateUserRequest;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $pageSize = request()->has('page_size') ? request()->get('page_size') : 10;
        $users = User::query()->paginate($pageSize);


        return UserResource::collection($users)->additional(
            [
                'message' => 'Users retrieved successfully',
                'code' => Response::HTTP_FOUND,
            ], Response::HTTP_FOUND
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request        - request object
     * @param UserRepository           $userRepository -
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request, UserRepository $userRepository)
    {
        return (new UserResource(
            $userRepository->create($request->validated())
        ))->additional(
            [
                'message' => 'User created successfully',
                'code' => Response::HTTP_CREATED,
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param User $user - user object
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {

        return (new UserResource($user))->additional(
            [
                'message' => 'User retrieved successfully',
                'code' => Response::HTTP_FOUND,
            ],
            Response::HTTP_FOUND
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request        - request object
     * @param User                     $user           - user object
     * @param UserRepository           $userRepository -
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdateUserRequest $request,
        User $user,
        UserRepository $userRepository
    ) {

        return (new UserResource(
            $userRepository->update($user, $request->validated())
        ))->additional(
            [
                'message' => 'User updated successfully',
                'code' => Response::HTTP_ACCEPTED,
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User           $user           - user object
     * @param UserRepository $userRepository -
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user, UserRepository $userRepository)
    {
        return (new UserResource($userRepository->delete($user)))->additional(
            [
                'message' => 'User deleted successfully',
                'code' => Response::HTTP_NO_CONTENT,
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
