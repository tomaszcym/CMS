<?php


namespace App\Http\Controllers;


use App\Forms\Admin\PageForm;
use App\Helpers\SeoHelper;
use App\Models\Realization;
use App\Models\Page;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RealizationController extends Controller
{
    public function show($item) {
        SeoHelper::setSeo($item->seo);

        return view('default.realization.show', compact('item'));
    }

    public function index($view) {
        $items = Realization::with([])
            ->activeAndLocale()
            ->paginate(5);
        $view->items = $items;
    }
}
