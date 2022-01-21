<?php

declare(strict_types=1);

namespace Marshmallow\Attributes\Models\Type;

use Marshmallow\Attributes\Models\Value;

/**
 * Marshmallow\Attributes\Models\Type\Datetime.
 *
 * @property int                 $id
 * @property \Carbon\Carbon      $content
 * @property int                 $attribute_id
 * @property int                 $entity_id
 * @property string              $entity_type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Marshmallow\Attributes\Models\Attribute           $attribute
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $entity
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Datetime whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Datetime whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Datetime whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Datetime whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Datetime whereEntityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Datetime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Datetime whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Datetime extends Value
{
    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'content' => 'datetime',
        'attribute_id' => 'integer',
        'entity_id' => 'integer',
        'entity_type' => 'string',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('marshmallow-attributes.tables.attribute_datetime_values'));
        $this->mergeRules([
            'content' => 'required|date',
            'attribute_id' => 'required|integer|exists:' . config('marshmallow-attributes.tables.attributes') . ',id',
            'entity_id' => 'required|integer',
            'entity_type' => 'required|string|max:150',
        ]);

        parent::__construct($attributes);
    }
}
