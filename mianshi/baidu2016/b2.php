<?php
/**
 *裁减网格纸
度度熊有一张网格纸，但是纸上有一些点过的点，每个点都在网格点上，若把网格看成一个坐标轴平行于网格线的坐标系的话，每个点可以用一对整数x，y来表示。度度熊必须沿着网格线画一个正方形，使所有点在正方形的内部或者边界。然后把这个正方形剪下来。问剪掉正方形的最小面积是多少。
输入描述:
第一行一个数n(2≤n≤1000)表示点数，接下来每行一对整数xi,yi(－1e9<=xi,yi<=1e9)表示网格上的点


输出描述:
一行输出最小面积

输入例子:
2
0 0
0 3

输出例子:
9
 */
/**
 *
 */

function deal($arr) {
    $n = count($arr);
    $narr = [];
    foreach ($arr as $k => $v) {
        foreach ($v as $ke => $va) {
            $narr[$ke][] = $v[$ke];
        }
    }
    $narr1 = $narr;
    $narr2 = $narr;
    array_multisort($narr1[0], SORT_NUMERIC, SORT_ASC);
    array_multisort($narr2[1], SORT_NUMERIC, SORT_ASC);
    $x = $narr1[0][$n - 1];
    $y = $narr2[1][$n - 1];
    $line = $x > $y ? $x : $y;
    $mianji = pow($line, 2);
    echo "面积==", $mianji;
    // var_dump($narr);
    // var_dump($narr1);
    // var_dump($narr2);
}

deal([[0, 0], [0, 3]]);