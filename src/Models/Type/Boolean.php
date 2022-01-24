<?php

namespace Marshmallow\Attributes\Models\Type;

use Marshmallow\Attributes\Models\Value;

/**
 * Marshmallow\Attributes\Models\Type\Boolean.
 *
 * @property int                 $id
 * @property bool                $content
 * @property int                 $attribute_id
 * @property int                 $entity_id
 * @property string              $entity_type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Marshmallow\Attributes\Models\Attribute           $attribute
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $entity
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Boolean whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Boolean whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Boolean whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Boolean whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Boolean whereEntityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Boolean whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Boolean whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Boolean extends Value
{
    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'content' => 'boolean',
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
        $this->setTable(config('marshmallow-attributes.tables.attribute_boolean_values'));
        $this->mergeRules([
            'content' => 'required|boolean',
            'attribute_id' => 'required|integer|exists:' . config('marshmallow-attributes.tables.attributes') . ',id',
            'entity_id' => 'required|integer',
            'entity_type' => 'required|string|max:150',
        ]);

        parent::__construct($attributes);
    }
}
