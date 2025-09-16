<?php

namespace App\Models;

use App\Enums\ProductPriceEnum;
use App\Enums\ProductStockEnum;
use App\Models\Filters\ProductFilter;
use App\Traits\Filterable;
use App\Traits\ImageUpload;
use App\Traits\JsonAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasFactory, ImageUpload, Filterable, JsonAttribute, HasTranslations;

    protected $filterClass = ProductFilter::class;
    protected $with = ['size', 'color', 'category'];

    protected $casts = [
        'data' => 'array',
        'galleries' => 'array',
        'parameters' => 'array',
    ];
    public $translatable = ['title', "description", "parameters"];

    protected $fillable = [
        'title',
        'size_id',
        'pattern_id',
		'brick_texture_file',
        'color_id',
        'category_id',
        'price',
        'stock',
        'slug',
        'description',
        'photo',
        'data',
        'galleries',
        'parameters',
        'per_piece',
        'seo_title',
        'meta_description',
        'meta_keywords',
        'status',
        'is_home',
    ];

    public function size(): BelongsTo
    {
        return $this->belongsTo(ProductSize::class, 'size_id');
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(ProductColor::class, 'color_id');
    }

    public function pattern(): BelongsTo
    {
        return $this->belongsTo(ProductPattern::class, 'pattern_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function hasCart(): bool
    {
        return \Cart::has('product' . '_' . $this->id);
    }

    public function getStockValue(): string
    {
        if ($this->stock > 500) return ProductStockEnum::STOCK_HAVE;
        return ($this->stock == 0) ? ProductStockEnum::STOCK_HAVENT : ProductStockEnum::STOCK_FEW;
    }

    public function getPerPieceLabel(): string
    {
        return ProductPriceEnum::labels()[$this->per_piece] ?? '';
    }

    public function getParameters(): array
    {
        $collection = collect($this->parameters);
        $size = ($collection->count() > 5) ? intval($collection->count()/2) + 1 : 5;
        return $collection->chunk($size)->toArray();
    }


    public function getCatalogData(Request $request, ?int $categoryId = null): array
    {

        $productQuery = Product::query()
            ->with(['pattern'])
            ->select(['id', 'title', 'slug', 'size_id', 'price', 'color_id', 'category_id', 'pattern_id', 'stock', 'galleries'])
            ->where('status', true)
            ->filter($request);

        if ($categoryId) {
            $productQuery->where('category_id', $categoryId);
        }

        $maxPrice = (clone $productQuery)->max('price');
        $products = $productQuery->paginate(12);

        return compact('maxPrice', 'products');
    }
}
