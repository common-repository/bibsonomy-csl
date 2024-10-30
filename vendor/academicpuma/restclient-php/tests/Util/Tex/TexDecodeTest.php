<?php
/*
 *
 * restclient-php is a full-featured REST client  written in PHP
 * for PUMA and/or BibSonomy.
 * www.bibsonomy.org
 * www.academic-puma.de
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation, either version 3
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */


namespace AcademicPuma\RestClient\Util\Tex;


use AcademicPuma\RestClient\Config\ModelUtils;
use PHPUnit\Framework\TestCase;

class TexDecodeTest extends TestCase
{


    /**
     * @var TexDecode
     */
    private $texDecoder;


    public function setUp(): void
    {
        $this->texDecoder = new TexDecode();
    }

    public function testDecode()
    {
        $this->assertEquals("HÄÖÜh", $this->texDecoder->decode('H{\"A}{\"O}{\"U}h'));
        $this->assertEquals("Hähöüh", $this->texDecoder->decode('H{\"a}h{\"o}{\"u}h'));

        $expected = <<<'EOT'
Böttger, Sebastian; Atienza, Nieves; de Castro, Natalia; Cortés, Carmen; Garrido, M. Ángeles; Grima, Clara I.; Hernández, Gregorio; Márquez, Alberto; Moreno, Auxiliadora; Nöllenburg, Martin; Portillo, José Ramon; Reyes, Pedro; Valenzuela, Jesús; Trinidad Villar, Maria; Wolff, Alexander
EOT;
        $encoded = <<<'EOD'
B{\"o}ttger, Sebastian; Atienza, Nieves; de Castro, Natalia; Cort{\'e}s, Carmen; Garrido, M. {\'A}ngeles; Grima, Clara I.; Hern{\'a}ndez, Gregorio; M{\'a}rquez, Alberto; Moreno, Auxiliadora; N{\"o}llenburg, Martin; Portillo, Jos{\'e} Ramon; Reyes, Pedro; Valenzuela, Jes{\'u}s; Trinidad Villar, Maria; Wolff, Alexander
EOD;
        $this->assertEquals($expected, $this->texDecoder->decode($encoded));

        $expected = "Drawing Metro Maps using Bézier Curves";
        $encoded = "Drawing Metro Maps using B{\'e}zier Curves";
        $this->assertEquals($expected, $this->texDecoder->decode($encoded));


    }

    public function testDecode2()
    {
        $this->assertEquals('HÄllö', $this->texDecoder->decode('H\"{A}ll\"{o}'));
        $this->assertEquals("présent dans l'industrie, parc à thème, même à", $this->texDecoder->decode("pr\\'{e}sent dans l'industrie, parc \`{a} th\`{e}me, m\^{e}me \`{a}"));
        $this->assertEquals('H\"{A}ll\"{o}', $this->texDecoder->decode('H\"{A}ll\"{o}', ModelUtils::CB_KEEP, ModelUtils::BS_KEEP, false));
        $this->assertEquals("pr\\'{e}sent dans l'industrie, parc \`{a} th\`{e}me, m\^{e}me \`{a}", $this->texDecoder->decode("pr\\'{e}sent dans l'industrie, parc \`{a} th\`{e}me, m\^{e}me \`{a}", ModelUtils::CB_KEEP, ModelUtils::BS_KEEP, false));
    }

    public function testDecode3()
    {
        $expected = <<<'EOT'
Böttger, Sebastian; Atienza, Nieves; de Castro, Natalia; Cortés, Carmen; Garrido, M. Ángeles; Grima, Clara I.; Hernández, Gregorio; Márquez, Alberto; Moreno, Auxiliadora; Nöllenburg, Martin; Portillo, José Ramon; Reyes, Pedro; Valenzuela, Jesús; Trinidad Villar, Maria; Wolff, Alexander
EOT;
        $encoded = <<<'EOD'
B\"{o}ttger, Sebastian; Atienza, Nieves; de Castro, Natalia; Cort\'{e}s, Carmen; Garrido, M. {\'A}ngeles; Grima, Clara I.; Hern\'{a}ndez, Gregorio; M\'{a}rquez, Alberto; Moreno, Auxiliadora; N\"{o}llenburg, Martin; Portillo, Jos{\'e} Ramon; Reyes, Pedro; Valenzuela, Jes\'{u}s; Trinidad Villar, Maria; Wolff, Alexander
EOD;
        $this->assertEquals($expected, $this->texDecoder->decode($encoded));

        $expected = "Drawing Metro Maps using Bézier Curves";
        $encoded = "Drawing Metro Maps using {B{\'e}zier} Curves";
        $this->assertEquals($expected, $this->texDecoder->decode($encoded));
    }

    /***
     * Checks whether the keeping and removing of curly braces works correctly.
     */
    public function testDecode4()
    {
        $this->assertEquals('Lorem Ipsum $\mathbb{R}$ dolore. Author $\{1,\dotsc,n}$',
            $this->texDecoder->decode('}{Lorem Ipsum} $\mathbb{R}$ dolore. {Author} $\{1,\dotsc,n}${', ModelUtils::CB_KEEP_IN_MATH_MODE));
        $this->assertEquals('}{Lorem Ipsum} $\mathbb{R}$ dolore. {Author} $\{1,\dotsc,n}${',
            $this->texDecoder->decode('}{Lorem Ipsum} $\mathbb{R}$ dolore. {Author} $\{1,\dotsc,n}${', ModelUtils::CB_KEEP));
        $this->assertEquals('Lorem Ipsum $\mathbbR$ dolore. Author $\1,\dotsc,n$',
            $this->texDecoder->decode('}{Lorem Ipsum} $\mathbb{R}$ dolore. {Author} $\{1,\dotsc,n}${', ModelUtils::CB_REMOVE));
    }

    /***
     * Checks whether the option for BibTex cleaning works correctly.
     */
    public function testDecode5()
    {
        $this->assertEquals('Lörem Ípsum $\mathbb{R}$ dolore. Author $\{1,\dotsc,n}$',
            $this->texDecoder->decode('}{L{\"o}rem {\\\'I}psum} $\mathbb{R}$ dolore. {Author} $\{1,\dotsc,n}${', ModelUtils::CB_KEEP_IN_MATH_MODE, ModelUtils::BS_KEEP, true));
        $this->assertEquals('}{L{\"o}rem {\\\'I}psum} $\mathbb{R}$ dolore. {Author} $\{1,\dotsc,n}${',
            $this->texDecoder->decode('}{L{\"o}rem {\\\'I}psum} $\mathbb{R}$ dolore. {Author} $\{1,\dotsc,n}${', ModelUtils::CB_KEEP, ModelUtils::BS_KEEP, false));
    }

    /**
     * Another test for BibTex cleaning and mutated vowels as we
     * recognize \"a now as well instead of just \"{a}
     */
    public function testDecode6()
    {
        $this->assertEquals('HÄllö', $this->texDecoder->decode('H\"All\"o'));
        $this->assertEquals("présent dans l'industrie, parc à thème, même à", $this->texDecoder->decode("pr\'esent dans l'industrie, parc \`a th\`eme, m\^eme \`a"));
        $this->assertEquals('H\"{A}ll\"{o}', $this->texDecoder->decode('H\"{A}ll\"{o}', ModelUtils::CB_KEEP, ModelUtils::BS_KEEP, false));
        $this->assertEquals("pr\\'{e}sent dans l'industrie, parc \`{a} th\`{e}me, m\^{e}me \`{a}", $this->texDecoder->decode("pr\\'{e}sent dans l'industrie, parc \`{a} th\`{e}me, m\^{e}me \`{a}", ModelUtils::CB_KEEP, ModelUtils::BS_KEEP, false));
    }

    /**
     * Another test for BibTex cleaning for backslashes
     */
    public function testDecode7()
    {
        $decode_string = 'An \emph{O}(\emph{n}\({}^{\mbox{2.75}}\)) algorithm for \incremental';

        $this->assertEquals('An \emph{O}(\emph{n}\({}^{\mbox{2.75}}\)) algorithm for \incremental', $this->texDecoder->decode($decode_string, ModelUtils::CB_KEEP, ModelUtils::BS_KEEP, false));
        $this->assertEquals('An \emph{O}(\emph{n}\({}^{\mbox{2.75}}\)) algorithm for incremental', $this->texDecoder->decode($decode_string, ModelUtils::CB_KEEP, ModelUtils::BS_KEEP_IN_MATH_MODE, false));
        $this->assertEquals('An emph{O}(emph{n}({}^{mbox{2.75}})) algorithm for incremental', $this->texDecoder->decode($decode_string, ModelUtils::CB_KEEP, ModelUtils::BS_REMOVE, false));
    }

}
