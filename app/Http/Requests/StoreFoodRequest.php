<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFoodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole('waiter');
    }

    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:255',
            'category'     => 'required|in:food,drink',
            'price'        => 'required|numeric|min:0',
            'description'  => 'nullable|string',
            'image_url'    => $this->image 
                            ? Storage::disk('public')->url($this->image) 
                            : asset('images/default-food.png'),
            'is_available' => 'boolean',
        ];
    }
}
