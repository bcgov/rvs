<?php

namespace Modules\Twp\Http\Middleware;

use Override;
use Modules\Twp\Entities\Util;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Cache;

class HandleInertiaRequests extends Middleware {

    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    #[Override]
    public function version(Request $request): ?string {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     * @param Request $request
     * @return array<string, mixed>
     * @see https://inertiajs.com/shared-data
     */
    #[Override]
    public function share(Request $request): array {
        $sortedUtils = Cache::remember('sorted_utils', 180, fn() => Util::getSortedUtils());

        return array_merge(parent::share($request), [
            'utils' => $sortedUtils,
            'flash' => [
                'message' => fn () => $request->session()->get('message')
            ],
        ]);
    }

}
