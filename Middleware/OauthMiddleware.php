<?php

namespace Tbcconnect\Middleware;

use Closure;
use Tbcconnect\MsConnector;


class OauthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle( $request, Closure $next )
    {
    	print_r($request);
    	exit;
        $externalApiKey = $request->header('x-api-key');
        if (empty($externalApiKey)) {
            return 'Api Key Not Found';
        }


        $headers = [
            env('ext_key')   => $externalApiKey,
            'token'          => $request->header('token'),
            'remember-token' => $request->header('remember-token'),
            'product'        => $request->header('product'),
            'api'            => env('API_NAME'),
            'method'         => $request->method()
        ];

        if (!empty($adminData)) {
            $headers['admin_data'] = $adminData;
            $headers['endpoint']   = $request->fullUrl();
        }

        $data = [
            'route'   => 'checkToken',
            'app'     => 'ACCOUNTS',
            'headers' => $headers,
            'method'  => 'GET',
        ];

        $checkAuth = Helpers::callOtherApi($data);
        $checkAuth = json_decode($checkAuth['body'], true);


        if ($checkAuth['status'] == 200) {

            //Check if user has its user group, we must check for routes.
            if (isset($checkAuth['data']['user_group_id']) AND $checkAuth['data']['user_group_id']) {
                $availableRoutes = json_decode($checkAuth['data']['user_available_routes'], true);

                //If `user_available_routes` is valid json array, we must check if current route is written
                //in this users available routes.
                if (is_array($availableRoutes)) {

                    //We may have path parameters in uri (user/{id}) and we must check if this route contains any of
                    //available routes.
                    $hasAccess = false;
                    foreach ($availableRoutes as $availableRoute) {
                        if (strpos($request->path() . '/', $availableRoute) > -1) {
                            $hasAccess = true;
                            break;
                        }
                    }

                    //If this route is not available for user, return with error message.
                    if (!$hasAccess) {
                        return responder()->error(401, 'You have no access to this module.')->respond(401);
                    }
                }
            }

            $request->request->add(['userData' => $checkAuth['data']]);
            return $next($request);
        }

        return $checkAuth;
    }

}