#!/bin/bash

echo -e  "\033[32m" # 设置输出属性，绿色字体
echo "This is a test!"
echo -e  "\033[0m" # 设置输出属性，恢复默认值

echo -e "\033[31m Hello Color! \033[0m" # 输出指定颜色字体
# echo -e ：激活终端对反斜线转义符（\）的解释
# \033 ：引导非常规字符序列（这里是引导设置输出属性）
# [31m ：设置前景色（字体）为红色，字母m表示设置的属性类别，数字代表属性值
# Hello Color!”：输出的字符
# \033[0m ：恢复属性为默认值

echo -e '\033[44;37;5m Color \033[0m Cool !' # 设置多个输出属性
# \033[44;37;5m ：44背景蓝色，37字体白色，5字体闪烁
# \033[0m ：恢复属性为默认值

echo -e "\033[2J\033[6;18H\033[1;4;32m Thanks,Color! \033[0m" # 设置更多输出属性
# \033[2J : 清除屏幕
# \033[6;18H ：光标移动到终端第6行第18列
# \033[1;4;32m ：字体高亮、带下划线且颜色为绿色
# \033[0m ：恢复属性为默认值





#   ### 设置输出属性
#   - 利用echo命令和非常规字符序列，可以设置输出属性，实现特定的输出形式；
#   - 只对当前shell生效；如果想永久生效，需要在“.bash_profile”或“.bashrc”等文件中添加相关配置；

#   - 可以同时设置多个输出属性，并且不区分属性顺序；
#   - 对于某些特殊字符，需要注意单引号和双引号的使用区别；
#   
#   
#   ### 常用属性
#   - \033[0m 缺省设置
#   - \033[1m 高亮
#   - \033[4m 下划线
#   - \033[5m 闪烁
#   - \033[7m 反向显示（前景色和背景色）
#   - \033[8m 隐藏显示
#   - \033[24m 关闭下划线
#   - \033[25m 关闭闪烁
#   - \033[27m 关闭反向显示
#   - \033[30m 至 \33[37m 设置前景色(30黑 31红 32绿 33黄 34蓝 35紫 36青 37白)
#   - \033[40m 至 \33[47m 设置背景色(40黑 41红 42绿 43黄 44青 45蓝 46青 47白)
#   - \033[49m 设置缺省黑色背景
#   
#   
#   ### ANSI ESCAPE CODE
#   - ANSI控制码（ANSI ESCAPE CODE/SEQUENCES）用于在字符显示系统中控制输出属性；
#   - ANSI控制码开始的标志都为ESC[，ESC对应ASCII码表的033(八进制);
#   - Linux命令echo用“-e”选项启用转义“\e”或“\033”来输出Esc符号，“\033[31m”即为“ESC[31m”；
#   - 更多信息：https://en.wikipedia.org/wiki/ANSI_escape_code