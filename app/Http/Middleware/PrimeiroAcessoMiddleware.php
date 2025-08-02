<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Admin;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrimeiroAcessoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd(auth()->guard('web')->check());
        if(auth()->guard('admin')->check()){
            $admin = Admin::where('email', auth()->guard('admin')->user()->email)->firstOrFail();

            if (is_null($admin->Empresa)) {
                return redirect()->route('empresa.create'); // se não completou o cadastro da empresa, vai completar
            } else {
                return $next($request); // se já completou o cadastro OU é admin, blz, pode passar
            }
        } elseif(auth()->guard('web')->check()){

            return abort(403, 'em desenvolvimento');
        }

        return abort(403, 'nao passou no primeiro acesso midd');
    }
}
