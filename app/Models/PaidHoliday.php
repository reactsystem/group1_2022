<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaidHoliday extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public static function getHolidays(int $userId): int
    {
        $twoYearAgo = date("Y-m-d H:i:s", strtotime("-2 year"));
        $data = PaidHoliday::where('user_id', $userId)->where('deleted_at', null)->where('created_at', '>=', $twoYearAgo)->get();
        $holidays = 0;
        foreach ($data as $dat) {
            $holidays += $dat->amount;
        }
        return $holidays;
    }

    public static function useHolidays(int $userId, int $amount): array
    {
        $twoYearAgo = date("Y-m-d H:i:s", strtotime("-2 year"));
        $data = PaidHoliday::where('user_id', $userId)->where('deleted_at', null)->where('created_at', '>=', $twoYearAgo)->get();
        $usedHolidays = [];
        foreach ($data as $dat) {
            if ($dat->amount < $amount) {
                $usedHolidays[] = $dat->id . ":" . $dat->amount;
                $amount -= $dat->amount;
                $dat->amount = 0;
                $dat->deleted_at = new \DateTime();
                $dat->save();
                continue;
            }
            $usedHolidays[] = $dat->id . ":" . $amount;
            $dat->amount -= $amount;
            if ($dat->amount == 0) {
                $dat->deleted_at = new \DateTime();
            }
            $amount = 0;
            $dat->save();
            break;
        }
        return [$amount == 0, join(",", $usedHolidays)];
    }

    public static function revertHolidays(int $userId, string $holidaysKey)
    {
        $usedHolidays = preg_split("/,/", $holidaysKey);
        foreach ($usedHolidays as $usedHoliday) {
            $usedHolidayData = preg_split("/:/", $usedHoliday);
            $dat = PaidHoliday::find(intval($usedHolidayData[0]));
            if ($dat == null) {
                continue;
            }
            $dat->amount += intval($usedHolidayData[1]);
            $dat->deleted_at = null;
            $dat->save();
        }
    }

    public static function createHoliday(int $userId, int $amount, $addedDate)
    {
        PaidHoliday::create(['user_id' => $userId, 'amount' => $amount, 'updated_at' => $addedDate, 'created_at' => $addedDate]);
    }

}
