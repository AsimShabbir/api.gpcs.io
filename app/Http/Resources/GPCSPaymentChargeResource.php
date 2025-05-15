<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Enums\Roles;
use App\Models\User;
class GPCSPaymentChargeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'is_active' => $this->is_active,
            'created_by' => $this->created_by,
            'created_by_name' => $this->GetUserName($this->created_by),
            'updated_by' => $this->updated_by,
            'updated_by_name' => $this->GetUserName($this->updated_by),
        ];
    }
    private function GetUserName($id)
    {
        $user = User::find($id);
        //dd($user);
        return $user->first_name . '  ' . $user->last_name;
    }
}
