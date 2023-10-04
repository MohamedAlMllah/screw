<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function participant()
    {
        return $this->hasOne(Participant::class);
    }
    public function scores()
    {
        return $this->hasMany(Score::class);
    }
    public function hands()
    {
        return $this->hasMany(Hand::class);
    }

    public function calculateRoundScores()
    {
        $score = 0;
        foreach ($this->hands as $hand) {
            if ($hand->card->value > 10 && $hand->card->value < 14) {
                $score += 10;
            } else {
                $score += $hand->card->value;
            }
        }
        if ($score < 0) {
            return 0;
        }
        return $score;
    }
    public function totalScore()
    {
        $totalScore = 0;
        foreach ($this->scores as $score) {
            $totalScore += $score->value;
        }
        return $totalScore;
    }
}
