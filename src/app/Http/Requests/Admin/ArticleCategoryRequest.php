<?php

namespace App\Http\Requests\Admin;

use App\Forms\Admin\ArticleCategoryForm;
use Illuminate\Foundation\Http\FormRequest;

class ArticleCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        foreach (ArticleCategoryForm::FIELDS as $field) {
            $rules['article_category.'.$field['name']] = $field['rules'];
        }

        return $rules;
    }
}
