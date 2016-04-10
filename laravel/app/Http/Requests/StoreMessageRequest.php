<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreMessageRequest extends Request {

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
		$rules = [
			'text' => 'required_without:photo.0',
		];

		return $rules;
	}

	public function validator($factory)
	{

		$messages = [
			'image' => 'Uploaded file must be an image',
			'required_without' => 'Please fill one of these fields'
		];
		$v = $factory->make($this->all(), $this->rules(), $messages);
		$v->each('photo', ['image']);
//		$v->each('link', ['']);
		return $v;
	}



}
