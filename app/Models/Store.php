<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'parent_id',
        'address',
        'phone',
    ];

    protected $casts = [
        'level' => 'string',
    ];

    /**
     * Get the parent store
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'parent_id');
    }

    /**
     * Get child stores
     */
    public function children(): HasMany
    {
        return $this->hasMany(Store::class, 'parent_id');
    }

    /**
     * Get users belonging to this store
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get admin user for this store
     */
    public function admin()
    {
        return $this->users()->where('role', 'admin')->first();
    }

    /**
     * Get cashier users for this store
     */
    public function cashiers(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'cashier');
    }

    /**
     * Get products belonging to this store
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get sales from this store
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get level display name
     */
    public function getLevelNameAttribute(): string
    {
        return match($this->level) {
            'pusat' => 'Toko Pusat',
            'cabang' => 'Toko Cabang',
            'retail' => 'Toko Retail',
            default => ucfirst($this->level),
        };
    }

    /**
     * Boot method to handle events
     */
    protected static function booted(): void
    {
        static::created(function (Store $store) {
            // Auto-create admin and cashier for new store
            User::create([
                'store_id' => $store->id,
                'name' => 'Admin ' . $store->name,
                'email' => 'admin.' . strtolower(str_replace(' ', '', $store->name)) . '@dazo.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]);

            User::create([
                'store_id' => $store->id,
                'name' => 'Kasir ' . $store->name,
                'email' => 'kasir.' . strtolower(str_replace(' ', '', $store->name)) . '@dazo.com',
                'password' => bcrypt('password'),
                'role' => 'cashier',
            ]);
        });
    }
}
