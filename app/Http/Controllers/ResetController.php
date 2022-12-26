<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class ResetController extends Controller
{
    public function reset():RedirectResponse
    {
        Artisan::call('migrate:fresh --seed');

        foreach (['categories', 'products'] as $folder) {
            Storage::deleteDirectory($folder);
            Storage::makeDirectory($folder);

            $files = Storage::disk('reset')->files($folder);

            foreach ($files as $file) {
                Storage::put($file, Storage::disk('reset')->get($file));
            }
        }

        session()->flash('success', 'Проект был сброшен в начальное состояние');

        return redirect()->route('index');
    }
}
