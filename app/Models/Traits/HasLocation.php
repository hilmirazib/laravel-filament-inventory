<?php
namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;

trait HasLocation
{
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'code');
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'village_id', 'code');
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id', 'code');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id', 'code');
    }
}
