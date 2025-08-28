<?php

namespace App\Traits;

use App\Models\Company;
use App\Models\Invite;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait CompanyTrait
{
    public function shortUrlList()
    {
         $user = Auth::user();
        if ($user->hasRole('Admin')) {
            // Get all users invited by this admin (where user is inviter or invitee)
            // $invite_id = $user->id;
            //  $users = Invite::where('invite_id', $invite_id)->get();
            //  if ($users->isEmpty()) {
            //      $userIds = [$invite_id];
            //  }else{
            //      $userIds = $users->pluck('user_id')->merge($invite_id)->all();
            //  }
             $shortUrls = ShortUrl::with('user')->whereHas(
                'user',
                function ($query) use ($user) {
                    $query->where('company_id', $user->company_id);
                }
             )->paginate(10);

        } elseif ($user->hasRole('Member')) {
            $shortUrls = ShortUrl::where('user_id', $user->id)->paginate(10);
        } else {
            $shortUrls = ShortUrl::paginate(10);
        }
        return $shortUrls;
    }

    public function inviteList()
    {
        $user = Auth::user();
        if ($user->hasRole('Admin')) {
            // Get all invites where this user is the inviter
            return User::where('company_id', $user->company_id)->paginate(10);
        } elseif ($user->hasRole('SuperAdmin')) {
            // Get all invites where this user is invited
            return User::whereNotNull('company_id')->paginate(10);
        }else{
             return '';
        }

       // return Invite::paginate(2);
    }

    public function userCount($role)
    {
        $user = Auth::user();
        if ($user->hasRole('Admin')) {
           $userCount = User::where('company_id', $user->company_id)->count();
        } else {
            $userCount = User::where('name','!=', 'Super Admin')->count();
        }
        return $userCount;
    }





}
