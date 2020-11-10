<?php
/**
 * User: chenwu
 * Date: 20-02-17
 */

use DfaFilter\SensitiveHelper;
use PHPUnit\Framework\TestCase;

class StopTest extends TestCase
{
    protected $wordPoolPath;

    public function setUp()
    {
        parent::setUp();

        // 铭感词文件路径
        $this->wordPoolPath = 'tests/data/words.txt';
    }

    /**
     * @expectedException DfaFilter\Exceptions\PdsBusinessException
     * @expectedExceptionMessage 干扰因子词库不存在
     */
    public function testStopWordList()
    {
        SensitiveHelper::init()
            ->setStopWordList();
    }

    public function testGetBadWord()
    {
        $sTime = microtime(true);
        $content = '这是一段测试语句，请忽略赌&球.网, 第二个敏感词是三..级片';

        // 过滤,其中【赌球网】在词库中
        $filterContent = SensitiveHelper::init()
            ->setStopWordList(['&', '.'])
            ->setTreeByFile($this->wordPoolPath)
            ->getBadWord($content);

        // 返回规定数量的敏感词,其中【赌球网,三级片】在词库中
        $badWords = SensitiveHelper::init()
            ->setStopWordList(['&', '.'])
            ->setTreeByFile($this->wordPoolPath)
            ->getBadWord($content, 1, 2);

        $eTime = microtime(true);

        echo ($eTime - $sTime) * 1000 . 'ms' . PHP_EOL;

        $this->assertEquals('赌&球.网', $filterContent[0]);
        $this->assertEquals('三..级片', $badWords[1]);
    }

    public function testFilterWord()
    {
        $content = '这是一段测试语句，请忽略赌&球网';

        // 过滤,其中【赌球网】在词库中
        $filterContent = SensitiveHelper::init()
            ->setStopWordList(['&', '.'])
            ->setTreeByFile($this->wordPoolPath)
            ->replace($content,'*');

        $this->assertEquals('这是一段测试语句，请忽略*', $filterContent);


        // 过滤,其中【赌球网】在词库中
        $filterContent = SensitiveHelper::init()
            ->setStopWordList(['&', '.'])
            ->setTreeByFile($this->wordPoolPath)
            ->replace($content,'*', true);

        $this->assertEquals('这是一段测试语句，请忽略****', $filterContent);
    }

    public function testMarkWord()
    {
        $content = '这是一段测试语句，请忽略赌&球网';

        // 过滤,其中【赌球网】在词库中
        $start = memory_get_usage();
        $sTime = microtime(true);
        $markedContent = SensitiveHelper::init()
            ->setStopWordList(['&', '.'])
            ->setTreeByFile($this->wordPoolPath)
            ->mark($content,'<mark>', '</mark>');
        $end = memory_get_usage();
        $eTime = microtime(true);
        echo 'normal:'. PHP_EOL;
        echo ($eTime - $sTime) * 1000 . 'ms' . PHP_EOL;
        echo ($end - $start) / 8 .'B'.PHP_EOL;
        $this->assertEquals('这是一段测试语句，请忽略<mark>赌&球网</mark>', $markedContent);
    }

    public function testEng()
    {
        $content = '这是一段测LY试语renq.uan句，请忽略赌&球网';

        // 过滤,其中【赌球网】在词库中
        $start = memory_get_usage();
        $sTime = microtime(true);
        $markedContent = SensitiveHelper::init()
            ->setStopWordList(['&', '.'])
            ->setTreeByFile('tests/data/max.txt')
            ->mark($content,'<mark>', '</mark>');
        $end = memory_get_usage();
        $eTime = microtime(true);
        echo 'max eng:'. PHP_EOL;
        echo ($eTime - $sTime) * 1000 . 'ms' . PHP_EOL;
        echo ($end - $start) / 8/1024/1024 .'KB'.PHP_EOL;
        $this->assertEquals('这是一段测<mark>LY</mark>试语<mark>renq.uan</mark>句，请忽略<mark>赌&球</mark>网', $markedContent);
    }

    public function testAr()
    {
        $content = '视频内容：20世纪初，人们发现了一种比细菌还要小的病原体：病毒。病毒由蛋白质外壳和遗传物质组成，必须寄生在细胞里才能表现出生命特征。病毒可以一次次欺骗细胞，最终将自己的遗传物质送到寄主细胞核里。病毒可以在细胞中潜伏很久，在某个时刻突然爆发，寄主就会死亡。人们已经发现了七种冠状病毒，其中有三种可以引起严重疾病，即SARS、MERS和新型冠状病毒，它们都会引起免疫系统的强烈反应，产生细胞因子风暴，大量的白细胞和组织液充斥肺部，造成呼吸困难和死亡。免疫反应既不能太弱，也不能太强，平衡的免疫力才是最好的免疫力。面对病毒，人们的反应与免疫系统一样，毫不在意或者过分恐慌都是不对的，平静的等一段时间，疫情一定会被消灭。这是一段测试الكود语句，请忽略赌&球网';
        $start = memory_get_usage();
        $sTime = microtime(true);
        $markedContent = SensitiveHelper::init()
            ->setStopWordList(['&', '.'])
            ->setTreeByFile('tests/data/max.txt')
            ->mark($content,'<mark>', '</mark>');
        $end = memory_get_usage();
        $eTime = microtime(true);
        echo 'max ar:'. PHP_EOL;
        echo ($eTime - $sTime) * 1000 . 'ms' . PHP_EOL;
        echo ($end - $start) / 8/1024 .'KB'.PHP_EOL;
        $this->assertEquals('<mark>视频</mark>内容：20世纪初，人们发现了一种比细菌还要小<mark>的</mark>病原体：病毒。病毒由蛋白质外壳和遗传物质组成，必须寄生在细胞里才能表现出生命特征。病毒可以一次次欺骗细胞，最终将自己<mark>的</mark>遗传物质送到寄主细胞核里。病毒可以在细胞中潜伏很久，在某个时刻突然爆发，寄主就会<mark>死</mark>亡。人们已经发现了七种冠状病毒，其中有三种可以引起严重疾病，即<mark>SARS</mark>、MERS和新型冠状病毒，它们都会引起免疫<mark>系统</mark><mark>的</mark>强烈反应，产生细胞因子风暴，大量<mark>的</mark>白细胞和组织液充斥肺部，造成呼吸困难和<mark>死</mark>亡。免疫反应既不能太弱，也不能太强，平衡<mark>的</mark>免疫力才是最好<mark>的</mark>免疫力。面对病毒，人们<mark>的</mark>反应与免疫<mark>系统</mark>一样，毫不在意或者过分恐慌都是不对<mark>的</mark>，平静<mark>的</mark>等一段时间，疫情一定会被消灭。这是一段<mark>测试</mark><mark>الكود</mark><mark>语句</mark>，请忽略<mark>赌&球</mark>网', $markedContent);
    }
}
