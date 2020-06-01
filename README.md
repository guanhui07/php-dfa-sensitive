<p align="center">
  <a href="https://travis-ci.com/jiangwu10057/php-dfa-sensitive"><img src="https://api.travis-ci.com/jiangwu10057/php-dfa-sensitive.svg?branch=feature-uniquebadwords" alt="Build Status"></a>
  <a href="https://secure.php.net/"><img src="https://img.shields.io/badge/php-%3E=7.2-brightgreen.svg?maxAge=2592000" alt="Php Version"></a>
</p>

# php-DFA-filterWord

php实现基于确定有穷自动机算法的铭感词过滤 https://packagist.org/packages/lustre/php-dfa-sensitive

##  安装&使用流程

### Download and install Composer:

    curl -sS https://getcomposer.org/installer | php
> 要检查 Composer 是否正常工作，只需要通过 php 来执行 PHAR
   
    php composer.phar

### 安装扩展 

    composer require lustre/php-dfa-sensitive
   
* 注意:如果你在使用composer安装时，出现                    
  Could not find package lustre/php-dfa-sensitive at any version for your minimum-stability (stable). Check the package spelling or your minimum-stability 请在你的composer.json中加入<code>"minimum-stability": "dev"</code>
   
#### 如果你需要手动引入

    require './vendor/autoload.php';
    
    use DfaFilter\SensitiveHelper;

### 构建敏感词库树

场景一: 可以拿到不同（用户）词库数组

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

场景二: 全站使用一套敏感词库

    // 获取感词库文件路径
    $wordFilePath = 'tests/data/words.txt';
    
    // get one helper
    $handle = SensitiveHelper::init()->setTreeByFile($wordFilePath);

### 设置干扰因子集合

    $handle = SensitiveHelper::init()->setStopWordList(['&', '*', '.'])->setTreeByFile($wordFilePath);

### 检测是否含有敏感词

    $islegal = $handle->islegal($content);

### 敏感词过滤
    
    // 敏感词替换为*为例（会替换为相同字符长度的*）
    $filterContent = $handle->replace($content, '*', true);
    
     // 或敏感词替换为***为例
     $filterContent = $handle->replace($content, '***');
     
 ### 标记敏感词

     $markedContent = $handle->mark($content, '<mark>', '</mark>');
    
### 获取文字中的敏感词

    // 获取内容中所有的敏感词
    $sensitiveWordGroup = $handle->getBadWord($content);
    // 仅且获取一个敏感词
    $sensitiveWordGroup = $handle->getBadWord($content, 1);

#### 使用composer自动加载php命名空间

```bash
$ composer update
```
### 运行单元测试

```bash
$ composer test
```

注：本仓库fork自：https://github.com/FireLustre/php-dfa-sensitive， 现仅做个人学习使用！<b>建议使用原仓库！</b>

本fork在原有基础上
- 新增两个特性
    - getBadWord返回的敏感词列表是排查的，避免替换敏感词的出现重复替换的问题（提过PR，没有通过所以留个记录！）
    - 新增停止词功能，提高敏感词命中率
- ci的集成