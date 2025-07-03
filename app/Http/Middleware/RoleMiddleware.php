<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;

    class RoleMiddleware
    {
        public function handle(Request $request, Closure $next, $role)
        {
            $user = $request->user();

            Log::info('Role Middleware Check', [
                'user' => $user ? $user->toArray() : 'No user',
                'required_role' => $role,
            ]);

            if (!$user) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            if ($user->role !== $role) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            return $next($request);
        }
    }
