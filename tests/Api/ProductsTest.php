<?php
namespace Api;

use PHPUnit\Framework\TestCase;

use CognifitSdk\Api\Product;

include_once dirname(__FILE__) . '/../.environment-test.php';

class ProductsTest extends TestCase {

    public function testGetAssessments()
    {
        $product     = new Product(getenv('TEST_CLIENT_ID'), true);
        $assessments = $product->getAssessments();
        $this->assertArrayHasKey('GENERAL_ASSESSMENT', $assessments);
        foreach ($assessments as $assessmentKey => $assessment){
            $this->assertInstanceOf('CognifitSdk\Lib\Products\Assessment', $assessment);
            $this->assertEquals($assessmentKey, $assessment->getKey());
            $this->assertIsArray($assessment->getSkills());
            $this->assertIsArray($assessment->getAssets());
            $this->assertIsInt($assessment->getEstimatedTime());
            $this->assertIsArray($assessment->getTasks());
            $this->_validateTitlesAndDescriptionOnlyEnglish($assessment);
            $this->assertArrayHasKey('images', $assessment->getAssets());
            $this->assertArrayHasKey('scareIconZodiac', $assessment->getAssets()['images']);
            $this->assertGreaterThan(0, $assessment->getEstimatedTime());
            $this->assertIsString($assessment->getTasks()[0]);
            $this->assertNotEquals('', $assessment->getTasks()[0]);
        }
    }

    public function testGetTraining()
    {
        $product   = new Product(getenv('TEST_CLIENT_ID'), true);
        $trainings = $product->getTraining();
        $this->assertArrayHasKey('NORMAL', $trainings);
        foreach ($trainings as $trainingKey => $training){
            $this->assertInstanceOf('CognifitSdk\Lib\Products\Training', $training);
            $this->assertEquals($trainingKey, $training->getKey());
            $this->assertIsString($training->getName());
            $this->assertNotEquals('', $training->getName());
            $this->assertIsArray($training->getTasks());
            $this->assertIsArray($training->getSkills());
            $this->assertIsArray($training->getAssets());
            $this->_validateTitlesAndDescriptionOnlyEnglish($training);
            $this->assertArrayHasKey('images', $training->getAssets());
            $this->assertArrayHasKey('scareIconZodiac', $training->getAssets()['images']);
        }
    }

    public function testGetGames()
    {
        $product    = new Product(getenv('TEST_CLIENT_ID'), true);
        $games      = $product->getGames();
        $this->assertArrayHasKey('MAHJONG', $games);
        foreach ($games as $gameKey => $game){
            $this->assertInstanceOf('CognifitSdk\Lib\Products\Game', $game);
            $this->assertEquals($gameKey, $game->getKey());
            $this->assertIsArray($game->getSkills());
            $this->assertIsArray($game->getAssets());
            $this->_validateTitlesAndDescriptionOnlyEnglish($game);
            $this->assertArrayHasKey('images', $game->getAssets());
            $this->assertArrayHasKey('icon', $game->getAssets()['images']);
        }
    }

    public function testGetAssessmentsWithLocales()
    {
        $product     = new Product(getenv('TEST_CLIENT_ID'), true);
        $assessments = $product->getAssessments($this->_getTestingLocales());
        $this->assertArrayHasKey('GENERAL_ASSESSMENT', $assessments);
        foreach ($assessments as $assessmentKey => $assessment){
            $this->assertInstanceOf('CognifitSdk\Lib\Products\Assessment', $assessment);
            $this->assertEquals($assessmentKey, $assessment->getKey());
            $this->assertIsArray($assessment->getSkills());
            $this->assertIsArray($assessment->getAssets());
            $this->assertIsInt($assessment->getEstimatedTime());
            $this->assertIsArray($assessment->getTasks());
            $this->_validateTitlesAndDescriptionTestingLocales($assessment);
            $this->assertArrayHasKey('images', $assessment->getAssets());
            $this->assertArrayHasKey('scareIconZodiac', $assessment->getAssets()['images']);
            $this->assertGreaterThan(0, $assessment->getEstimatedTime());
            $this->assertIsString($assessment->getTasks()[0]);
            $this->assertNotEquals('', $assessment->getTasks()[0]);
        }
    }

    public function testGetTrainingWithLocales()
    {
        $product   = new Product(getenv('TEST_CLIENT_ID'), true);
        $trainings = $product->getTraining($this->_getTestingLocales());
        $this->assertArrayHasKey('NORMAL', $trainings);
        foreach ($trainings as $trainingKey => $training){
            $this->assertInstanceOf('CognifitSdk\Lib\Products\Training', $training);
            $this->assertEquals($trainingKey, $training->getKey());
            $this->assertIsString($training->getName());
            $this->assertNotEquals('', $training->getName());
            $this->assertIsArray($training->getTasks());
            $this->assertIsArray($training->getSkills());
            $this->assertIsArray($training->getAssets());
            $this->_validateTitlesAndDescriptionTestingLocales($training);
            $this->assertArrayHasKey('images', $training->getAssets());
            $this->assertArrayHasKey('scareIconZodiac', $training->getAssets()['images']);
        }
    }

    public function testGetGamesWithLocales()
    {
        $product    = new Product(getenv('TEST_CLIENT_ID'), true);
        $games      = $product->getGames($this->_getTestingLocales());
        $this->assertArrayHasKey('MAHJONG', $games);
        foreach ($games as $gameKey => $game){
            $this->assertInstanceOf('CognifitSdk\Lib\Products\Game', $game);
            $this->assertEquals($gameKey, $game->getKey());
            $this->assertIsArray($game->getSkills());
            $this->assertIsArray($game->getAssets());
            $this->_validateTitlesAndDescriptionTestingLocales($game);
            $this->assertArrayHasKey('images', $game->getAssets());
            $this->assertArrayHasKey('icon', $game->getAssets()['images']);
        }
    }

    public function testGetAssessmentsError()
    {
        $product     = new Product('MAKE_CLIENT_ID', true);
        $assessments = $product->getAssessments();
        $this->assertEmpty($assessments);
    }

    public function testGetTrainingError()
    {
        $product   = new Product('MAKE_CLIENT_ID', true);
        $trainings = $product->getTraining();
        $this->assertEmpty($trainings);
    }

    public function testGetGamesError()
    {
        $product    = new Product('MAKE_CLIENT_ID', true);
        $games      = $product->getGames();
        $this->assertEmpty($games);
    }

    private function _validateTitlesAndDescriptionOnlyEnglish($element){
        $this->assertArrayHasKey('titles', $element->getAssets());
        $this->assertCount(1, $element->getAssets()['titles']);
        $this->assertArrayHasKey('en', $element->getAssets()['titles']);
        $this->assertArrayHasKey('descriptions', $element->getAssets());
        $this->assertCount(1, $element->getAssets()['descriptions']);
        $this->assertArrayHasKey('en', $element->getAssets()['descriptions']);

    }

    private function _validateTitlesAndDescriptionTestingLocales($element){
        $localesCount = count($this->_getTestingLocales());
        $this->assertArrayHasKey('titles', $element->getAssets());
        $this->assertCount($localesCount, $element->getAssets()['titles']);
        $this->assertArrayHasKey('descriptions', $element->getAssets());
        $this->assertCount($localesCount, $element->getAssets()['descriptions']);
        foreach ($this->_getTestingLocales() as $locale){
            $this->assertArrayHasKey($locale, $element->getAssets()['titles']);
            $this->assertArrayHasKey($locale, $element->getAssets()['descriptions']);
        }
    }

    private function _getTestingLocales(): array{
        return ['fr', 'es', 'pt_BR'];
    }

}
