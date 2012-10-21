<?php

require_once dirname(__FILE__) . '/../../application/controllers/converter.php';


class ConverterTest extends PHPUnit_Framework_TestCase {

    public function testTest() {
        $this->assertEquals(
                true
                , Converter::test()
        );
    }

}