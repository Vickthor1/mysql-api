<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'stock',
        'barcode',
        'external_source',
        'external_id',
        'is_external',
        'image',
    ];

    protected $casts = [
        'is_external' => 'boolean',
        'price' => 'float',
    ];

    /**
     * Retorna a URL da imagem ou null se não houver.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }
        // Se já for URL completa (http/https), usa direto
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        // Caso seja path local de upload
        return asset('storage/' . $this->image);
    }
}
