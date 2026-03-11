<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller
{

    public function index()
    {

        $user = auth()->user();

        $users = User::where('status', 1)
        ->orderBy('created_at', 'desc')
        ->get();

        //dd('teste', $users);

        return Inertia::render('Admin/Users/Index', [
            'user' => $user,
            'users' => $users,
        ]);

    }

    public function edit(User $user)
    {
        $data = $user->data;

        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'data' => $data
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'email' => 'required|email',
            'person_type' => 'required',
        ]);

        $user->update([
            'email' => $request->email,
        ]);

        $data = $user->data;

        if (!$data) {
            $data = $user->data()->create([]);
        }

        $data->person_type = $request->person_type;
        $data->full_name = $request->full_name;
        $data->document = $request->document;
        $data->phone = $request->phone;

        if ($request->hasFile('cnh_file')) {
            $data->cnh_file = $request->file('cnh_file')->store('documents');
        }

        if ($request->hasFile('instructor_credential')) {
            $data->instructor_credential = $request->file('instructor_credential')->store('documents');
        }

        if ($request->hasFile('criminal_record')) {
            $data->criminal_record = $request->file('criminal_record')->store('documents');
        }

        if ($request->hasFile('detran_certificate')) {
            $data->detran_certificate = $request->file('detran_certificate')->store('documents');
        }

        if ($request->hasFile('face_photo')) {
            $data->face_photo = $request->file('face_photo')->store('documents');
        }

        $data->save();

        return redirect()->route('users.show', $user->id);
    }

    public function suspend(User $user)
    {
        $user->status = 0;
        $user->save();

        return redirect()->back();
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users');
    }

    public function show(User $user)
    {
        $user->load('data');

        return Inertia::render('Admin/Users/Show', [
            'user' => $user
        ]);
    }

}