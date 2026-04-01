<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Events\AiMessageEvent;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        // 1. Validate incoming message
        $request->validate([
            'message' => 'required|string'
        ]);

        $userMessage = $request->message;
        $sessionId = session()->getId(); // User ka unique session ID

       // 2. Setup Gemini AI Prompt
        $apiKey = env('GEMINI_API_KEY');
        $systemPrompt = "You are Sangam AI, a highly professional and polite travel assistant for 'Sangam Tours' in Bihar. Help users book buses, hotels, and holiday packages. Keep responses short, helpful, and in natural Hinglish.";

        // Agar API key empty hai toh yahi error de dega
        if(empty($apiKey)) {
            $aiText = "Bhai, .env file mein GEMINI_API_KEY nahi mil rahi hai! Ek baar php artisan config:clear chalao.";
        } else {
            // 3. Call Google Gemini API (Added withoutVerifying() for local testing)
            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json'
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $systemPrompt . " \n\nUser: " . $userMessage]]]
                ]
            ]);

            // Default message
            $aiText = "Maafi chahunga, abhi server mein thodi dikkat hai.";

            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                    $aiText = $responseData['candidates'][0]['content']['parts'][0]['text'];
                } else {
                    $aiText = "API ne ajeeb response diya: " . json_encode($responseData);
                }
            } else {
                // 🔥 YAHAN ASLI ERROR DIKHEGA 🔥
                $aiText = "API Error Code " . $response->status() . " : " . $response->body();
            }
        }

        // 4. Broadcast the response using Reverb
        broadcast(new \App\Events\AiMessageEvent($aiText, $sessionId)); // Removed toOthers() temporarily for strict testing

        return response()->json([
            'status' => 'success'
        ]);
    }
}