<?php


namespace App\Http\Controllers\Admin;


use App\Forms\Admin\SeoForm;
use App\Models\Seo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BaseController
{
    private $class = null;
    private $form = null;
    private $moduleName = '';
    private $moduleView = '';
    private $seo = false;
    private $paginateLimit = 20;

    public function __construct($class, $form, $moduleName, $seo = false, $paginateLimit = 20)
    {
        $this->class = $class;
        $this->form = $form;
        $this->moduleName = $moduleName;
        $this->moduleView = $this->getModuleView($moduleName);
        $this->seo = $seo;
        $this->paginateLimit = $paginateLimit;
    }

    // GETTERS
    public function index() {
        $items = $this->class::with([])
            ->adminLocale()
            ->orderByDesc('position')
            ->paginate($this->paginateLimit);
        return view('admin.'.$this->moduleView.'.index', compact('items'));
    }


    public function create() {
        $item = new $this->class();
        $form = new $this->form();
        $formSeo = null;

        $data = [
            'item' => $item,
            'form' => $form,
        ];

        if($this->seo) {
            $formSeo = new SeoForm();
            $data['formSeo'] = $formSeo;
        }

        return view('admin.'.$this->moduleView.'.edit', $data);
    }

    public function show(int $id) {
        $item = $this->class::with(['seo'])->findOrFail($id);
        $form = new $this->form($item);
        $formSeo = null;

        if($item->seo) {
            $formSeo = new SeoForm($item->seo);
        }

        return view('admin.'.$this->moduleView.'.edit', compact('item', 'form', 'formSeo'));
    }


    // MODIFIERS
    public function baseEdit($request) {
        $id = $request->id;
        $post = $request->post();

        $item = $this->class::with(['seo'])->findOrNew($id);

        if(isset($post['seo'])) {
            $rules = [];

            foreach (SeoForm::FIELDS as $name=>$field) {
                $rules['seo.'.$name] = $field['rules'];
            }

            $seoId = null;
            if($item->seo) {
                $seoId = $item->seo->id;
            }
            $rules['seo.url'][] = Rule::unique('seo')->ignore($seoId)->where('lang', getAdminLang());
//            dd($rules);

//            if($item->seo) {
//                foreach (SeoForm::getRules($item->seo) as $name=>$field) {
//                    $rules['seo.'.$name] = $field['rules'];
//                }
//            }
//            else {
//                foreach (SeoForm::FIELDS as $name=>$field) {
//                    $rules['seo.'.$name] = $field['rules'];
//                }
//            }

            Validator::make($post, $rules)->validate();
        }

        $item->fill($post[$this->moduleName]);

        if($item->seo) {
            $item->seo()->update($post['seo']);
        }
        elseif ($this->seo) {
            $item->seo()->associate(Seo::create($post['seo']));
        }


        $item->save();


        if($id) {
            Log::info(__('admin.log.updated', ['model' => $this->moduleName, 'id' => $item->getKey()]));
            return redirect(route('admin.'.$this->moduleName.'.index'));
        }
        Log::info(__('admin.log.created', ['model' => $this->moduleName, 'id' => $item->getKey()]));
        return redirect(route('admin.'.$this->moduleName.'.show', $item));
    }

    public function delete(int $id) {
        $item = $this->class::with([])->findOrFail($id);
        if($item->seo) {
            $item->seo()->delete();
        }
        if($item->gallery) {
            $item->gallery()->delete();
        }
        $item->delete();
        Log::info(__('admin.log.deleted', ['model' => $this->moduleName, 'id' => $id]));
        return redirect(route('admin.'.$this->moduleName.'.index'));
    }



    private function getModuleView($moduleName) {
        $exceptions = ['category'];
        $arr = explode('_', $moduleName);

        foreach ($exceptions as $exception) {
            if(in_array($exception, $arr)) {
                return str_replace('_'.$exception, '.'.$exception, $moduleName);
            }
        }

        return $moduleName;
    }
}
