<?php

namespace WF\Batch\Traits;

use WF\Batch\Batch;

trait Batchable
{
    public static function newBatch(iterable $models) : Batch
    {
        return Batch::of(static::class, $models);
    }

    /**
     * @param  iterable $models
     * @param  integer  $batchSize
     * @return array    The ids that were just saved (if available).
     */
    public static function batchSave(iterable $models, int $batchSize = 500) : array
    {
        return static::newBatch($models)->batchSize($batchSize)->save()->now();
    }

    public static function batchSaveQueue(iterable $models, int $chunkSize = 500, string $queue = null) : void
    {
        static::newBatch($models)->batchSize($chunkSize)->save()->onQueue($queue)->dispatch();
    }

    /**
     * @param  iterable $models
     * @param  integer  $batchSize
     * @return array    The ids of the models that were deleted.
     */
    public static function batchDelete(iterable $models, int $batchSize = 500) : array
    {
        return static::newBatch($models)->batchSize($batchSize)->delete()->now();
    }

    public static function batchDeleteQueue(iterable $models, int $batchSize = 500, string $queue = null) : void
    {
        static::newBatch($models)->batchSize($batchSize)->delete()->onQueue($queue)->dispatch();
    }
}
