<?php 
namespace MaiMeng\EloquentCache\Common;

use MaiMeng\EloquentCache\Contracts\IMeta;
use Illuminate\Contracts\Redis\Factory as Redis;

class RedisMeta implements IMeta
{
    private $prefix = 'maimeng';

    const KEY_SCHEMA_VERSION = 'schema_version';
    const KEY_UPDATE_VERSION = 'update_version';
    
    private $redis;
    
    public function __construct( Redis $redis )
    {
    	$this->redis = $redis;
    }

    public function prefix($db, $table, $isForTable = false)
    {
        $version = $this->getSchemaVersion($db, $table);
        if ($isForTable) {
            $version = $version . ':' . $this->getUpdateVersion($db, $table);
        }

        return implode(':', [
            $this->prefix,
            $db,
            $table,
            $version,
        ]);
    }

    private function getSchemaVersion($db, $table)
    {
        $key = implode(':', [
            $this->prefix,
            self::KEY_SCHEMA_VERSION,
            $db,
            $table,
        ]);

        $key = md5($key);

        return $this->redis->get($key) ?: 0;
    }

    private function getUpdateVersion($db, $table)
    {
        $key = implode(':', [
            $this->prefix,
            self::KEY_UPDATE_VERSION,
            $db,
            $table,
        ]);

        $key = md5($key);

        return $this->redis->get($key) ?: 0;
    }

    public function flush($db, $table)
    {
        $key = implode(':', [
            $this->prefix,
            self::KEY_UPDATE_VERSION,
            $db,
            $table,
        ]);

        $key = md5($key);

        $this->redis->incr($key);
    }

    public function flushAll($db, $table)
    {
        $key = implode(':', [
            $this->prefix,
            self::KEY_SCHEMA_VERSION,
            $db,
            $table,
        ]);

        $key = md5($key);

        $this->redis->incr($key);
    }
}
