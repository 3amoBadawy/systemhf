<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $business_name
 * @property string $business_name_ar
 * @property numeric $default_profit_percent
 * @property string $currency
 * @property string $currency_symbol
 * @property string $currency_symbol_placement
 * @property string $timezone
 * @property string|null $logo
 * @property string $date_format
 * @property string $time_format
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessSetting whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessSetting whereBusinessName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessSetting whereBusinessNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessSetting whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessSetting whereCurrencySymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessSetting whereCurrencySymbolPlacement($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessSetting whereDateFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessSetting whereDefaultProfitPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessSetting whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessSetting whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessSetting whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessSetting wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessSetting whereTimeFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessSetting whereTimezone($value)
 * @method statifinal c \Illuminate\Database\Eloquent\Builder<static>|BusinessSetting whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class BusinessSetting extends Model
{
    protected $fillable = [
        'business_name',
        'business_name_ar',
        'default_profit_percent',
        'currency',
        'currency_symbol',
        'currency_symbol_placement',
        'timezone',
        'logo',
        'date_format',
        'time_format',
        'phone',
        'email',
        'address',
        'description',
    ];

    protected $casts = [
        'default_profit_percent' => 'decimal:2',
    ];

    /**
     * Get the singleton instance of business settings
     */
    public static function getInstance()
    {
        return static::firstOrCreate([]);
    }

    /**
     * Get formatted currency
     */
    public function formatCurrency($amount): string
    {
        $formatted = number_format($amount, 2);

        if ($this->currency_symbol_placement === 'before') {
            return $this->currency_symbol.' '.$formatted;
        }

        return $formatted.' '.$this->currency_symbol;
    }

    /**
     * Get available timezones
     *
     * @return string[]
     *
     * @psalm-return array{'Africa/Cairo': 'القاهرة (GMT+2)', 'Africa/Alexandria': 'الإسكندرية (GMT+2)', 'Africa/Luxor': 'الأقصر (GMT+2)', 'Africa/Aswan': 'أسوان (GMT+2)', 'Europe/London': 'لندن (GMT+0)', 'America/New_York': 'نيويورك (GMT-5)', 'Europe/Berlin': 'برلين (GMT+1)', 'Asia/Dubai': 'دبي (GMT+4)'}
     */
    public static function getTimezones(): array
    {
        return [
            'Africa/Cairo' => 'القاهرة (GMT+2)',
            'Africa/Alexandria' => 'الإسكندرية (GMT+2)',
            'Africa/Luxor' => 'الأقصر (GMT+2)',
            'Africa/Aswan' => 'أسوان (GMT+2)',
            'Europe/London' => 'لندن (GMT+0)',
            'America/New_York' => 'نيويورك (GMT-5)',
            'Europe/Berlin' => 'برلين (GMT+1)',
            'Asia/Dubai' => 'دبي (GMT+4)',
        ];
    }

    /**
     * Get available date formats
     *
     * @return string[]
     *
     * @psalm-return array{'Y-m-d': 'YYYY-MM-DD (2025-08-22)', 'd-m-Y': 'DD-MM-YYYY (22-08-2025)', 'm/d/Y': 'MM/DD/YYYY (08/22/2025)', 'd/m/Y': 'DD/MM/YYYY (22/08/2025)', 'F j, Y': 'Month Day, Year (August 22, 2025)'}
     */
    public static function getDateFormats(): array
    {
        return [
            'Y-m-d' => 'YYYY-MM-DD (2025-08-22)',
            'd-m-Y' => 'DD-MM-YYYY (22-08-2025)',
            'm/d/Y' => 'MM/DD/YYYY (08/22/2025)',
            'd/m/Y' => 'DD/MM/YYYY (22/08/2025)',
            'F j, Y' => 'Month Day, Year (August 22, 2025)',
        ];
    }

    /**
     * Get available time formats
     *
     * @return string[]
     *
     * @psalm-return array{'H:i': '24 ساعة (14:30)', 'h:i A': '12 ساعة (2:30 PM)', 'H:i:s': '24 ساعة مع الثواني (14:30:45)', 'h:i:s A': '12 ساعة مع الثواني (2:30:45 PM)'}
     */
    public static function getTimeFormats(): array
    {
        return [
            'H:i' => '24 ساعة (14:30)',
            'h:i A' => '12 ساعة (2:30 PM)',
            'H:i:s' => '24 ساعة مع الثواني (14:30:45)',
            'h:i:s A' => '12 ساعة مع الثواني (2:30:45 PM)',
        ];
    }

    /**
     * Get available currencies
     *
     * @return string[][]
     *
     * @psalm-return array{EGP: array{name: 'جنيه مصري', symbol: 'ج.م'}, USD: array{name: 'دولار أمريكي', symbol: '$'}, EUR: array{name: 'يورو', symbol: '€'}, GBP: array{name: 'جنيه إسترليني', symbol: '£'}, SAR: array{name: 'ريال سعودي', symbol: 'ريال'}, AED: array{name: 'درهم إماراتي', symbol: 'د.إ'}, KWD: array{name: 'دينار كويتي', symbol: 'د.ك'}, QAR: array{name: 'ريال قطري', symbol: 'ر.ق'}}
     */
    public static function getCurrencies(): array
    {
        return [
            'EGP' => ['name' => 'جنيه مصري', 'symbol' => 'ج.م'],
            'USD' => ['name' => 'دولار أمريكي', 'symbol' => '$'],
            'EUR' => ['name' => 'يورو', 'symbol' => '€'],
            'GBP' => ['name' => 'جنيه إسترليني', 'symbol' => '£'],
            'SAR' => ['name' => 'ريال سعودي', 'symbol' => 'ريال'],
            'AED' => ['name' => 'درهم إماراتي', 'symbol' => 'د.إ'],
            'KWD' => ['name' => 'دينار كويتي', 'symbol' => 'د.ك'],
            'QAR' => ['name' => 'ريال قطري', 'symbol' => 'ر.ق'],
        ];
    }
}
