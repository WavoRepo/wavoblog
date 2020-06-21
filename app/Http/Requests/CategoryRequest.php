<?php

namespace App\Http\Requests;

use Exception;

use App\Http\Helpers\Text;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

final class CategoryRequest extends FormRequest
{
    protected $text;

    public function __construct (Text $text) {
        $this->text = $text;
    }

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
        return  [
            'name' => ['required', 'max:255',Rule::unique('category', 'name')->ignore($this->category)],
            'description' => ['sometimes', 'required'],
            'taxonomy' => ['sometimes', 'required'],
            'parent_id' => ['sometimes', 'required'],
        ];
    }

    /**
     * [validated description]
     * @method validated
     * @return [type]
     */
    public function validated()
    {
        $validated = $this->validator->validated();
        $validated['slug'] = $this->text->slugify($validated['name']);

        if (!array_key_exists('parent_id', $validated)){
            $validated['parent_id'] = 0;
        }

        return $validated;
    }
}
