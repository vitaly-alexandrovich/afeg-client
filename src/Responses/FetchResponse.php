<?php
namespace Afeg\Responses;

use Afeg\Message;
use Modeler\Model;
use Modeler\Property;

/**
 * Class FetchResponse
 * @package Afeg\Responses
 *
 * @method boolean hasItems()
 * @method boolean hasLength()
 * @method boolean hasIsAll()
 *
 * @method Message[] getItems()
 * @method integer getLength()
 * @method boolean getIsAll()
 */
class FetchResponse extends Model
{
    /**
     * @return array
     */
    protected static function mapProperties()
    {
        return [
            'items' => Property::raw(),
            'length' => Property::integer(),
            'is_all' => Property::boolean(),
        ];
    }

    /**
     * @return false|int
     */
    public function isAll()
    {
        return $this->getIsAll();
    }
}
