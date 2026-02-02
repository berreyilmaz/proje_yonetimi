<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * Veritabanına toplu olarak kaydedilebilecek alanlar.
     * Formdan gelen 'title', 'event_date' ve 'color' alanlarını buraya ekliyoruz.
     */
    protected $fillable = [
        'title',
        'event_date',
        'color',
        'user_id' // Eğer etkinlikleri kullanıcıya özel yapacaksan bunu da ekle
    ];

    /**
     * Verilerin otomatik olarak tür dönüşümü.
     * 'event_date' alanını otomatik olarak tarih nesnesine çevirir,
     * böylece ->format('d/m/Y') gibi özellikleri kullanabilirsin.
     */
    protected $casts = [
        'event_date' => 'date',
    ];

    /**
     * Etkinliği oluşturan kullanıcı ile ilişki (Opsiyonel)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}