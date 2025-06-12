<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LegalController extends Controller
{
    public function privacy()
    {
        return view('legal.privacy');
    }

    public function terms()
    {
        return view('legal.terms');
    }

    public function contact()
    {
        return view('legal.contact');
    }

    public function sendContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000'
        ]);

        // Pour l'instant, on sauvegarde dans les logs
        \Log::info('Contact StudiosUnisDB', [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message
        ]);

        return back()->with('success', 'Votre message a été envoyé avec succès. Nous vous répondrons sous 24h.');
    }
}
