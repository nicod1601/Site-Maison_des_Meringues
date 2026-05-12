<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function foo()
    {
        if (Gate::denies('access-admin')) {
            abort(403);
        }
        return view('emails.test.foo');
    }

    public function bar()
    {
        Mail::to('test@mail.test')->send(new TestMail());
        return view('emails.test.bar');
    }
}
