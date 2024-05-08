<?php

namespace App\Http\Resources;

use App\Models\LightShow;
use mathewparet\LaravelPolicyAbilitiesExport\Resources\ResourceCollectionWithPermissions;

class LightShowResourceCollection extends ResourceCollectionWithPermissions
{
    /**
     * @var \Illuminate\Database\Eloquent\Model|string $model 
     */
    protected $model = LightShow::class;
}