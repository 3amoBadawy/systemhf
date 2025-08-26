<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name_ar
 * @property string|null $phone
 * @property string|null $phone2
 * @property string|null $email
 * @property string|null $address
 * @property string|null $governorate
 * @property string|null $notes
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereGovernorate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePhone2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereUpdatedAt($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'phone',
        'phone2',
        'email',
        'address',
        'governorate',
        'notes',
        'is_active',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'is_active' => 'boolean',
        'name' => 'string',
        'name_ar' => 'string',
        'code' => 'string',
        'contact_person' => 'string',
        'phone' => 'string',
        'phone2' => 'string',
        'email' => 'string',
        'website' => 'string',
        'address' => 'string',
        'address_ar' => 'string',
        'country' => 'string',
        'governorate' => 'string',
        'city' => 'string',
        'postal_code' => 'string',
        'tax_number' => 'string',
        'commercial_record' => 'string',
        'payment_terms' => 'string',
        'credit_limit' => 'decimal:2',
        'branch_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];
}
