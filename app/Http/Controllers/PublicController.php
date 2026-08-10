<?php

namespace App\Http\Controllers;

use App\Support\Site;
use Inertia\Inertia;

class PublicController extends Controller
{
    public function home()
    {
        return Inertia::render('Home');
    }

    public function about()
    {
        return Inertia::render('About');
    }

    public function services()
    {
        return Inertia::render('Services');
    }

    public function serviceDetail(string $slug)
    {
        $services = $this->content()['services'] ?? [];

        if (! collect($services)->firstWhere('slug', $slug)) {
            abort(404);
        }

        return Inertia::render('ServiceDetail', [
            'slug' => $slug,
        ]);
    }

    public function contact()
    {
        return Inertia::render('Contact');
    }

    private function content(): array
    {
        return Site::data();
    }
}
