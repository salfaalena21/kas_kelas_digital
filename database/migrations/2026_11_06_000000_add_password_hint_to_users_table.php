<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('password_hint')->nullable()->after('password');
        });

        foreach (DB::table('users')->get() as $user) {
            $candidate = $user->username === 'admin' ? 'admin123' : $user->username;

            if (Hash::check($candidate, $user->password)) {
                DB::table('users')->where('id_user', $user->id_user)->update([
                    'password_hint' => Crypt::encryptString($candidate),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('password_hint');
        });
    }
};
