<?php

namespace Application\API\Utils {
    
    use Application\API\Canonicals\General\Constants,
        Application\API\Canonicals\General\HijriDay;
    
    class GeneralUtils {
        
        public static function shortenText($str, $maxWords=10) {
            $i=0;
            $newStr="";

            foreach(preg_split("/\s+/",$str) as $word) {
                if(++$i<$maxWords) {
                    $newStr.="$word ";
                } else {
                    $word = preg_replace("/\.$/i","",$word);
                    $newStr.="$word ....";
                    return $newStr;
                }
            }
            return $newStr;
        }
        
        public static function nextDayIsDaylightSavings(\DateTime $day) {
            $thisDay = new \DateTime($day->format("Y-m-d"), new \DateTimeZone(Constants::TZ));
            $nextDay = new \DateTime($day->format("Y-m-d"), new \DateTimeZone(Constants::TZ));
            
            return $thisDay->format("I") != $nextDay->add(new \DateInterval("P1D"))->format("I");
        }
        
        public static function convertToHijri(\DateTime $day) {
            
            $y = $day->format("Y");
            $m = $day->format("m");
            $d = $day->format("d");
            
            if ($y>1582 || (($y==1582)&&($m>10)) || (($y==1582)&&($m==10)&&($d>14))) {
                $jd = (int)((1461*($y + 4800 + (int)(($m - 14)/12)))/4) +
                      (int)((367*($m - 2 - 12*((int)(($m - 14)/12))))/12) - 
                      (int)((3*((int)(($y + 4900 + (int)(($m - 14)/12))/100)))/4) + $d - 32075;
            } else {
                $jd = 367*$y - (int)((7*($y + 5001 + (int)(($m - 9)/7)))/4) + (int)((275*$m)/9) + $d + 1729777;
            }
            
            $l=$jd-1948440+10632;
            $n=(int)(($l-1)/10631);
            $l=$l-10631*$n+354;
            $j=((int)((10985-$l)/5316))*((int)((50*$l)/17719)) + ((int)($l/5670))*((int)((43*$l)/15238));
            $l=$l-((int)((30-$j)/15))*((int)((17719*$j)/50)) - ((int)($j/16))*((int)((15238*$j)/43)) + 29;
            
            $m=(int)((24*$l)/709);
            $d=$l-(int)((709*$m)/24);
            $y=30*$n + $j - 30;

            switch ($m) {
                case 1: $mn  = "Muharram"; break;
                case 2: $mn  = "Safar"; break;
                case 3: $mn  = "Rabi I"; break;
                case 4: $mn  = "Rabi II"; break;
                case 5: $mn  = "Jumada I"; break;
                case 6: $mn  = "Jumada II"; break;
                case 7: $mn  = "Rajab"; break;
                case 8: $mn  = "Shaban"; break;
                case 9: $mn  = "Ramadhan"; break;
                case 10: $mn = "Shawwal"; break;
                case 11: $mn = "Dhul al-Qada"; break;
                default: $mn = "Dhul al-Hijja"; break;
            }
            
            $hijriDay = new HijriDay();
            $hijriDay->setNumericWeekDay($day->format("N"));
            $hijriDay->setYear($y);
            $hijriDay->setMonth($m);
            $hijriDay->setMonthName($mn);
            $hijriDay->setDay($d);
            
            return $hijriDay;
        }        
    }
}
