<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArduinoController extends Controller
{
    public function sendData(Request $request)
    {
        // Retrieve data from the request
        $data = $request->input('data');

        // Process the data (e.g., send to Arduino, store in a database)

        // Add code here to send data to Arduino over Ethernet

        // Return a response
        return response()->json(['message' => 'Data received and processed']);
    }
}
