<?php


namespace App\Http\Controllers;


use App\Forms\Admin\PageForm;
use App\Helpers\SeoHelper;
use App\Models\Article;
use App\Models\Offer;
use App\Models\Page;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class OfferController extends Controller
{
    public function show($item) {
        SeoHelper::setSeo($item->seo);

        return view('default.offer.show', compact('item'));
    }

    public function index($view) {
        $items = Offer::with([])
            ->activeAndLocale()
            ->orderByDesc('position')
            ->paginate(5);
        $view->items = $items;
    }

}
