<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\User;
use App\Http\Resources\UserResource;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        return UserResource::collection($this->userRepository->getAll());
    }

    public function show(Request $request, $id)
    {
        return new UserResource($this->userRepository->find($id));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $user = $this->userRepository->create($data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $user = $this->userRepository->update($id, $data);
    }

    public function destroy($id)
    {
        $this->userRepository->delete($id);
    }
}
