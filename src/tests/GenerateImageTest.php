<?php

use Illuminate\Http\UploadedFile;

/**
 * Class GenerateImageTest
 */
class GenerateImageTest extends TestCase
{
    /**
     * Get api key.
     *
     * @return void
     */
    public function getApiKey(){
        $response = $this->get('/api/login?email=test@test.com&password=password');
        return $response->response->original['api_token'];
    }

    /**
     * Test generate image without send parameters and auth.
     */
    public function testGenerateImageWithoutSendParamsAndAutorization(){
        $response = $this->post('/api/generate-image');
        $this->assertEquals(401, $response->response->status());
    }

    /**
     * Test generate default image
     */
    public function testGenerateImageWithoutSendParams(){
        $response = $this->post('/api/generate-image', [],  ['Authorization' => "Bearer ". $this->getApiKey()]);
        $this->assertEquals(200, $response->response->status());
        $this->seeJsonStructure(['status', 'data']);
    }

    /**
     * Test generate image with image on params.
     */
    public function testGenerateImageWithSendImageParam(){
        $file = UploadedFile::fake()->image('test.jpg');
        $header = $this->transformHeadersToServerVars(['Authorization' => "Bearer ". $this->getApiKey()]);
        $response = $this->call('POST', '/api/generate-image', [], [], ['image' => $file], $header);

        $this->assertEquals(200, $response->status());
        $this->seeJsonStructure(['status', 'data']);
    }
}
