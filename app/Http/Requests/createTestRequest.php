<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createTestRequest extends FormRequest
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
        $inputs =  $this->request->all();
        if(isset($inputs['title'])){
            $inputs['title'] = htmlentities($inputs['title']) ;
        }
        if(isset($inputs['questions']) && is_array($inputs['questions'])){
            foreach($inputs['questions'] as $key=>$row){
                $inputs['questions'][$key] = htmlentities($row);
            }
        }

        echo htmlentities( $this->request->get('title'));
        echo html_entity_decode( $this->request->get('title'));
        die();

        return [
            'title'=>'rewquire|min:3'
        ];
    }
}
