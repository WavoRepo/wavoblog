<?php

namespace App\Http\Requests;

use Exception;

use App\Http\Helpers\Text;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

final class PostRequest extends FormRequest
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
            'title' => ['required', 'max:255'],
            'content' => ['sometimes', 'required'],
            'status' => ['sometimes', 'required'],
        ];
    }

    public function validated()
    {
        $validated = $this->validator->validated();
        $validated['slug'] = $this->text->slugify($validated['title']);

        return $validated;
    }
}
