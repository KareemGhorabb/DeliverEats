<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Supported OAuth providers.
     */
    private const SUPPORTED_PROVIDERS = ['google', 'github'];

    /**
     * Redirect the user to the OAuth provider's authentication page.
     *
     * @param  string  $provider  'google' or 'github'
     */
    public function redirectToProvider(string $provider): RedirectResponse
    {
        $this->validateProvider($provider);

        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Handle the OAuth provider callback.
     * Finds or creates the user, then issues a Sanctum token.
     *
     * @param  string  $provider  'google' or 'github'
     */
    public function handleProviderCallback(string $provider): JsonResponse
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'OAuth authentication failed. Please try again.',
                'error'   => $e->getMessage(),
            ], 422);
        }

        // Find existing user by provider ID or email
        $user = User::where('provider', $provider)
                    ->where('provider_id', $socialUser->getId())
                    ->first();

        if (! $user) {
            // Try to link to an existing account by email
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                // Link the OAuth provider to the existing account
                $user->update([
                    'provider'    => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar'      => $user->avatar ?? $socialUser->getAvatar(),
                ]);
            } else {
                // Create a brand-new user
                $user = User::create([
                    'name'        => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                    'email'       => $socialUser->getEmail(),
                    'avatar'      => $socialUser->getAvatar(),
                    'provider'    => $provider,
                    'provider_id' => $socialUser->getId(),
                    'password'    => null,   // No password for social-only accounts
                    'role'        => 'customer', // Default role for OAuth sign-ups
                    'is_active'   => true,
                ]);
            }
        }

        // Revoke old tokens for this provider to keep sessions clean
        $user->tokens()->where('name', "oauth_{$provider}")->delete();

        // Issue a fresh Sanctum token
        $token = $user->createToken("oauth_{$provider}")->plainTextToken;

        return response()->json([
            'success'  => true,
            'message'  => 'Authenticated via ' . ucfirst($provider),
            'provider' => $provider,
            'user'     => $user,
            'token'    => $token,
        ]);
    }

    /**
     * Abort with a 422 if the provider is not supported.
     */
    private function validateProvider(string $provider): void
    {
        if (! in_array($provider, self::SUPPORTED_PROVIDERS, true)) {
            abort(422, "Unsupported OAuth provider: [{$provider}]. Supported: " . implode(', ', self::SUPPORTED_PROVIDERS));
        }
    }
}
