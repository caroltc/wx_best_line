<?php
namespace App\Api\V1\Format;

use Dingo\Api\Http\Response\Format\Format;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Created by PhpStorm.
 * User: root
 * Date: 17-5-12
 * Time: 下午4:53
 */
class JsonFormat extends Format
{

    /**
     * Format an Eloquent model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return string
     */
    public function formatEloquentModel($model)
    {
        $key = Str::singular($model->getTable());

        if (! $model::$snakeAttributes) {
            $key = Str::camel($key);
        }

        return $this->encode([$key => $model->toArray()]);
    }

    /**
     * Format an Eloquent collection.
     *
     * @param \Illuminate\Database\Eloquent\Collection $collection
     *
     * @return string
     */
    public function formatEloquentCollection($collection)
    {
        if ($collection->isEmpty()) {
            return $this->encode([]);
        }

        $model = $collection->first();
        $key = Str::plural($model->getTable());

        if (! $model::$snakeAttributes) {
            $key = Str::camel($key);
        }

        return $this->encode([$key => $collection->toArray()]);
    }

    /**
     * Format an array or instance implementing Arrayable.
     *
     * @param array|\Illuminate\Contracts\Support\Arrayable $content
     *
     * @return string
     */
    public function formatArray($content)
    {
        $content = $this->morphToArray($content);

        array_walk_recursive($content, function (&$value) {
            $value = $this->morphToArray($value);
        });

        return $this->encode($content);
    }

    /**
     * Get the response content type.
     *
     * @return string
     */
    public function getContentType()
    {
        return 'application/json';
    }

    /**
     * Morph a value to an array.
     *
     * @param array|\Illuminate\Contracts\Support\Arrayable $value
     *
     * @return array
     */
    protected function morphToArray($value)
    {
        return $value instanceof Arrayable ? $value->toArray() : $value;
    }

    /**
     * Encode the content to its JSON representation.
     *
     * @param string $content
     *
     * @return string
     */
    protected function encode($content)
    {
        if (!isset($content['error'])) {
            return json_encode(['errorcode' => 0, 'result' => $content, 'msg' => '请求成功']);
        }
        return json_encode(['errorcode' => 50000, 'msg' => $content['error']]);
    }
}