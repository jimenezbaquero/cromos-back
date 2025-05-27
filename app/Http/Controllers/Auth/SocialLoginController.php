<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialLoginController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    
    public function callback($provider)
    {
        DB::beginTransaction();
        
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
            
            $user = User::where('provider', $provider)
                ->where('provider_id', $socialUser->getId())
                ->first();
            
            if (!$user) {
                $user = User::where('email', $socialUser->getEmail())->first();
                
                if ($user){
                    if(!$user->hasRole('admin')){
                        $user->update([
                            'provider' => $provider,
                            'provider_id' => $socialUser->getId(),
                        ]);
                    }else{
                        throw new \Exception('Prueba a usar autenticación manual');
                    }
                } else {
                    $user = User::create([
                        'name' => $socialUser->getName() ?? 'Sin Nombre',
                        'email' => $socialUser->getEmail(),
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'password' => bcrypt(Str::random(16)),
                    ]);
                    
                    $user->assignRole('client');
                }
            }
            
            Auth::login($user, true);
            
            DB::commit();
            
            return redirect()->route('dashboard');
            
        } catch (\Throwable $e) {
            DB::rollBack();
            
            Log::error("Error en autenticación social con $provider: " . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'provider' => $provider,
            ]);
            
            return redirect()->route('login')->withErrors(['error' => 'No se pudo completar el inicio de sesión. Inténtalo de nuevo.']);
        }
    }
}
