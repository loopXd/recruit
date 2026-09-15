<?php

namespace App\Http\Controllers\App\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\Auth\User\UserRegisterRequest;
use App\Models\App\Applicant\Applicant;
use App\Models\Core\Auth\Profile;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\User;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Support\Facades\Hash;


class UserRegisterController extends Controller
{


    public function signUpView()
    {
        return view('frontend.user.sign_up');
    }

    public function register(UserRegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::query()->updateOrCreate(
            ['email' => $request->email],
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status_id' => resolve(StatusRepository::class)->userActive(),
            ]
        );

        $role = Role::query()->where('name', 'Candidate')->first();
        $user->assignRole($role);

        Applicant::query()->create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'user_id' => $user->id,
        ]);

        Profile::query()->create([
            'user_id' => $user->id,
            'apply_form_setting' => null,
            'gender' => 'other'
        ]);

        // 🔐 Login automático
        auth()->login($user);

        // 🔁 Resposta única e limpa
        return response()->json([
            'message' => 'Cadastro realizado com sucesso',
            'user' => [
                'id' => $user->id,
                'name' => $user->full_name ?? "{$user->first_name} {$user->last_name}",
                'email' => $user->email,
            ]
        ]);
    }
}
