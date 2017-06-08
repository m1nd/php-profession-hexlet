<?php
 --------------

function sum($start, $finish, $func)
{
    // BEGIN (write your solution here)
       if($start > $finish) {
		return 0;
	}
	
	$sumIter = function ($start, $acc) use (&$sumIter, &$finish, &$func) {
		if($start > $finish) {
			return $acc;
		}	
		
		return $sumIter($start+1, $acc+$func($start)); 
	};
	
	return $sumIter($start, 0, $func);
    // END
}
------------------
function product($num1, $num2, $func)
{
    // BEGIN (write your solution here)
 	if($num2 == 1) {
 		return 1;
 	}
 	
 	
 	$prodIter = function ($num1, $acc) use (&$prodIter, &$num2, &$func) {

 		if($num1 >= $num2) {
			return $acc;
		}
		return $prodIter($num1+1, $func($acc, $num1+1));	
 	};
 	
 	return $prodIter($num1, $num1);
    // END
}
------------------------
function product($num1, $num2, $func)
{
    // BEGIN
    if ($num1 == $num2) {
        return $num2;
    }
    return $func(product($num1, $num2 - 1, $func), $num2);
    // END
}
----------------
function factor($multiplier)
{
    // BEGIN (write your solution here)
    	return function($a) use ($multiplier) {
		return $a * $multiplier;
	};
    // END
}
------------------
function double($func)
{
        return function($a) use ($func) {
		return $func($func($a));
    };
}
 -------------------
 function cons($x, $y)
{
    return function ($func) use ($x, $y) {
        return $func($x, $y);
    };
}

function car(callable $pair)
{
    // BEGIN (write your solution here)
    return $pair(function($x, $y) { return $x; } );
    // END
}

function cdr(callable $pair)
{
    // BEGIN (write your solution here)
    return $pair(function($x, $y) { return $y; } );
    // END
}
----------------------------
function subRat($rat1, $rat2)
{
    $numer = numer($rat1) * denom($rat2) - numer($rat2) * denom($rat1);
    $denom = denom($rat1) * denom($rat2);

    return makeRat($numer, $denom);
}

function equalRat($rat1, $rat2)
{
    return numer($rat1) * denom($rat2) == numer($rat2) * denom($rat1);
}
-------------
Append.php
 
    if ($list1 === null) {
        return $list2;
    } else {
        return cons(car($list1), append(cdr($list1), $list2));
    }
---------
Length.php

    if ($items === null) {
        return 0;
    } else {
        return 1 + length(cdr($items));
    }
 ----------
  $iter = function ($items, $acc) use (&$iter) {
        if ($items === null) {
            return $acc;
        } else {
            return $iter(cdr($items), cons(car($items), $acc));
        }
    };

    return $iter($list, null);
-----------
$map = function ($func, $list) use (&$map) {
	if ($list == null) {
		return null;
	} else {
		$rest = $map($func, cdr($list));
		return cons($func(car($list)), $rest);
	}
	
};


function map($func, $list)
{
	$mapIter = function ($list, $acc) use (&$mapIter, &$func) {
		if ($list === null) {
            return reverse($acc);
        } else {
			return $mapIter(cdr($list), cons($func(car($list)), $acc));
		}
	};
	
 	return $mapIter($list, null);
}
 ----------------
 function filter($func, $list)
{
    $iter = function ($list, $acc) use (&$iter, $func) {
        if ($list === null) {
            return reverse($acc);
        }

        $newAcc = $func(car($list)) ? cons(car($list), $acc) : $acc;
        return $iter(cdr($list), $newAcc);
    };
    return $iter($list, null);
}
-------------------
function solution($list)
{
    $r1 = map($list, function ($item) {
        return ceil($item);
    });

    $r2 = filter($r1, function ($item) {
        return $item % 2 === 0;
    });

    $r3 = accumulate($r2, function ($item, $acc) {
        return $acc * $item;
    }, 1);

    return $r3;
}

function solution($source)
{
	$funcCeil = function ($item) { return ceil($item); };
	$funcFilter = function ($item) { if ($item%2 == 0) { return $item; } };
	$funcMul = function ($item, $acc) { return $item * $acc; };
	return accumulate(filter(map($source, $funcCeil),$funcFilter), $funcMul, 1);
}
---------------
function reverse($list)
{
    $iter = function ($items, $acc) use (&$iter) {
        if ($items === null) {
            return $acc;
        } else {
            return $iter(cdr($items), cons(car($items), $acc));
        }
    };

    return $iter($list, null);
}


    $iter = function ($items, $acc) use (&$iter) {
        if ($items === null) {
            return $acc;
        } else {
            $element = car($items);
            if (isPair($element)) {
                $result = reverse($element);
            } else {
                $result = $element;
            }
            return $iter(cdr($items), cons($result, $acc));
        }
    };

    return $iter($list, null);
 ---------------
function treeMap($list, $func, $acc)
{
	$iter = function ($list, $acc) use (&$iter, $func) {
		if ($list === null) {
			return $acc;
		}
		
		$element = car($list);
		if (isPair($element)) {
			$newAcc = treeMap($element, $func, $acc);
		} else {
			$newAcc = $func($element, $acc);
		}
		return $iter(cdr($list), $newAcc);
	};
	return $iter($list, $acc);
}

$list = l(1, l(3, 2), 5, l(6, l(5, 4)));
$result = treeMap($list, function ($item, $acc) {return $acc + 1;}, 0);
print_r($result);
------------------
function solution($list)
{
    $result = filter($list, function ($item) {
        return $item % 3 === 0;
    });

    $result2 = map($result, function ($item) {
        return $item ** 2;
    });

    $result3 = accumulate($result2, function ($item, $acc) {
        return $acc + $item;
    }, 0);

    return $result3 / length($result2);
}

function solution($source) 
{
	
	$funcFilter = function ($item) { if ($item%3 === 0) { return $item; } };
	$funcSq = function ($item) { return $item * $item; };
	$funcAvg = function ($item, $acc) { return $item + $acc; };
	$fil = filter($source, $funcFilter);
	return accumulate(map($fil, $funcSq), $funcAvg, 0) / length($fil);

}
---------
function newWithdraw($balance) 
{
	return function ($draw) use (&$balance) 
	{
		if ($draw > $balance) {
			return "too much";
		} 
		return $balance -= $draw;
	};
}
------------
    $checkPass = function ($pass) use (&$password) {
    	return $pass === $password ? true : false;
    };
    

	return function ($funcName, $amount, $pass) use ($withdraw, $deposit, $checkPass) {
		if ($checkPass($pass)) {
			switch ($funcName) {
				case "withdraw":
					return $withdraw($amount);
					break;
				case "deposit":
					return $deposit($amount);
					break;
			}
		} else {
			return "wrong password!";
		}
	};
-----------
function random($seed)
{
    $init = $seed;
    return function ($method = null) use (&$seed, $init) {
        $a = 45;
        $c = 21;
        $m = 67;

        switch ($method) {
            case "reset":
                $seed = $init;
                break;
            default:
                $seed = ($a * $seed + $c) % $m;
                break;
        }

        return $seed;
    };
}
-----------





 
 
 