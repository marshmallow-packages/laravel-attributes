<?php

namespace Marshmallow\Attributes\Support;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Marshmallow\Attributes\Models\Attribute;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class ValueCollection extends EloquentCollection
{
    /**
     * The entity this value collection belongs to.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $entity;

    /**
     * The attribute this value collection is storing.
     *
     * @var \Marshmallow\Attributes\Models\Attribute
     */
    protected $attribute;

    /**
     * Link the entity and attribute to this value collection.
     *
     * @param \Illuminate\Database\Eloquent\Model $entity
     * @param $attribute
     *
     * @return $this
     */
    public function link($entity, $attribute)
    {
        $this->entity = $entity;
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * Add new values to the value collection.
     *
     * @param \Illuminate\Support\Collection|array $values
     *
     * @return $this
     */
    public function add($values)
    {
        if (!is_array($values) && !$values instanceof BaseCollection) {
            $values = func_get_args();
        }

        // Once we have made sure our input is an array of values, we will convert
        // them into value model objects (if no model instances are given). When
        // done we will just push all values into the current collection items.
        foreach ($values as $value) {
            $this->push($this->buildValue($value));
        }

        return $this;
    }

    /**
     * Replace current values with the given values.
     *
     * @param \Illuminate\Support\Collection|array $values
     *
     * @return $this
     */
    public function replace($values)
    {
        if (!is_array($values) && !$values instanceof BaseCollection) {
            $values = func_get_args();
        }

        // Trash the current values
        $this->trashCurrentItems();

        // Build valid instances of the given values based on attribute data type
        $this->items = $this->buildValues($values);

        return $this;
    }

    /**
     * Trash the current values by queuing into entity object,
     * these trashed values will physically deleted on entity save.
     *
     * @return void
     */
    protected function trashCurrentItems(): void
    {
        $trash = $this->entity->getEntityAttributeValueTrash();

        foreach ($this->items as $value) {
            $trash->push($value);
        }
    }

    /**
     * Build a value instance from the given input.
     *
     * @param mixed $value
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function buildValue($value): Model
    {
        if ($value instanceof Model || is_null($value)) {
            return $value;
        }

        $model = Attribute::getTypeModel($this->attribute->getAttribute('type'));
        $instance = new $model();

        $instance->setAttribute('entity_id', $this->entity->getKey());
        $instance->setAttribute('entity_type', $this->entity->getMorphClass());
        $instance->setAttribute($this->attribute->getForeignKey(), $this->attribute->getKey());
        $instance->setAttribute('content', $value);

        return $instance;
    }

    /**
     * Build value instances from the given array.
     *
     * @param \Illuminate\Support\Collection|array $values
     *
     * @return \Marshmallow\Attributes\Models\Value[]
     */
    protected function buildValues($values)
    {
        $result = [];

        $test = json_decode($values[0]);
        if (is_array($test)) {
            $values = $test;
        } else {
            $test = explode(',', $values[0]);
            $values = $test;
        }

        // We will iterate through the entire array of values transforming every
        // item into the data type object linked to this collection. Any null
        // value will be omitted here in order to avoid storing NULL values.
        foreach ($values as $value) {
            if (is_numeric($value)) {
                $model = Attribute::getTypeModel($this->attribute->getAttribute('type'));
                $value = $model::find($value);
                $value = $value['content'];
            }
            $value = trim($value);

            if (!is_null($value)) {
                $result[] = $this->buildValue($value);
            }
        }

        return $result;
    }
}
