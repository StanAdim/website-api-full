<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
// use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCollection extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uid' => $this->id,
            'email' => $this->email,
            'firstName' => $this->firstName,
            'middleName' => $this->middleName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'createdDate'=> Carbon::parse($this ->created_at)->format('d-m-Y'),         
            'createdTime'=> Carbon::parse($this ->created_at)->format('H:i:s')            
        ];
    }
}
