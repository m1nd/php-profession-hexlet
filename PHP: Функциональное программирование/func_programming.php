<?php
 --------------

function evenSquareSum($array)
{
    $evenNumbers = select($array, function ($item) {
        return $item % 2 == 0;
    });

    $squaredNumbers = map($evenNumbers, function ($item) {
        return $item ** 2;
    });

    return array_sum($squaredNumbers);
}


function evenSquareSum($mass) 
{
	return array_sum( array_map( function($item) { return $item*$item; }, array_filter($mass, function($item) { return $item%2 == 0; })) );
}

-------
function bestAttempt($first, $second)
{
    $result = zip($first, $second, function ($result1, $result2) {
        if ($result1['scored'] > $result2['scored']) {
            return $result1['name'];
        } else if ($result1['scored'] < $result2['scored']) {
            return $result2['name'];
        } else if ($result1['scored'] == $result2['scored']) {
            return null;
        }
    });

    $result2 = array_filter($result, function ($var) {
        return !is_null($var);
    });

    return array_values($result2);
}
--------
function wordsCount($array)
{
    $result = reduce_left($array, function ($item, $index, $collection, $acc) {
        if (!array_key_exists($item, $acc)) {
            $acc[$item] = 0;
        }
        $acc[$item]++;
        return $acc;
    }, []);

    return $result;
}



function wordsCount($words)
{
	$result = array_reduce($words, function ($acc, $item) {
			if (!array_key_exists($item, $acc)) {
				$acc[$item] = 1;	
			} else {
				$acc[$item]++;
			}
			return $acc;
	}, []);
	
	
	return $result;
}
-----------
function sortByBinary($collection)
{
    $onesCount = function ($number) {
        $binary = decbin($number);
        $bitsArray = str_split($binary);
        return sizeof(array_filter($bitsArray, function ($bit) {
            return $bit == "1";
        }));
    };

    $sorted = fsort($collection, function ($prev, $next) use ($onesCount) {
        $result = bccomp($onesCount($prev), $onesCount($next));
        if ($result === 0) {
            if ($prev > $next) {
                return 1;
            } else if ($prev < $next) {
                return -1;
            }
            return 0;
        }
        return $result;
    });

    return $sorted;
}
---------------------
function mapWithPower($array, $power)
{
    $func = partial_any('pow', …, $power);
    return map($array, $func);
}
-------------------
function separateEvenAndOddNumbers($arr)
{
    return partition($arr, function ($one) {
		return $one%2 ? false : true;
    });
}
---------------
function ages($users)
{
    $result = group($users, function ($user) {
        return getAge($user);
    });

    return flatten($result);
}
--------------






