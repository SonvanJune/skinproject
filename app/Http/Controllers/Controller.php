<?php

namespace App\Http\Controllers;

use App\DTOs\GetUserDTO;
use App\Models\User;
use App\Services\OrderService;
use App\Services\RoleService;
use App\Services\Service;
use App\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function checkTokenWhenReload($request, UserService $userService, bool $getUserModel = false, bool $deleteOrder = true): GetUserDTO|User|null|int
    {
        $token = $request->session()->get(UserService::ACCESS_TOKEN_KEY_NAME);
        if (!!$token) {
            $user = $userService->getUserInformationByToken($request, $token);
            if ($this->checkIsString($user)) {
                $new_token = $userService->refreshToken($request, $token);
                if ($new_token == false) {
                    return -1;
                } else {
                    $token = $request->session()->get(UserService::ACCESS_TOKEN_KEY_NAME);
                    $user = $userService->getUserInformationByToken($request, $token);
                }
            }
            if ($getUserModel == true) {
                $user = $userService->getUserByToken($request, $token);
            } else {
                $user = $userService->getUserInformationByToken($request, $token);
            }
            $request->merge(['user_id' => $user->user_id]);
            if ($user && $deleteOrder) {
                OrderService::deleteOrderByUser($request);
            }
            $request->request->remove('user_id');
            return $user;
        } else {
            return null;
        }
    }

    public function checkMaintenance($user = null)
    {
        if ($user != null) {
            if ($user instanceof User) {
                $roles = $user->roles->pluck('role_name')->toArray();
            } elseif ($user instanceof GetUserDTO) {
                $roles = $user->roles;
            }
            if (in_array(RoleService::ADMIN_ROLE, $roles) || in_array(RoleService::SUB_ADMIN_ROLE, $roles)) {
                return "on";
            }
        }
        $status = (new Service)->getStatusMaintenance();
        return $status;
    }

    public function checkUserInPage($roles)
    {
        if (!in_array(RoleService::USER_ROLE, $roles)) {
            return false;
        } else {
            return true;
        }
    }

    public function checkAdminInPage($user)
    {
        if ($user) {
            if (!in_array(RoleService::ADMIN_ROLE, $user->roles) && !in_array(RoleService::SUB_ADMIN_ROLE, $user->roles)) {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    public function getPositionLogo($categories)
    {
        if (!$categories) {
            return 0;
        }
        $count = 0;
        $position_logo = 0;

        foreach ($categories as $category) {
            $count += 1;
        }

        if ($count % 2 == 0) {
            $position_logo = round($count / 2);
        } else {
            $position_logo = round($count / 2 - 1);
        }

        return $position_logo;
    }

    public function checkCategorySlug($categories, $slug)
    {
        if (!$categories) {
            abort(404);
        }

        if ($this->checkIsString($categories)) {
            abort(404);
        }

        $slug_not_found = true;
        foreach ($categories as $category) {
            if ($category->slug == $slug) {
                $slug_not_found = false;
                break;
            }
            if (!empty($category->children)) {
                $slug_not_found = $this->checkCategorySlug($category->children, $slug);
                if ($slug_not_found == false) {
                    break;
                }
            }
        }
        return $slug_not_found;
    }

    public function checkIsString($list)
    {
        return is_string($list);
    }

    public function setPasswordRequest($request)
    {
        return [
            [
                'question_id' => $request->securityQuestion1,
                'user_answer' => $request->securityAnswer1
            ],
            [
                'question_id' => $request->securityQuestion2,
                'user_answer' => $request->securityAnswer2
            ],
            [
                'question_id' => $request->securityQuestion3,
                'user_answer' => $request->securityAnswer3
            ]
        ];
    }
}
