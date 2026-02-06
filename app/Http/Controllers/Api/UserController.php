<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $users = User::with('emails')->get();

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request): UserResource
    {
        $user = DB::transaction(function () use ($request) {
            $user = User::create($request->only([
                'first_name',
                'last_name',
                'phone_number'
            ]));

            foreach ($request->emails as $email) {
                $user->emails()->create(['email' => $email]);
            }

            return $user->load('emails');
        });

        return new UserResource($user);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user->load('emails'));
    }

    public function update(UpdateUserRequest $request, User $user): UserResource
    {
        DB::transaction(function () use ($request, $user) {
            $user->update($request->only([
                'first_name',
                'last_name',
                'phone_number'
            ]));

            if ($request->has('emails')) {
                $user->emails()->delete();
                foreach ($request->emails as $email) {
                    $user->emails()->create(['email' => $email]);
                }
            }
        });

        return new UserResource($user->load('emails'));
    }

    public function destroy(User $user): Response
    {
        $user->delete();

        return response()->noContent();
    }
}
