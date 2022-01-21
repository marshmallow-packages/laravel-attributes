<?php

declare(strict_types=1);

namespace Marshmallow\Attributes\Models\Type;

use Marshmallow\Attributes\Models\Value;

/**
 * Marshmallow\Attributes\Models\Type\Text.
 *
 * @property int                 $id
 * @property string              $content
 * @property int                 $attribute_id
 * @property int                 $entity_id
 * @property string              $entity_type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Marshmallow\Attributes\Models\Attribute           $attribute
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $entity
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Text whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Text whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Text whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Text whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Text whereEntityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Text whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\Type\Text whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Text extends Value
{
    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'content' => 'string',
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
        $this->setTable(config('marshmallow-attributes.tables.attribute_text_values'));
        $this->mergeRules([
            'content' => 'required|string|max:32768',
            'attribute_id' => 'required|integer|exists:' . config('marshmallow-attributes.tables.attributes') . ',id',
            'entity_id' => 'required|integer',
            'entity_type' => 'required|string|max:150',
        ]);

        parent::__construct($attributes);
    }
}
