<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AirtableConnection;
use App\User;

class AirtableController extends Controller
{
    public function addConnection(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'api_key' => 'required',
        ]);

        $user = User::find(Auth::user()->id);

        $airtable = new AirtableConnection();
        $airtable->name = $request->name;
        $airtable->api_key = $request->api_key;
        $airtable->user_id = $user->id;
        $airtable->save();

        $all_airtable = AirtableConnection::where(['user_id' => $user->id])->get();

        $response = array(
            'user' => $user,
            'airtable' => $all_airtable
        );

        return response()->json($response, 200);
    }

    public function allConnections(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $all_airtable = AirtableConnection::where(['user_id' => $user->id])->get();

        $response = array(
            'user' => $user,
            'airtable' => $all_airtable
        );

        return response()->json($response, 200);
    }

    public function removeConnection(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $airtable = AirtableConnection::find($request->id);

        $user = User::find(Auth::user()->id);

        if ($airtable->user_id != $user->id)
        {
            $response = array(
                'success' => false,
                'message' => 'This Airtable connection does not belongs to you'
            );
    
            return response()->json($response, 403);
        }

        $airtable->delete();

        $response = array(
            'success' => true
        );

        return response()->json($response, 200);
    }
}
