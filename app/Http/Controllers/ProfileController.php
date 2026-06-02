<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CandidateProfile;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $userId = session('user_id');
        $profile = CandidateProfile::firstOrCreate(['user_id' => $userId]);

        return view('profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $userId = session('user_id');
        $request->validate([
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'skills' => 'nullable|string',
            'education' => 'nullable|string',
            'experience' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf|max:4096',
        ]);

        $profile = CandidateProfile::firstOrCreate(['user_id' => $userId]);
        
        $data = $request->only(['phone', 'bio', 'skills', 'education', 'experience']);

        if ($request->hasFile('resume')) {
            // Delete old resume if exists
            if ($profile->resume_path && Storage::disk('public')->exists($profile->resume_path)) {
                Storage::disk('public')->delete($profile->resume_path);
            }
            $path = $request->file('resume')->store('resumes', 'public');
            $data['resume_path'] = $path;
        }

        $profile->update($data);

        return redirect()->back()->with('success', 'Perfil atualizado com sucesso!');
    }
}
