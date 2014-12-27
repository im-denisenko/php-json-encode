<?php
namespace Future;

class JsonTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function unescapeUnicode()
    {
        $data = array(
            "english",
            "русский",
            "čeština",
        );
        $result = file_get_contents(__DIR__.'/assert_1.json');
        $this->assertEquals($result, Json::encode($data, JSON_UNESCAPED_UNICODE));
    }

    /**
     * @test
     */
    public function prettyPrint()
    {
        $data = array('one' => 'two');
        $result = file_get_contents(__DIR__.'/assert_2.json');
        $this->assertEquals($result, Json::encode($data, JSON_PRETTY_PRINT));

        $data = array(
            'one' => array(
                'two' => array(
                    'three' => array(
                        'four' => array(
                            'five' => array(
                                'six' => array(
                                    1,2,3,4,5,
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        );
        $result = file_get_contents(__DIR__.'/assert_3.json');
        $this->assertEquals($result, Json::encode($data, JSON_PRETTY_PRINT));

        $data = array(
            'one' => 'Here is "quotes" inside string',
            'two' => 'Here is } inside string',
            'three' => 'Here is { inside string',
            'four' => 'Here is ] inside string',
            'five' => 'Here is [ inside string',
            'six' => 'Here is , inside string',
            'seven' => 'Here is \"quote and } and { and ] inside string',
        );
        $result = file_get_contents(__DIR__.'/assert_4.json');
        $this->assertEquals($result, Json::encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * @test
     */
    public function unescapeSlashes()
    {
        $data = array(
            'foo/',
            'foo/////',
            '/foo',
            '/////foo',
            'f/o/o',
            'foo \t bar',
            'foo \t bar',
        );
        $result = file_get_contents(__DIR__.'/assert_5.json');
        $this->assertEquals($result, Json::encode($data, JSON_UNESCAPED_SLASHES));
    }

    /**
     * @test
     */
    public function numericCheck()
    {
        $data = array(
            1234567,
            '1234567',
            '1234567foo',
            'foo"1234567"bar'
        );
        $result = file_get_contents(__DIR__.'/assert_6.json');
        $this->assertEquals($result, Json::encode($data, JSON_NUMERIC_CHECK));
    }

    /**
     * @test
     */
    public function depth()
    {
        $data = array(
            'one' => array(
                'two' => array(
                    'three' => array(
                        1,2,3,4,5
                    ),
                ),
            ),
        );
        $this->assertFalse(Json::encode($data, null, 1));
        $this->assertFalse(Json::encode($data, null, 2));
        $this->assertFalse(Json::encode($data, null, 3));
        $this->assertFalse(false === Json::encode($data, null, 4));
        $this->assertFalse(false === Json::encode($data, null, 5));
        $this->assertFalse(false === Json::encode($data));

    }
}
