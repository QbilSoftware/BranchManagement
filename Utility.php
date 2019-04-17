<?php

namespace Qbil\BranchManagement;

class Utility
{
    const RELEASE_SEASONS = ['winter', 'voorjaar', 'spring', 'zomer', 'summer', 'najaar', 'autumn'];
    
    public static function branchToNumber($branch)
    {
        if (preg_match("'^(?:QbilTrade/|)([a-z0-9A-Z]+)([0-9]{4})$'", $branch, $matches)) {
            $number = (intval($matches[2]) - 2011) * 10;

            return $number + array_search($matches[1], self::RELEASE_SEASONS);
        }

        if ('QbilTrade/Development' == $branch) {
            return 500;
        }

        if (preg_match("'^QbilTrade/Development'", $branch)) {
            return 501;
        }

        if (preg_match("'^QbilWarehouse'", $branch)) {
            return 700;
        }

        return 600;
    }

    public static function branchFromNumber($number)
    {
        if (500 == $number) {
            return 'QbilTrade/Development';
        }

        return self::RELEASE_SEASONS[substr($number, -1)].(($number / 10) + 2011);
    }

    public static function branchFromAppVersion($version)
    {
        return self::branchFromNumber(preg_replace('/^v(\d+)@(\d+)$/', '$1', $version));
    }

    public static function revisionFromAppVersion($version)
    {
        return preg_replace('/^v(\d+)@(\d+)$/', '$2', $version);
    }


    public static function compareBranches($a, $b)
    {
        $numa = self::branchToNumber($a);
        $numb = self::branchToNumber($b);

        if ($numa < $numb) {
            return -1;
        }

        if ($numa > $numb) {
            return 1;
        }

        return 0;
    }

    public static function groupToNumber($group)
    {
        preg_match("'^([a-z]+) (.*)$'i", $group, $matches);
        $num = self::branchToNumber($matches[2]);
        switch ($matches[1]) {
            case 'Acceptatie':
                $num += 1000;
                break;
            case 'Historisch':
                $num += 2000;
                break;
        }

        return $num;
    }

    public static function compareInstallationGroups($a, $b)
    {
        $numa = self::groupToNumber($a);
        $numb = self::groupToNumber($b);

        if ($numa < $numb) {
            return -1;
        }

        if ($numa > $numb) {
            return 1;
        }

        return 0;
    }
}
