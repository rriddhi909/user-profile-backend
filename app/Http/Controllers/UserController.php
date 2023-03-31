<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function getUser(Request $request)
    {
        $user = User::where('gender', '=', $request->gender)
            ->where('country_code', '=', $request->country)
            ->first();
        if ($user === null) {
            return $this->fetchUser($request);
        } else {
            return $user->toArray();
        }
    }

    public function fetchUser(Request $request)
    {
        $response = Http::get('https://randomuser.me/api/?nat=' . $request->country . '&gender=' . $request->gender);
        $jsonData = $response->json();
        $user = User::create([
            'country_code' => $request->country,
            'country' => $jsonData['results'][0]['location']['country'],
            'gender' => $request->gender,
            'image_url' => $jsonData['results'][0]['picture']['large']
        ]);
        
        return $user->toArray();
    }
}
