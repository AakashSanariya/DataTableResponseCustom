<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use DB;

class UserController extends Controller
{
    public function index(Request $request){

        $users = User::select([DB::raw("CONCAT(first_name,' ',last_name)  AS fullname"),
            'id', 'first_name', 'last_name', 'email', 'gender', 'date_of_birth',
            'phone_number', 'profile_image', 'status', 'created_at', 'updated_at',
            'username'])
            ->where(['role' => 'USER'])
        ;

        if ($request->has('email') && !empty($request->get('email'))) {
            $users->where('email', 'like', "%{$request->get('email')}%");
        }
        if ($request->has('status') && !empty($request->get('status'))) {
            $users->where('status', $request->get('status'));
        }

        $usersResult = $this->searchFilter($users, $request);
        // Using the Engine Factory


        $page_number = $request->get('page_number');
        $sort_param = 'fullname';
        $sort_param_type = 'asc';
        $page_limit = $request->get('limit');

        if($request->has('sort_param') && !empty($request->get('sort_param'))){
            $sort_param = $request->get('sort_param');
        }
        if($request->has('sort_type') && !empty($request->get('sort_type'))){
            $sort_param_type = $request->get('sort_type');
        }

        Paginator::currentPageResolver(function () use ($page_number) {
            return $page_number;
        });

        $list = $usersResult->orderBy($sort_param, $sort_param_type)->paginate($page_limit);

        $dataResponse = [
            "headers" => [],
            "original" => [
                "data" => $list->items(),
                "draw" => $list->currentPage(),
                "recordFiltered" => $list->total(),
                "recordsTotal" => $list->total(),
            ]
        ];
        return $this->success($dataResponse);

    }

    public function searchFilter($users, $request)
    {
        if ($request->has('name') && !empty($request->get('name'))) {
            $users->where(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', "%{$request->get('name')}%");
        }

        if ($request->has('gender') && !empty($request->get('gender'))) {
            $users->where('gender', $request->get('gender'));
        }

        if ($request->has('phone_number') && !empty($request->get('phone_number'))) {
            $users->where('phone_number', 'like', "%{$request->get('phone_number')}%");
        }
        return $users;
    }
}
