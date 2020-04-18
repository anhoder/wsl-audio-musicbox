# Windows的WSL音频解决方案

使用pulseaudio进行音频转发，本项目主要是为了简化WSL与Windows之间通信转发的配置

> 可以实现在WSL中使用musicbox播放音乐

## 依赖

* 基于WSL2，WSL1未进行测试
* 基于ubuntu1804，其他需要修改代码
* php-cli, 因为使用到PHP脚本处理文件内容

## 原理

使用pulseaudio软件将音频数据转发到Windows上，本项目只是将繁杂的配置进行自动化处理，并可以实现开机自启动

## 使用

1. Windows下安装pulseaudio软件

2. 下载或clone本项目，将auto、etc目录移动或覆盖到pulseaudio的根目录下

3. 启动WSL，安装pulseaudio: `sudo apt install pulseaudio`，如果没有php环境，执行`sudo apt install php-cli`进行安装。**确保/etc/wsl.conf中有配置项**：`generateResolvConf = true`（如果没有，需要添加上，并**重启电脑**）

4. Windows回到pulseaudio根目录，手动运行`auto`目录下的`start.bat`，出现`成功`表明一切顺利。（**如果你在WSL中使用的不是zsh，则需要打开`auto/start.bat`文件，将ubuntu1804.exe run "./start.php `echo ~/.zshrc` && source ~/.zshrc"中的`~/.zshrc`修改为你正在使用的环境变量文件，例如`~/.bashrc`**

5. **关闭WSL并重新打开**，运行`paplay -p /mnt/c/Windows/Media/Alarm04.wav`，如果有美妙的声音播放出来，恭喜你，音频转发成功了！！

6. 如果你不想每次开机都手动执行一次`start.bat`，可以打开`auto/start.vbs`文件，将其中的`.\start.bat`替换为你电脑`start.bat`文件的**绝对路径**，并将start.vbs移动到Windows的自启动目录下（win+R运行`shell:startup`即可打开该目录）。重启试试看吧~


## 使用musicbox

> 只需要根据musicbox项目的Linux系统安装指引进行安装即可~~

