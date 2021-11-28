<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterForm;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\InvalidCastException;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use LogicException;
use Exception;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponser;

    public function unauthenticated(Request $request)
    {
        return $this->error(null, 'Login Required', 400);
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function register(RegisterForm $request)
    {
        $inputs = $request->input();

        dd($inputs);
        $user = User::create([
            'name' => $inputs['name'],
            'password' => bcrypt($inputs['password']),
            'email' => $inputs['email']
        ]);

        return $this->success([
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws Exception
     * @throws InvalidCastException
     * @throws LogicException
     */
    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ]);

        if (!Auth::attempt($attr)) {
            return $this->error(null, 'Credentials not match', 401);
        }

        /** @var \App\Models\User $user **/
        $user = Auth::user();

        return $this->success([
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    /**
     *
     * @return Redirector|RedirectResponse
     * @throws InvalidArgumentException
     * @throws BindingResolutionException
     */
    public function logout()
    {
        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $user->tokens()->delete();

        return $this->success(null, 'logout succeed');
    }
}
