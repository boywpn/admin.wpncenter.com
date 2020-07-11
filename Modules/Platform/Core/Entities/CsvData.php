<?php
/**
 * Created by PhpStorm.
 * User: jw
 * Date: 02.10.18
 * Time: 09:59
 */

namespace Modules\Platform\Core\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * Temporary table
 * 
 * Class CsvData
 *
 * @package Modules\Platform\Core\Entities
 * @property int $id
 * @property string $csv_filename
 * @property int $csv_header
 * @property string $csv_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CsvData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CsvData whereCsvData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CsvData whereCsvFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CsvData whereCsvHeader($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CsvData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CsvData whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CsvData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CsvData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CsvData query()
 */
class CsvData extends Model
{

    protected $table = 'bap_csv_data';

    protected $fillable = [
        'csv_filename',
        'csv_header',
        'csv_data'
    ];

}
