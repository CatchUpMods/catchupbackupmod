<?php namespace WebEd\Plugins\Backup\Http\Middleware;

use \Closure;

class BootstrapModuleMiddleware
{
    public function __construct()
    {

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * Register to dashboard menu
         */
        dashboard_menu()->registerItem([
            'id' => 'webed-backup',
            'priority' => 100,
            'parent_id' => 'webed-configuration',
            'heading' => null,
            'title' => trans('webed-backup::base.menu_title'),
            'font_icon' => 'fa fa-circle-o',
            'link' => route('admin::webed-backup.index.get'),
            'css_class' => null,
            'permissions' => ['view-backups'],
        ]);

        return $next($request);
    }
}
