<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Social;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use PhpParser\Node\Stmt\TryCatch;

class SocialController extends Controller
{
    public function redirect($provider){
        return Socialite::driver($provider)->redirect();

    }

    public function callback($provider){

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Throwable $th) {

            return redirect(route('login'));

        }

        // Kullanıcının e-posta adresiyle veritabanında eşleşen bir kullanıcı var mı kontrol et
        $user = User::where('email', $socialUser->getEmail())->first();

        //kullanıcının (nickname) varsa bu değeri al, eğer nickname yoksa (name) al.
        $name = $socialUser->getNickname() ?? $socialUser->getName();

            //Veritabanında eşleşen bir kullanıcı yoksa (Kullanıcıyı oluştur) (sosyal medya bilgilerini oluştur)
            if (!$user) {
                // Kullanıcıyı oluştur
                $user = User::create([
                    'name' => $name,
                    'email' => $socialUser->getEmail(),
                    'password' => Hash::make(Str::random(7)),
                ]);
                // Yeni kullanıcıya ait sosyal medya bilgileri oluşturuluyor
                $user->socials()->create([
                    'provider_id' => $socialUser->getId(),
                    'provider' => $provider,
                    'provider_token' => $socialUser->token,
                    'provider_refresh_token' => $socialUser->refreshToken
                ]);
            }


             // Kullanıcının bu $provider'a ait sosyal medya hesap bilgilerini kontrol et
              $socials = Social::where('provider', $provider)->where('user_id', $user->id)->first();

            //Kullanıcının bu providere ait sosyal medyası yoksa bu providerida  kaydediyoruz
           //Yani google ile giriş yapmış olabilir ama  github ilede  giriş yapmak isteyebilir
             if (!$socials) {
            // sosyal medya hesabı ekle
            $user->socials()->create([
                'provider_id' => $socialUser->getId(),
                'provider' => $provider,
                'provider_token' => $socialUser->token,
                'provider_refresh_token' => $socialUser->refreshToken
            ]);
             }

        // kullanıcıyı login yap
        auth()->login($user);

        //Dashboard'a yönlendir
        return redirect('/dashboard');

    }
}
