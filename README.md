<p align="center">
  <a href="https://travis-ci.com/jiangwu10057/php-dfa-sensitive"><img src="https://api.travis-ci.com/jiangwu10057/php-dfa-sensitive.svg?branch=master" alt="Build Status"></a>
  <a href="https://secure.php.net/"><img src="https://img.shields.io/badge/php-%3E=7.2-brightgreen.svg?maxAge=2592000" alt="Php Version"></a>
</p>

# php-DFA-filterWord


### 安装扩展 
```
    composer require guanhui07/dfa-sensitive
   ```
* 注意:如果你在使用composer安装时，出现                    
  Could not find package jiangwu10057/php-dfa-sensitive at any version for your minimum-stability (stable). Check the package spelling or your minimum-stability 请在你的composer.json中加入<code>"minimum-stability": "dev"</code>
   
#### 如果你需要手动引入

    require './vendor/autoload.php';
    
    use DfaFilter\SensitiveHelper;

### 构建敏感词库树

场景一: 可以拿到不同（用户）词库数组
```php
    // 获取感词库索引数组
    $wordData = array(
        '察象蚂',
        '拆迁灭',
        '车牌隐',
        '成人电',
        '成人卡通',
        ......
    );
    
    // get one helper
    $handle = SensitiveHelper::init()->setTree($wordData);
```
场景二: 全站使用一套敏感词库
```php
    // 获取感词库文件路径
    $wordFilePath = 'tests/data/words.txt';
    
    // get one helper
    $handle = SensitiveHelper::init()->setTreeByFile($wordFilePath);
```
### 设置干扰因子集合
> 注意只干扰因子只支持单个字符或单个汉字，暂不支持词

> 但是多个干扰因子连在一起，敏感词可以准确识别
```php
    $handle = SensitiveHelper::init()->setStopWordList(['&', '*', '.'])->setTreeByFile($wordFilePath);
```
### 忽略大小写
> 注意该设置只有在构建敏感词库树之前调用

> 在构建敏感词库树之后调用，结果可能不符合预期
```php
    $handle = SensitiveHelper::init()->setIgnoreCase()->setTree(['Av', '赌球网'])
```
### 检测是否含有敏感词
```php
    $islegal = $handle->islegal($content);
```
### 敏感词过滤
```php
// 敏感词替换为*为例（会替换为相同字符长度的*）
$filterContent = $handle->replace($content, '*', true);

 // 或敏感词替换为***为例
 $filterContent = $handle->replace($content, '***');
``` 
 ### 标记敏感词
```php
     $markedContent = $handle->mark($content, '<mark>', '</mark>');
```
### 获取文字中的敏感词
```php
    // 获取内容中所有的敏感词
    $sensitiveWordGroup = $handle->getBadWord($content);
    // 仅且获取一个敏感词
    $sensitiveWordGroup = $handle->getBadWord($content, 1);
```


