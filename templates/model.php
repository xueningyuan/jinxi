namespace models;

class <?=$mname?> extends Model
{
    protected $table = <?=$tableName?>;
    // 设置允许接受的字段
    protected $fillable = ['title','content','is_show'];
}
