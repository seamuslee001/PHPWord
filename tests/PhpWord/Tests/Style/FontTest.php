<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Tests\Style;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Tests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Style\Font
 *
 * @runTestsInSeparateProcesses
 */
class FontTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tear down after each test
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test initiation for style type and paragraph style
     */
    public function testInitiation()
    {
        $object = new Font('text', array('align' => 'both'));

        $this->assertEquals('text', $object->getStyleType());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $object->getParagraphStyle());
    }

    /**
     * Test setting style values with null or empty value
     */
    public function testSetStyleValueWithNullOrEmpty()
    {
        $object = new Font();

        $attributes = array(
            'name' => PhpWord::DEFAULT_FONT_NAME,
            'size' => PhpWord::DEFAULT_FONT_SIZE,
            'bold' => false,
            'italic' => false,
            'underline' => Font::UNDERLINE_NONE,
            'superScript' => false,
            'subScript' => false,
            'strikethrough' => false,
            'doubleStrikethrough' => false,
            'smallCaps' => false,
            'allCaps' => false,
            'doubleStrikethrough' => false,
            'color' => PhpWord::DEFAULT_FONT_COLOR,
            'fgColor' => null,
            'bgColor' => null,
            'hint' => PhpWord::DEFAULT_FONT_CONTENT_TYPE,
        );
        foreach ($attributes as $key => $default) {
            $get = is_bool($default) ? "is{$key}" : "get{$key}";
            $object->setStyleValue("$key", null);
            $this->assertEquals($default, $object->$get());
            $object->setStyleValue("$key", '');
            $this->assertEquals($default, $object->$get());
        }
    }

    /**
     * Test setting style values with normal value
     */
    public function testSetStyleValueNormal()
    {
        $object = new Font();

        $attributes = array(
            'name' => 'Times New Roman',
            'size' => 9,
            'bold' => true,
            'italic' => true,
            'underline' => Font::UNDERLINE_HEAVY,
            'superScript' => true,
            'subScript' => false,
            'strikethrough' => true,
            'doubleStrikethrough' => false,
            'smallCaps' => true,
            'allCaps' => false,
            'color' => '999999',
            'fgColor' => Font::FGCOLOR_YELLOW,
            'bgColor' => 'FFFF00',
            'hint' => 'eastAsia',
            'lineHeight' => 2,
        );
        $object->setStyleByArray($attributes);
        foreach ($attributes as $key => $value) {
            $get = is_bool($value) ? "is{$key}" : "get{$key}";
            $this->assertEquals($value, $object->$get());
        }
    }

    /**
     * Test set line height
     */
    public function testLineHeight()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Test style array
        $text = $section->addText('This is a test', array(
            'line-height' => 2.0
        ));

        $doc = TestHelperDOCX::getDocument($phpWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:spacing');

        $lineHeight = $element->getAttribute('w:line');
        $lineRule = $element->getAttribute('w:lineRule');

        $this->assertEquals(480, $lineHeight);
        $this->assertEquals('auto', $lineRule);

        // Test setter
        $text->getFontStyle()->setLineHeight(3.0);
        $doc = TestHelperDOCX::getDocument($phpWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:spacing');

        $lineHeight = $element->getAttribute('w:line');
        $lineRule = $element->getAttribute('w:lineRule');

        $this->assertEquals(720, $lineHeight);
        $this->assertEquals('auto', $lineRule);
    }

    /**
     * Test line height floatval
     */
    public function testLineHeightFloatval()
    {
        $object = new Font(null, array('align' => 'center'));
        $object->setLineHeight('1.5pt');
        $this->assertEquals(1.5, $object->getLineHeight());
    }

    /**
     * Test line height exception by using nonnumeric value
     *
     * @expectedException \PhpOffice\PhpWord\Exception\InvalidStyleException
     */
    public function testLineHeightException()
    {
        $object = new Font();
        $object->setLineHeight('a');
    }
}
