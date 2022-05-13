<?php


namespace App\Http\Controllers;


use App\Forms\Admin\PageForm;
use App\Http\Requests\PageRequest;
use App\Models\Article;
use App\Models\Page;
use App\Models\SiteLang;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function show($view) {
    }

    public function langNav($view) {
        $view->items = SiteLang::with([])
            ->active()
            ->get();
    }
}
