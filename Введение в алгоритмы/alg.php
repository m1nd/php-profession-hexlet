<?php
 --------------

    $n = sizeof($arr);
    for ($i = 1; $i < $n; $i++) {
        for ($j = $n - 1; $j >= $i; $j--) {
            if ($arr[$j-1] > $arr[$j]) {
                $tmp = $arr[$j - 1];
                $arr[$j - 1] = $arr[$j];
                $arr[$j] = $tmp;
            }
        }
    }

    return $arr;
    -------------
function mergeSort(array $arr)
{
    if(count($arr) <= 1)
    {
    	return $arr;
    } else {
    	$q = (int) (count($arr)/2);
    	return merge(mergeSort(array_slice($arr, 0, $q)), mergeSort(array_slice($arr, $q)));
    }
}

function merge($left, $right)
{
    $result = array();

    while (count($left) > 0 && count($right) > 0) {
        if ($left[0] <= $right[0]) {
            array_push($result, array_shift($left));
        } else {
            array_push($result, array_shift($right));
        }
    }

    array_splice($result, count($result), 0, $left);
    array_splice($result, count($result), 0, $right);

    return $result;
}
----------
 function merge($left, $right)
{
    $iter = function ($current, $another, $result) use (&$iter) {
        if (sizeof($current) == 0) {
            return array_merge($result, $another);
        } else if (sizeof($another) == 0) {
            return array_merge($result, $current);
        }

        list($newCurrent, $newAnother) = $current[0] >= $another[0] ?
            [$another, $current] : [$current, $another];
        return $iter(array_slice($newCurrent, 1), $newAnother, array_merge($result, [$newCurrent[0]]));
    };
    return $iter($first, $second, []);
}
------------
function sortGraph(array $graph)
{
$add = function ($acc, $node) use ($graph, &$add) {
        return array_merge(
            $acc,
            isset($graph[$node]) ? array_reduce($graph[$node], $add, $acc) : [],
            [$node => true]
        );
    };

    return array_keys(array_reduce(array_keys($graph), $add, []));

}
----------
function isCircular($node)
{
    $node2 = getNext($node);

    $iterNode = function ($node, $node2) use (&$iterNode){
         if ($node2 == $node) {
             print("true");
             return true;
         }

         if ( $node2 == null ) {
             print("false");
              return false;
          }

         return $iterNode(getNext($node), getNext( getNext($node2)) );
    };

    return $iterNode($node, $node2);
 }
-------
function findIndex(array $tree, $element)
{
	if ( $tree == [] || is_null($element) ) {
		return null;
	}
	
	$n = count($tree);
	
	$iterTree = function ($node) use (&$iterTree, $tree, $element, $n) {
		
		if ($node >= $n) {
			return null;
		}
		
		if ($element == $tree[$node]) {
			return $node;
		}
		
		if ($element < $tree[$node]) {
			$node = 2*$node+1;
		} else {
			$node = 2*$node+2;
		}
		
		return $iterTree($node);
	};
	
	return $iterTree(0);
}

 echo findIndex([13, 10, 28, 6, 11, 21, 33], 33);
 -------
 








