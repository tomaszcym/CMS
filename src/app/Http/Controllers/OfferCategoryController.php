<?php


namespace App\Http\Controllers;


use App\Forms\Admin\Admin\PageForm;
use App\Http\Requests\PageRequest;
use App\Models\OfferCategory;
use App\Models\Page;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class OfferCategoryController extends Controller
{
    public function show($item) {
        return view('default.offer_category.show', compact('item'));
    }

    public function index($view) {
        $items = OfferCategory::with([])
            ->activeAndLocale()
            ->paginate(5);
        $view->items = $items;
    }
}
