<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\StudentCreated;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserCreatedListener
{
    /**
     * Create the event listener.
     */

    protected  $userModel, $studentModel;

    public function __construct(User $userModel, Student $studentModel)
    {
        $this->userModel = $userModel;
        $this->studentModel = $studentModel;
    }

    /**
     * Handle the event.
     */
    public function handle(\App\Events\Student\StudentCreated $event): void
    {
        DB::transaction(function () use ($event) {

            $user = User::where('email', $event->student->nis . '@spp.com')->first();

            $name = $event->student->name;
            $name = strtolower($name);
            $words = explode(" ", $name);
            $initials = "";
            foreach ($words as $word) {
                $initials .= $word[0];
            }

            if (!$user) {
                $user = $this->userModel->create([
                    'name' => $event->student->name,
                    'role' => User::SISWA,
                    'email' => $event->student->nis . '@spp.com',
                    'password' => Hash::make($initials . $event->student->nis),
                    'gender' => $event->student->gender,
                ]);
            }

            $userId = $user->id;
            $event->student->update(['user_id' => $userId]);
        });
    }
}
