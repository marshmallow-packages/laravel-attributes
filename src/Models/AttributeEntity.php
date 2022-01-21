<?php

declare(strict_types=1);

namespace Marshmallow\Attributes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Marshmallow\Attributes\Models\AttributeEntity.
 *
 * @property int                 $attribute_id
 * @property string              $entity_type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\AttributeEntity whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\AttributeEntity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\AttributeEntity whereEntityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Marshmallow\Attributes\Models\AttributeEntity whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AttributeEntity extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'entity_type',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'entity_type' => 'string',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('marshmallow-attributes.tables.attribute_entity'));

        parent::__construct($attributes);
    }

    /**
     * Get the attribute attached to this entity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(config('marshmallow-attributes.models.attribute'));
    }
}
