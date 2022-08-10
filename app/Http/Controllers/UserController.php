<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::query()->get();
        
        return new JsonResponse(
            [
                'message' => 'Users retrieved successfully',
                'code' => Response::HTTP_FOUND,
                'data' => $users,
            ], Response::HTTP_FOUND
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request - request object
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request)
    {
        $user = User::query()->create($request->validated());
        
        return new JsonResponse(
            [
                'message' => 'User created successfully',
                'code' => Response::HTTP_CREATED,
                'data' => $user,
            ], Response::HTTP_CREATED
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
        return new JsonResponse(
            [
                'message' => 'User retrieved successfully',
                'code' => Response::HTTP_FOUND,
                'data' => $user,
            ], Response::HTTP_FOUND
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request - request object
     * @param User                     $user    - user object
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $user)
    {
        $user->update($request->validated());
        
        return new JsonResponse(
            [
                'message' => 'User updated successfully',
                'code' => Response::HTTP_ACCEPTED,
                'data' => $user,
            ], Response::HTTP_ACCEPTED
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user - user object
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $user->delete();
        
        return new JsonResponse(
            [
                'message' => 'User deleted successfully',
                'code' => Response::HTTP_NO_CONTENT,
                'data' => $user,
            ], Response::HTTP_NO_CONTENT
        );
    }
}
