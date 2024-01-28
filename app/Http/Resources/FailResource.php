<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    protected $title;
    protected $english_error;
    protected $arabic_error;
    public function __construct($_title, $_english_error, $_arabic_error)
    {
        $this->title = $_title;
        $this->english_error =$_english_error;
        $this->arabic_error =$_arabic_error;

    }
    public function toArray($request)
    {
        return[
                'message'=>$this->title,
                'data'=> [
                    'arabic_error'=> $this->arabic_error,
                    'english_error'=> $this->english_error,
                    'arabic_result'=>'',
                    'english_result'=>'',
                ]
        ];
    }
}
