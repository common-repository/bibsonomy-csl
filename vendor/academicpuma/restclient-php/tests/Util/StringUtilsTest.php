<?php

namespace AcademicPuma\RestClient\Util;

use PHPUnit\Framework\TestCase;


class StringUtilsTest extends TestCase
{

    const TEST_VALUE1 = "test";
    const TEST_VALUE2 = "hurz";
    const SPECIAL_CHARS = "üöä!\"§$%&/()=,.-+#'´`";

    /**
     * @var StringUtils
     */
    protected $object;

    /**
     * @covers AcademicPuma\RestClient\Util\StringUtils::removeNonNumbersOrLetters
     */
    public function testRemoveNonNumbersOrLetters()
    {
        $this->assertEquals(self::TEST_VALUE1, StringUtils::removeNonNumbersOrLetters("!-test-!"));
        $this->assertEquals(self::TEST_VALUE1, StringUtils::removeNonNumbersOrLetters(" !-test-! "));
    }

    /**
     * @covers AcademicPuma\RestClient\Util\StringUtils::removeNonNumbers
     */
    public function testRemoveNonNumbers()
    {
        $strings = ["123test", "test123", "t1e2s3t"];

        foreach ($strings as $str) {
            $this->assertEquals("123", StringUtils::removeNonNumbers($str));
        }
    }

    /**
     * @covers AcademicPuma\RestClient\Util\StringUtils::getStringFromList
     */
    public function testGetStringFromList()
    {
        $res = StringUtils::getStringFromList(['item1', 'item2', 'item3']);
        $this->assertEquals('[item1,item2,item3]', $res);
    }

    /**
     * @covers AcademicPuma\RestClient\Util\StringUtils::removeNonNumbersOrLettersOrDotsOrCommaOrSpace
     */
    public function testRemoveNonNumbersOrLettersOrDotsOrCommaOrSpace()
    {

        $res = StringUtils::removeNonNumbersOrLettersOrDotsOrCommaOrSpace(",...!-test-!...,");
        $this->assertEquals(",...test...,", $res);

        $res = StringUtils::removeNonNumbersOrLettersOrDotsOrCommaOrSpace(",.# .' .*!-test1-!/.& .% .$,!");
        $this->assertEquals(",. . .test1. . .,", $res);
    }

    /**
     * @covers AcademicPuma\RestClient\Util\StringUtils::removeNonLettersOrDotsOrCommaOrSemicolonOrSpace
     */
    public function testRemoveNonLettersOrDotsOrCommaOrSemicolonOrSpace()
    {
        $res = StringUtils::removeNonLettersOrDotsOrCommaOrSemicolonOrSpace(",...!-test-!...,");
        $this->assertEquals(",...test...,", $res);

        $res = StringUtils::removeNonLettersOrDotsOrCommaOrSemicolonOrSpace(";,.# .' .*!-test1-!/.& .% .$,!;");
        $this->assertEquals(";,. . .test. . .,;", $res);
    }

    /**
     * @covers AcademicPuma\RestClient\Util\StringUtils::cleanTitle
     */
    public function testCleanTitle()
    {

        $res = StringUtils::cleanTitle('A Title  with    many Space   Characters.   ');
        $this->assertEquals('A Title with many Space Characters.', $res);
    }

    /**
     * @covers AcademicPuma\RestClient\Util\StringUtils::cleanTitle2
     */
    public function testCleanTitle2()
    {
        $res = StringUtils::cleanTitle2('<b>A</b> Title  with many Space   Characters and <bold>tags</bold>.   ');
        $this->assertEquals('A Title with many Space Characters and tags.', $res);

    }

    /**
     * @covers AcademicPuma\RestClient\Util\StringUtils::split
     */
    public function testSplit()
    {
        $res = StringUtils::split('a,b,c', '!,!');
        $this->assertEquals(['a', 'b', 'c'], $res);
    }

    /**
     * @covers AcademicPuma\RestClient\Util\StringUtils::extractDateYearFromTitleSource
     */
    public function testExtractDateYearFromTitleSource()
    {

        $res = StringUtils::extractDateYearFromTitleSource('Ein Buch mit einem Datum wie APR 12, 1562 im Titel, soll es geben.');
        $this->assertEquals('1562', $res);

        $res = StringUtils::extractDateYearFromTitleSource('NOV 30, 1276');
        $this->assertEquals('1276', $res);
    }

    public function testToASCII()
    {
        $this->assertEquals('muller osas', StringUtils::toASCII('müller ösäß'));
        $this->assertEquals('norway', StringUtils::toASCII('nørwåy'));
        $this->assertEquals('SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy', StringUtils::toASCII('ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'));
    }

    /**
     * Test cases are all taken from stackoverflow questions where people wanted to have symbols with diacritic signs
     * converted to default ASCII symbols.
     */
    public function testTransliterateString()
    {
        $this->assertEquals('Duranova', StringUtils::transliterateString("Ďuranová"));
        $this->assertEquals('Sebastien', StringUtils::transliterateString("Sébastien"));
        $this->assertEquals('eric cantona', StringUtils::transliterateString("éric cantona"));
        $this->assertEquals('l s c t z y a i e C A Z Y', StringUtils::transliterateString("ľ š č ť ž ý á í é Č Á Ž Ý"));
        $this->assertEquals('Java', StringUtils::transliterateString("Jávã"));
    }

    public function testParseBracketedKeyValuePairs()
    {
        $arr = StringUtils::parseBracketedKeyValuePairs("isbn = {23132123}\nadded-at = {2014-11-13}", "=", "\n", "{", "}");
        $this->assertTrue($arr['isbn'] == "23132123");
        $this->assertTrue($arr['added-at'] == "2014-11-13");

    }


}
