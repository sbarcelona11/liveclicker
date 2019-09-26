<?php

/**
 * Class UserLoginTest
 */
class UserLoginTest extends TestCase {
    /**
     * Test user login without any parameters.
     *
     * @return void
     */
    public function testUserLoginResponseWithAnyParameters()
    {
        $response = $this->get('/api/login');
        $this->assertEquals(422, $response->response->status());
        $this->seeJsonEquals([
            'email' => ["The email field is required."],
            'password' => ["The password field is required."]
        ]);
    }

    /**
     * Test user login without email parameter.
     */
    public function testUserLoginWithoutEmailParameter() {
        $response = $this->get( '/api/login?email=&password=asdasd');
        $this->assertEquals(422, $response->response->status());
        $this->seeJsonEquals([
            'email' => ["The email field is required."],
        ]);
    }

    /**
     * Test user login without password parameter.
     */
    public function testUserLoginWithoutPasswordParameter() {
        $response = $this->get( '/api/login?email=test@test.com&password');
        $this->assertEquals(422, $response->response->status());
        $this->seeJsonEquals([
            'password' => ["The password field is required."]
        ]);
    }

    /**
     * Test user login with wrong parameters.
     */
    public function testUserLoginWithWrongdParameters() {
        $response = $this->get( '/api/login?email=test@test&password=asdasdasdasd');
        $this->assertEquals(401, $response->response->status());
        $this->seeJsonEquals([
            'status' => "Fail, please, try again"
        ]);
    }

    /**
     * Test user login with valid parameters.
     */
    public function testUserLoginWithValidParameters() {
        $response = $this->get( '/api/login?email=test@test.com&password=password');
        $this->assertEquals(200, $response->response->status());
        $this->seeJsonStructure(['status', 'api_token']);
    }
}
