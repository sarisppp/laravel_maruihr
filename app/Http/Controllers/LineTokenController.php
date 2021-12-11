<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LineToken;

class LineTokenController extends Controller
{
    public function editToken(Request $request)
    {

        $token = LineToken::first()->update([
            'token' => $request->token,
        ]);

        return response()->json($token , 201);
    }

    public function token()
    {
        $data = LineToken::first();

        return response()->json($data);
    }
}
