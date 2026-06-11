<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template loaded on the first page visit.
     * Admin pages use this. Landing pages override it (see landing controllers).
     */
    protected $rootView = 'app';

    /**
     * Asset version (cache-busts Inertia responses when assets change).
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Props shared with every Inertia page.
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $user ? [
                    'id'        => $user->id,
                    'uuid'      => $user->uuid,
                    'name'      => $user->name,
                    'email'     => $user->email,
                    'phone'     => $user->phone,
                    'role'      => $user->role,
                    'roleLabel' => $user->getRoleLabel(),
                    'roleBadge' => $user->getRoleBadgeClass(),
                    'isAdmin'   => $user->isAdmin(),
                    'isManager' => $user->isManager(),
                    'canManage' => $user->canManage(),
                ] : null,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info'    => fn () => $request->session()->get('info'),
                'status'  => fn () => $request->session()->get('status'),
            ],
        ]);
    }
}
