<?php
/**
 * Created by PhpStorm.
 * User: laravel-bap.com
 * Date: 01.10.18
 * Time: 20:27
 */

namespace Modules\Platform\Core\Http\Requests;

use App\Http\Requests\Request;

class CsvImportRequest extends Request
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

    public function rules()
    {
        return [
            'csv_file' => 'required|file|mimes:csv,txt',
        ];
    }
}