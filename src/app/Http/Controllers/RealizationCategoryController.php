<?php


namespace App\Http\Controllers;


use App\Forms\Admin\PageForm;
use App\Http\Requests\PageRequest;
use App\Models\RealizationCategory;
use App\Models\Page;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RealizationCategoryController extends Controller
{
    public function show($item) {
        return view('default.realization_category.show', compact('item'));
    }

    public function index($view) {
        $items = RealizationCategory::with([])
            ->activeAndLocale()
            ->paginate(5);
        $view->items = $items;
    }
}
