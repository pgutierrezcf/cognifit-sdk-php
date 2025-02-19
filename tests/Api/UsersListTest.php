<?php
namespace Api;

use CognifitSdk\Api\UsersList;
use CognifitSdk\Lib\UserData;
use PHPUnit\Framework\TestCase;

include_once dirname(__FILE__) . '/../.environment-test.php';

class UsersListTest extends TestCase
{

    public function testUserList(){

        $usersListInstance  = new UsersList(getenv('TEST_CLIENT_ID'), getenv('TEST_CLIENT_SECRET'), true);
        $response           = $usersListInstance->get();
        $this->assertEquals(false, $response->hasError());
        $this->assertIsArray($response->getData());
        $this->assertArrayHasKey('morePages', $response->getData());
        $this->assertArrayHasKey('userAccounts', $response->getData());
        $userAccounts   = $response->get('userAccounts');

        $this->assertIsArray($userAccounts);

        foreach ($userAccounts as $userAccount){
            $this->assertIsBool($userAccount->getLicenses()->isActiveTrainingLicense());
            if($userAccount->getLicenses()->isActiveTrainingLicense()){
                return $userAccount;
            }
        }

        return $userAccounts[0];
    }

    /**
     * @depends testUserList
     */
    public function testUserValues(UserData $userAccount){
        $this->assertInstanceOf('CognifitSdk\Lib\UserData', $userAccount);
        $this->assertNotEmpty($userAccount->getUserToken());
        $this->assertNotEmpty($userAccount->getValue('user_name'));
        $this->assertNotEmpty($userAccount->getValue('user_email'));
        $this->assertStringContainsString('@', $userAccount->getValue('user_email'));
        $this->assertRegExp('/^[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]$/', $userAccount->getValue('user_birthday'));
        $this->assertContains($userAccount->getValue('user_sex'), [-1, 0, 1]);
        $this->assertRegExp('/^[a-z][a-z](_[A-Z][A-Z])?$/', $userAccount->getValue('user_locale'));
        $this->assertInstanceOf('CognifitSdk\Lib\Licenses', $userAccount->getLicenses());
        $this->assertIsArray($userAccount->getLicenses()->getPendingAssessmentLicenses());
        $this->assertIsArray($userAccount->getLicenses()->getUsedAssessmentLicenses());
        $this->assertEquals(true, $userAccount->getLicenses()->isActiveTrainingLicense());
        $this->assertRegExp('/^[0-9][0-9][0-9][0-9]-[0-1][0-9]-[0-3][0-9]$/', $userAccount->getLicenses()->getTrainingExpirationDate());
    }

}
