# shell高级变量操作
  2017年05月09日 22:28:58


## 一、内置变量

### 1.$BASH

Bash的二进制程序文件的路径（脚本解释器的路径）

    $ vim test.sh
    

输入代码：

    #!/bin/bash
    echo $BASH
    

运行代码：

    $ bash test.sh
    

### 2.$FUNCNAME

当前函数的名字

    $ vim test.sh
    

输入代码：

    #!/bin/bash
    xyz23 ()
    {
      echo "$FUNCNAME now executing."
      # 正常情况下打印: xyz23 now executing.
      # 在个别版本的bash中，并不支持$FUNCNAME内置变量
    }
    
    xyz23
    
    echo "FUNCNAME = $FUNCNAME"        # FUNCNAME =
    # 超出函数的作用域就变为null值了.
    

运行代码：

    $ bash test.sh
    

### 3.$IFS

内部域分隔符，这个变量用来决定 Bash 在解释字符串时如何识别域，或者单词边界。

$IFS默认为空白（空格，制表符，和换行符），但这是可以修改的，比如，在分析逗号分

隔的数据文件时，就可以设置为逗号，注意 $* 使用的是保存在$IFS中的第一个字符。

    $ vim test.sh
    

输入代码：

    #!/bin/bash
    # $IFS 处理空白与处理其他字符不同.
    
    output_args_one_per_line()
    {
      for arg
      do echo "[$arg]"
      done
    }
    
    echo; echo "IFS=\" \""
    echo "-------"
    
    IFS=" "
    var=" a  b c   "
    output_args_one_per_line $var  # output_args_one_per_line `echo " a  b c   "`
    #
    # [a]
    # [b]
    # [c]
    
    
    echo; echo "IFS=:"
    echo "-----"
    
    IFS=:
    var=":a::b:c:::"               # 与上边一样, 但是用" "替换了":".
    output_args_one_per_line $var
    #
    # []
    # [a]
    # []
    # [b]
    # [c]
    # []
    # []
    # []
    
    # 同样的事情也会发生在awk的"FS"域中.
    
    # 感谢, Stephane Chazelas.
    
    echo
    
    exit 0
    

 注意: 你可能对上面的for循环和变量arg有疑问，这将在下一节进行讨论。

运行代码：

    $ bash test.sh
    

### 4、$REPLY

当没有参数变量提供给 read 命令的时候，这个变量会作为默认变量提供给 read 命令。也

可以用于 select 菜单，但是只提供所选择变量的编号，而不是变量本身的值。在个别版本的bash中，并不支持内置变量$REPLY。

    $ vim test.sh
    

输入代码：

    #!/bin/bash
    # reply.sh
    
    # REPLY是提供给'read'命令的默认变量.
    
    echo
    echo -n "What is your favorite vegetable? "
    read
    
    echo "Your favorite vegetable is $REPLY."
    #  当且仅当没有变量提供给"read"命令时,
    #+ REPLY才保存最后一个"read"命令读入的值.
    
    echo
    echo -n "What is your favorite fruit? "
    read fruit
    echo "Your favorite fruit is $fruit."
    echo "but..."
    echo "Value of \$REPLY is still $REPLY."
    #  $REPLY还是保存着上一个read命令的值,
    #+ 因为变量$fruit被传入到了这个新的"read"命令中.
    
    echo
    
    exit 0
    

运行代码：

    $ bash test.sh
    

### 5.通过$*和$@列出所有的参数

"$*" 所有的位置参数都被看作为一个单词。

$@与 $* 相同，但是每个参数都是一个独立的引用字符串，这就意味着，参数是被完整

传递的，并没有被解释或扩展。这也意味着，参数列表中每个参数都被看作为单独的单词。

    $ vim test.sh
    

输入代码：

    #!/bin/bash
    # 多使用几个参数来调用这个脚本, 比如"one two three".
    
    E_BADARGS=65
    
    if [ ! -n "$1" ]
    then
    echo "Usage: `basename $0` argument1 argument2 etc."
    exit $E_BADARGS
    fi  
    
    echo
    
    index=1          # 起始计数.
    
    echo "Listing args with \"\$*\":"
    for arg in "$*"  # 如果"$*"不被""（双引号）引用,那么将不能正常地工作.
    do
    echo "Arg #$index = $arg"
    let "index+=1"
    done             # $* 将所有的参数看成一个单词.
    echo "Entire arg list seen as single word."
    
    echo
    
    index=1          # 重置计数(译者注: 从1开始).
    
    echo "Listing args with \"\$@\":"
    for arg in "$@"
    do
    echo "Arg #$index = $arg"
    let "index+=1"
    done             # $@ 把每个参数都看成是单独的单词.
    echo "Arg list seen as separate words."
    
    echo
    
    index=1          # 重置计数(译者注: 从1开始).
    
    echo "Listing args with \$* (unquoted):"
    for arg in $*
    do
    echo "Arg #$index = $arg"
    let "index+=1"
    done             # 未引用的$*将会把参数看成单独的单词.
    echo "Arg list seen as separate words."
    
    exit 0
    

运行代码：

    $ bash test.sh
    

### 二、操作字符串

### 1.字符串长度

    $ vim test.sh
    

输入代码：

    #!/bin/bash
    
    stringZ=abcABC123ABCabc
    
    echo ${#stringZ}                 # 15
    expr length $stringZ             # 15
    

运行代码：

    $ bash test.sh
    

### 2.使用awk来处理字符串

Bash脚本也可以调用awk的字符串操作功能来代替它自己内建的字符串操作。

例子：提取字符串

    $ vim test.sh
    

输入代码：

    #!/bin/bash
    
    String=23skidoo1
    #      012345678    Bash
    #      123456789    awk
    # 注意不同的字符串索引系统:
    # Bash的第一个字符是从'0'开始记录的.
    # Awk的第一个字符是从'1'开始记录的.
    
    echo ${String:2:4} # 位置 3 (0-1-2), 4 个字符长
    # skid
    
    # awk中等价于${string:pos:length}的命令是substr(string,pos,length).
    echo | awk '
    {
        print substr("'"${String}"'",3,4)      # skid
    }
    '
    #  使用一个空的"echo"通过管道传递给awk一个假的输入,
    #+ 这样就不必提供一个文件名给awk.
    
    exit 0
    

运行代码：

    $ bash test.sh
    

### 三、参数替换

### 1.处理和（或）扩展变量

${parameter} 与 $parameter 相同，也就是变量 parameter 的值。但在某些情况下，最好使用${parameter} 以避免产生混淆。

    your_id=${USER}-on-${HOSTNAME}
    echo "$your_id"
    echo "Old \$PATH = $PATH"
    PATH=${PATH}:/opt/bin  #在脚本的生命周期中, /opt/bin会被添加到$PATH变量中.
    echo "New \$PATH = $PATH"
    

${parameter-default} 如果变量 parameter 没被声明，那么就使用默认值。

${parameter:-default} 如果变量 parameter 没被设置，那么就使用默认值。

    #!/bin/bash
    # param-sub.sh
    
    #  一个变量是否被声明或设置,
    #+ 将会影响这个变量是否使用默认值,
    #+ 即使这个变量值为空(null).
    
    username0=
    echo "username0 has been declared, but is set to null."
    echo "username0 = ${username0-`whoami`}"
    # 不会有输出.
    
    echo
    
    echo username1 has not been declared.
    echo "username1 = ${username1-`whoami`}"
    # 将会输出默认值.
    
    username2=
    echo "username2 has been declared, but is set to null."
    echo "username2 = ${username2:-`whoami`}"
    #                            ^
    # 会输出, 因为:-会比-多一个条件测试.
    # 可以与上边的例子比较一下.
    

指定方式 说明 ${parameter-default} 如果变量 parameter 没被声明，那么就使用默认值。 ${parameter:-default} 如果变量 parameter 没被设置，那么就使用默认值。 ${parameter=default} 如果变量parameter没声明，那么就把它的值设为default。 ${parameter:=default} 如果变量parameter没设置，那么就把它的值设为default。 ${parameter+alt_value} 如果变量parameter被声明了，那么就使用alt_value，否则就使用null字符串。 ${parameter:+alt_value} 如果变量parameter被设置了，那么就使用alt_value，否则就使用null字符串。 ${parameter?err_msg} 如果parameter已经被声明，那么就使用设置的值，否则打印err_msg错误消息。 ${parameter:?err_msg} 如果parameter已经被设置，那么就使用设置的值，否则打印err_msg错误消息。 

    #!/bin/bash
    
    #  检查一些系统环境变量.
    #  这是一种可以做一些预防性保护措施的好习惯.
    #  比如, 如果$USER(用户在控制台上中的名字)没有被设置的话,
    #+ 那么系统就会不认你.
    
    : ${HOSTNAME?} ${USER?} ${HOME?} ${MAIL?}
    echo
    echo "Name of the machine is $HOSTNAME."
    echo "You are $USER."
    echo "Your home directory is $HOME."
    echo "Your mail INBOX is located in $MAIL."
    echo
    echo "If you are reading this message,"
    echo "critical environmental variables have been set."
    echo
    echo
    
    # ------------------------------------------------------
    
    #  ${variablename?}结构
    #+ 也能够检查脚本中变量的设置情况.
    
    ThisVariable=Value-of-ThisVariable
    #  注意, 顺便提一下,
    #+ 这个字符串变量可能会被设置一些非法字符.
    : ${ThisVariable?}
    echo "Value of ThisVariable is $ThisVariable".
    echo
    echo
    
    
    : ${ZZXy23AB?"ZZXy23AB has not been set."}
    #  如果变量ZZXy23AB没有被设置的话,
    #+ 那么这个脚本会打印一个错误信息, 然后结束.
    
    # 你可以自己指定错误消息.
    # : ${variablename?"ERROR MESSAGE"}
    
    
    # 等价于:    dummy_variable=${ZZXy23AB?}
    #            dummy_variable=${ZZXy23AB?"ZXy23AB has not been set."}
    #
    #            echo ${ZZXy23AB?} >/dev/null
    
    #  使用命令"set -u"来比较这些检查变量是否被设置的方法.
    #
    
    echo "You will not see this message, because script already terminated."
    
    HERE=0
    exit $HERE   # 不会在这里退出.
    
    # 事实上, 这个脚本将会以返回值1作为退出状态(echo $?).
    

### 2.变量长度/子串删除

${#var} 字符串长度（变量$var得字符个数）。对于array来说，${#array}表示的是数组中第一个元素的长度.。

变量长度：

    #!/bin/bash
    
    E_NO_ARGS=65
    
    if [ $# -eq 0 ]  # 这个演示脚本必须有命令行参数.
    then
    echo "Please invoke this script with one or more command-line arguments."
    exit $E_NO_ARGS
    fi  
    
    var01=abcdEFGH28ij
    echo "var01 = ${var01}"
    echo "Length of var01 = ${#var01}"
    # 现在, 让我们试试在变量中嵌入一个空格.
    var02="abcd EFGH28ij"
    echo "var02 = ${var02}"
    echo "Length of var02 = ${#var02}"
    
    echo "Number of command-line arguments passed to script = ${#@}"
    echo "Number of command-line arguments passed to script = ${#*}"
    
    exit 0
    

${var#Pattern}, ${var##Pattern} 从变量$var的开头删除最短或最长匹配$Pattern的子串。

“#”表示匹配最短，“##”表示匹配最长。

    strip_leading_zero2 () # 去掉开头可能存在的0(也可能有多个0), 因为如果不取掉的话,
    {                      # Bash就会把这个值当作8进制的值来解释.
      shopt -s extglob     # 打开扩展的通配(globbing).
      local val=${1##+(0)} # 使用局部变量, 匹配最长连续的一个或多个0.
      shopt -u extglob     # 关闭扩展的通配(globbing).
      _strip_leading_zero2=${val:-0}
      # 如果输入为0, 那么返回0来代替"".
    }
    

${var%Pattern}, ${var%%Pattern} 从变量$var的结尾删除最短或最长匹配$Pattern的子串。

“%”表示匹配最短，“%%”表示匹配最长。

### 3.变量扩展/子串替换

${var:pos} 变量var从位置pos开始扩展， 也就是pos之前的字符都丢弃。

${var:pos:len} 变量var从位置pos开始，并扩展len个字符。

${var/Pattern/Replacement} 使用Replacement来替换变量var中第一个匹配Pattern的字符串。

${var//Pattern/Replacement} 全局替换。所有在变量var匹配Pattern的字符串，都会被替换为Replacement。

    #!/bin/bash
    
    var1=abcd-1234-defg
    echo "var1 = $var1"
    
    t=${var1#*-*}
    echo "var1 (with everything, up to and including first - stripped out) = $t"
    #  t=${var1#*-}  也一样,
    #+ 因为#匹配最短的字符串,
    #+ 同时*匹配任意前缀, 包括空字符串.
    # (感谢, Stephane Chazelas, 指出这点.)
    
    t=${var1##*-*}
    echo "If var1 contains a \"-\", returns empty string...   var1 = $t"
    
    
    t=${var1%*-*}
    echo "var1 (with everything from the last - on stripped out) = $t"
    

${var/#Pattern/Replacement} 如果变量var的前缀匹配Pattern，那么就使用Replacement来替换匹配到Pattern的字符串。

${var/%Pattern/Replacement} 如果变量var的后缀匹配Pattern，那么就使用Replacement来替换匹配到Pattern的字符串。

    #!/bin/bash
    # var-match.sh:
    # 对字符串的前缀和后缀进行模式替换的一个演示.
    
    v0=abc1234zip1234abc    # 变量原始值.
    echo "v0 = $v0"         # abc1234zip1234abc
    echo
    
    # 匹配字符串的前缀(开头).
    v1=${v0/#abc/ABCDEF}    # abc1234zip1234abc
    # |-|
    echo "v1 = $v1"         # ABCDEF1234zip1234abc
    # |----|
    
    # 匹配字符串的后缀(结尾).
    v2=${v0/%abc/ABCDEF}    # abc1234zip123abc
    #              |-|
    echo "v2 = $v2"         # abc1234zip1234ABCDEF
    #               |----|
    
    echo
    
    #  ----------------------------------------------------
    #  必须匹配字符串的开头或结尾,
    #+ 否则是不会产生替换结果的.
    #  ----------------------------------------------------
    v3=${v0/#123/000}       # 匹配, 但不是在开头.
    echo "v3 = $v3"         # abc1234zip1234abc
    # 不会发生替换.
    v4=${v0/%123/000}       # 匹配, 但不是在结尾.
    echo "v4 = $v4"         # abc1234zip1234abc
    # 不会发生替换.
    
    exit 0
    ${!varprefix*}, ${!varprefix@} 匹配所有之前声明过的，并且以varprefix开头的变量。
    xyz23=whatever
    xyz24=
    
    a=${!xyz*}      # 展开所有以"xyz"开头的, 并且之前声明过的变量名.
    echo "a = $a"   # a = xyz23 xyz24
    a=${!xyz@}      # 同上.
    echo "a = $a"   # a = xyz23 xyz24
    
    # Bash, 版本2.04, 添加了这个功能.
    

## 四、指定变量的类型: 使用declare或者typeset

### 1.declare/typeset选项

选项 说明 -r 只读 -i 整型 -a 数组 -f 函数 -x export 

### 2.使用declare来指定变量的类型

    #!/bin/bash
    
    func1 ()
    {
      echo This is a function.
    }
    
    declare -f        # 列出前面定义的所有函数.
    
    echo
    
    declare -i var1   # var1是个整型变量.
    var1=2367
    echo "var1 declared as $var1"
    var1=var1+1       # 整型变量的声明并不需要使用'let'命令.
    echo "var1 incremented by 1 is $var1."
    # 尝试修改一个已经声明为整型变量的值.
    echo "Attempting to change var1 to floating point value, 2367.1."
    var1=2367.1       # 产生错误信息, 并且变量并没有被修改.
    echo "var1 is still $var1"
    
    echo
    
    declare -r var2=13.36         # 'declare'允许设置变量的属性,
    #+ 同时给变量赋值.
    echo "var2 declared as $var2" # 试图修改只读变量的值.
    var2=13.37                    # 产生错误消息, 并且从脚本退出.
    
    echo "var2 is still $var2"    # 将不会执行到这行.
    
    exit 0                        # 脚本也不会从此处退出.
    

## 五、变量的间接引用

### 1.间接引用

假设一个变量的值是第二个变量的名字。如果a=letter_of_alphabet并且letter_of_alphabet=z，

它被称为间接引用。我们能够通过引用变量a来获得z，它使用eval var1=\$$var2这种不平常的形式。

    #!/bin/bash
    # 访问一个以另一个变量内容作为名字的变量的值.(译者注: 怎么译都不顺)
    
    a=letter_of_alphabet   # 变量"a"的值是另一个变量的名字.
    letter_of_alphabet=z
    
    echo
    
    # 直接引用.
    echo "a = $a"          # a = letter_of_alphabet
    
    # 间接引用.
    eval a=\$$a
    echo "Now a = $a"      # 现在 a = z
    
    echo
    
    # 现在, 让我们试试修改第二个引用的值.
    
    t=table_cell_3
    table_cell_3=24
    echo "\"table_cell_3\" = $table_cell_3"            # "table_cell_3" = 24
    echo -n "dereferenced \"t\" = "; eval echo \$$t    # 解引用 "t" = 24
    # 在这个简单的例子中, 下面的表达式也能正常工作么(为什么?).
    #         eval t=\$$t; echo "\"t\" = $t"
    
    echo
    
    t=table_cell_3
    NEW_VAL=387
    table_cell_3=$NEW_VAL
    echo "Changing value of \"table_cell_3\" to $NEW_VAL."
    echo "\"table_cell_3\" now $table_cell_3"
    echo -n "dereferenced \"t\" now "; eval echo \$$t
    # "eval" 带有两个参数 "echo" 和 "\$$t" (与$table_cell_3等价)
    
    echo
    
    exit 0
    

### 2.传递一个间接引用给awk

    #!/bin/bash
    
    #  这是"求文件中指定列的总和"脚本的另一个版本,
    #+ 这个脚本可以计算目标文件中指定列(此列的内容必须都是数字)的所有数字的和.
    #  这个脚本使用了间接引用.
    
    ARGS=2
    E_WRONGARGS=65
    
    if [ $# -ne "$ARGS" ] # 检查命令行参数的个数是否合适.
    then
    echo "Usage: `basename $0` filename column-number"
    exit $E_WRONGARGS
    fi
    
    filename=$1
    column_number=$2
    
    #===== 在这一行上边的部分, 与原始脚本是相同的 =====#
    
    # 多行的awk脚本的调用方法为: awk ' ..... '
    
    # awk脚本开始.
    # ------------------------------------------------
    awk "
    
    { total += \$${column_number} # 间接引用
    }
    END {
      print total
    }
    
    " "$filename"
    # ------------------------------------------------
    # awk脚本结束.
    
    exit 0
    

## 六、双圆括号结构

### 1.简介

与let命令很相似，((...)) 结构允许算术扩展和赋值。

如，a=$(( 5 + 3 ))，将把变量“a”设为“5 + 3”，或者8。

### 2.C语言风格的变量操作

    #!/bin/bash
    # 使用((...))结构操作一个变量, C语言风格的变量操作.
    
    echo
    
    (( a = 23 ))  # C语言风格的变量赋值, "="两边允许有空格.
    echo "a (initial value) = $a"
    
    (( a++ ))     # C语言风格的后置自加.
    echo "a (after a++) = $a"
    
    (( a-- ))     # C语言风格的后置自减.
    echo "a (after a--) = $a"
    
    
    (( ++a ))     # C语言风格的前置自加.
    echo "a (after ++a) = $a"
    
    (( --a ))     # C语言风格的前置自减.
    echo "a (after --a) = $a"
    
    echo
    
    ########################################################
    #  注意: 就像在C语言中一样, 前置或后置自减操作
    #+ 会产生一些不同的副作用.
    
    n=1; let --n && echo "True" || echo "False"  # False
    n=1; let n-- && echo "True" || echo "False"  # True
    
    echo
    
    (( t = a<45?7:11 ))   # C语言风格的三元操作.
    echo "If a < 45, then t = 7, else t = 11."
    echo "t = $t "        # Yes!
    
    echo
    
    # 你也可以参考一些 "for" 和 "while" 循环中使用((...))结构的例子.
    
    exit 0

[0]: http://write.blog.csdn.net/postedit/71512584