<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'contact_message_id',
        'project_description',
        'budget_range',
        'desired_due_date',
        'status',
        'quote_amount',
        'invoice_link',
    ];

    protected function casts(): array
    {
        return [
            'desired_due_date' => 'date',
            'quote_amount' => 'decimal:2',
        ];
    }

    public function contactMessage()
    {
        return $this->belongsTo(ContactMessage::class);
    }
}
