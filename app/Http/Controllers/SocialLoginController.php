<?php

namespace App\Http\Controllers;

use App\Models\LinkedSocialAccount;
use App\Models\User;
use App\Models\UserDetails;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as ProviderUser;

class SocialLoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            $accessToken = $request->get('access_token');
            $provider = $request->get('provider');
            $providerUser = Socialite::driver($provider)->userFromToken($accessToken);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ]);
        }

        if (filled($providerUser)) {
            $user = $this->findOrCreate($providerUser, $provider);
        } else {
            $user = $providerUser;
        }

        Auth::login($user);

        // Verifica se o user_details já existe para o usuário
        $userDetails = UserDetails::where('user_id', $user->id)->first();
        
        if (!$userDetails) {
            // Cria os detalhes do usuário apenas se ainda não existirem
            UserDetails::create([
                'user_id' => $user->id,
                'status' => 'active',
            ]);
        }

        if (auth()->check()) {
            return response()->json([
                'message' => 'Logged in successfully',
                'data' => ['token' => auth()->user()->createToken('API Token')->plainTextToken],
            ]);
        } else {
            return response()->json([
                'message' => 'Failed to Login, try again',
            ], 401);
        }
    }

    protected function findOrCreate(ProviderUser $providerUser, string $provider): User
    {
        $linkedSocialAccount = LinkedSocialAccount::query()
            ->where('provider_name', $provider)
            ->where('provider_id', $providerUser->getId())
            ->first();

        if ($linkedSocialAccount) {
            // Se a conta social já está vinculada, retorna o usuário
            return $linkedSocialAccount->user;
        } else {
            $user = null;

            // Verifica se há um usuário com o mesmo e-mail
            if ($email = $providerUser->getEmail()) {
                $user = User::query()->where('email', $email)->first();
            }

            if (! $user) {
                // Cria o usuário se ele não existir
                $user = User::query()->create([
                    'name' => $providerUser->getName(),
                    'email' => $providerUser->getEmail(),
                    'type' => 'user'
                ]);
            }

            // Vincula a conta social ao usuário
            $user->linkedSocialAccounts()->create([
                'provider_id' => $providerUser->getId(),
                'provider_name' => $provider,
            ]);

            return $user;
        }
    }
}
