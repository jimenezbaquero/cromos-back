<?php

namespace App\Http\Requests;

use App\Models\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class CollectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()?->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer'],
            'publisher_id' => ['required', 'integer', 'exists:publishers,id'],
        ];
    }
    
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $id = $this->collection?->id;
            $exists = Collection::where('name', $this->input('name'))
                ->where('year', $this->input('year'))
                ->where('publisher_id', $this->input('publisher_id'));
            
            if(!is_null($id)){
                $exists = $exists->where('id','<>', $id);
            }
            
            $exists = $exists->exists();
            
            if ($exists) {
                $validator->errors()->add('name', 'Ya existe una colección con este nombre, año y editor.');
            }
        });
    }
}
