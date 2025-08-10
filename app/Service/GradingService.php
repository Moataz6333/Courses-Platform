<?php
namespace App\Service;

 class GradingService 
{
   public function grade($total, $correct)
    {

        $percentage = ($correct / $total) * 100;
        $grade = '';

        if ($percentage >= 90) {
            $grade = 'A';
        } elseif ($percentage >= 85) {
            $grade = 'A-';
        } elseif ($percentage >= 80) {
            $grade = 'B+';
        } elseif ($percentage >= 75) {
            $grade = 'B';
        } elseif ($percentage >= 70) {
            $grade = 'B-';
        } elseif ($percentage >= 65) {
            $grade = 'C+';
        } elseif ($percentage >= 60) {
            $grade = 'C';
        } elseif ($percentage >= 55) {
            $grade = 'C-';
        } else {
            $grade = 'F';
        }
        return $grade;
    }
    
}
