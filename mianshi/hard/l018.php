<?php
/**
 *Valid Number 验证数字
Validate if a given string is numeric.

Some examples:
"0" => true
" 0.1 " => true
"abc" => false
"1 a" => false
"2e10" => true

Note: It is intended for the problem statement to be ambiguous. You should gather all requirements up front before implementing one.



这道验证数字的题比想象中的要复杂的多，有很多情况需要考虑，而OJ上给这道题的分类居然是Easy，Why? 而10.9% 的全场最低的Accept Rate正说明这道题的难度，网上有很多解法，有利用有限自动机Finite Automata Machine的程序写的简洁优雅 (http://blog.csdn.net/kenden23/article/details/18696083), 还有利用正则表达式，更是写的丧心病狂的简洁 (http://blog.csdn.net/fightforyourdream/article/details/12900751)。而我主要还是用最一般的写法，参考了网上另一篇博文 (http://yucoding.blogspot.com/2013/05/leetcode-question-118-valid-number.html)，处理各种情况。



首先，从题目中给的一些例子可以分析出来，我们所需要关注的除了数字以外的特殊字符有空格 ‘ ’， 小数点 '.', 自然数 'e/E', 还要加上正负号 '+/-"， 除了这些字符需要考虑意外，出现了任何其他的字符，可以马上判定不是数字。下面我们来一一分析这些出现了也可能是数字的特殊字符：

1. 空格 ‘ ’： 空格分为两种情况需要考虑，一种是出现在开头和末尾的空格，一种是出现在中间的字符。出现在开头和末尾的空格不影响数字，而一旦中间出现了空格，则立马不是数字。解决方法：预处理时去掉字符的首位空格，中间再检测到空格，则判定不是数字。

2. 小数点 '.'：小数点需要分的情况较多，首先的是小数点只能出现一次，但是小数点可以出现在任何位置，开头(".3"), 中间("1.e2"), 以及结尾("1." ), 而且需要注意的是，小数点不能出现在自然数 'e/E' 之后，如 "1e.1" false, "1e1.1" false。还有，当小数点位于末尾时，前面必须是数字，如 "1."  true，" -." false。解决方法：开头中间结尾三个位置分开讨论情况。

3. 自然数 'e/E'：自然数的前后必须有数字，即自然数不能出现在开头和结尾，如 "e" false,  ".e1" false, "3.e" false, "3.e1" true。而且小数点只能出现在自然数之前，还有就是自然数前面不能是符号，如 "+e1" false, "1+e" false. 解决方法：开头中间结尾三个位置分开讨论情况。

4. 正负号 '+/-"，正负号可以再开头出现，可以再自然数e之后出现，但不能是最后一个字符，后面得有数字，如  "+1.e+5" true。解决方法：开头中间结尾三个位置分开讨论情况。



下面我们开始正式分开头中间结尾三个位置来讨论情况：

1. 在讨论三个位置之前做预处理，去掉字符串首尾的空格，可以采用两个指针分别指向开头和结尾，遇到空格则跳过，分别指向开头结尾非空格的字符。

2. 对首字符处理，首字符只能为数字或者正负号 '+/-"，我们需要定义三个flag在标示我们是否之前检测到过小数点，自然数和正负号。首字符如为数字或正负号，则标记对应的flag，若不是，直接返回false。

3. 对中间字符的处理，中间字符会出现五种情况，数字，小数点，自然数，正负号和其他字符。

若是数字，标记flag并通过。

若是自然数，则必须是第一次出现自然数，并且前一个字符不能是正负号，而且之前一定要出现过数字，才能标记flag通过。

若是正负号，则之前的字符必须是自然数e，才能标记flag通过。

若是小数点，则必须是第一次出现小数点并且自然数没有出现过，才能标记flag通过。

若是其他，返回false。

4. 对尾字符处理，最后一个字符只能是数字或小数点，其他字符都返回false。

若是数字，返回true。

若是小数点，则必须是第一次出现小数点并且自然数没有出现过，还有前面必须是数字，才能返回true。
 */