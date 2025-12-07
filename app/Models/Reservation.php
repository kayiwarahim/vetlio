<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use App\Observers\ReservationObserver;
use App\Traits\AddedByCurrentUser;
use App\Traits\Organisationable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([ReservationObserver::class])]
class Reservation extends Model
{
    use HasFactory, SoftDeletes, Organisationable, AddedByCurrentUser;

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'client_id',
        'patient_id',
        'status_id',
        'branch_id',
        'note',
        'service_provider_id',
        'user_id',
        'reason_for_coming',
        'room_id',
        'service_id',
        'canceled_at',
        'cancel_reason_id',
        'waiting_room_at',
        'in_process_at',
        'completed_at',
        'confirmed_status_id',
        'confirmed_at',
        'confirmed_note',
        'organisation_id',
    ];

    protected $casts = [
        'status_id' => ReservationStatus::class,
        'date' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'canceled_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'waiting_room_at' => 'datetime',
        'in_process_at' => 'datetime',
        'completed_at' => 'datetime',
        'confirmed_status_id' => 'boolean',
    ];

    #[Scope]
    public function canceled(Builder $query, $canceled = true): void
    {
        $query->when(
            $canceled,
            fn($q) => $q->whereNotNull('canceled_at'),
            fn($q) => $q->whereNull('canceled_at'),
        );
    }

    #[Scope]
    public function confirmed(Builder $query, $confirmed = true): void
    {
        $query->when(
            $confirmed,
            fn($q) => $q->whereNotNull('confirmed_at'),
            fn($q) => $q->whereNull('confirmed_at'),
        );
    }

    #[Scope]
    public function ordered(Builder $query): void
    {
        $query->where('status_id', ReservationStatus::Ordered->value);
    }

    public function isCanceled(): Attribute
    {
        return Attribute::make(fn () => $this->canceled_at !== null);
    }

    public function cancelReason(): BelongsTo
    {
        return $this->belongsTo(CancelReason::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'related')->latest();
    }

    public function serviceProvider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'service_provider_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function reservationReminders(): HasMany
    {
        return $this->hasMany(ReservationReminder::class, 'reservation_id');
    }
}
