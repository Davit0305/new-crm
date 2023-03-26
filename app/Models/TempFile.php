<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\TempFile
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TempFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TempFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TempFile query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property int $order_id
 * @property string $folder
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */

class TempFile extends Model
{
    protected $table = 'temporary_files';
    protected $fillable = array('order_id', 'folder', 'name', 'user_id');

    public static function addTempForFiles( $name, $folder, $order_id = null){
        $temp = new self;
        $temp->user_id = Auth::id();
        $temp->name = $name;
        $temp->folder = $folder;
        $temp->order_id = $order_id;
        $temp->save();

        return $temp;
    }

    /** Получает коллекцию новых добавленных текущим пользователем файлов
     * @param mixed $user_id
     * @param null $order_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getTemporaryFiles($user_id, $order_id = null, $folder = null)
    {
        $result = self::where('user_id', (int)$user_id, 'AND')->where('order_id', (int)$order_id)->where('folder', $folder)->get();
        return $result;
    }

    public static function deleteTemporaryFile( $user_id, $order_id = null, $folder = null)
    {
        self::where('user_id', '=', (int)$user_id, 'AND')->where('order_id', '=', $order_id)->where('folder', '=', $folder)->delete();
    }
}
