<?php 
	/**
	 * Copyright 2016 Google Inc.
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License");
	 * you may not use this file except in compliance with the License.
	 * You may obtain a copy of the License at
	 *
	 *     http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software
	 * distributed under the License is distributed on an "AS IS" BASIS,
	 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
	 * See the License for the specific language governing permissions and
	 * limitations under the License.
	 */

	/**
	 * For instructions on how to run the full sample:
	 *
	 * @see https://github.com/GoogleCloudPlatform/php-docs-samples/tree/master/speech/README.md
	 */

	// Include Google Cloud dependendencies using Composer
	require_once __DIR__ . '/php-docs-samples/speech/vendor/autoload.php';
		
	$_ = 'php-docs-samples/speech/src/transcribe_sync.php';
	$audioFile = $_POST['inputFile'];

	# [START speech_transcribe_sync]
	use Google\Cloud\Speech\V1\SpeechClient;
	use Google\Cloud\Speech\V1\RecognitionAudio;
	use Google\Cloud\Speech\V1\RecognitionConfig;
	use Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding;
	
	// An issue with Guzzle client means I must set a flag to false
	/*
	use Google\Cloud\Firestore\FirestoreClient;
	use GuzzleHttp\Client;
	use Psr\Http\Message\RequestInterface;

	$guzzleClient = new Client(['verify' => false]);
	$firestore = new FirestoreClient([
		'authHttpHandler' => function (RequestInterface $request, array $options = []) use ($guzzleClient) {
			return $guzzleClient->send($request, $options);
		}
	]);*/

	// change these variables if necessary
	$encoding = AudioEncoding::LINEAR16;
	//$sampleRateHertz = 32000; //.raw
	//$sampleRateHertz = ;
	$languageCode = 'en-US';

	// get contents of a file into a string
	$content = file_get_contents($audioFile);

	// set string as audio content
	$audio = (new RecognitionAudio())
		->setContent($content);

	// set config
	$config = (new RecognitionConfig())
		->setEncoding($encoding)
	//    ->setSampleRateHertz($sampleRateHertz)
		->setLanguageCode($languageCode);

	// create the speech client
	$client = new SpeechClient();


	try {
		$response = $client->recognize($config, $audio);
		foreach ($response->getResults() as $result) {
			$alternatives = $result->getAlternatives();
			$mostLikely = $alternatives[0];
			$transcript = $mostLikely->getTranscript();
			$confidence = $mostLikely->getConfidence();
			echo '<b>Transcript</b>: '.$transcript.'<br>';
			echo '<b>Condifence</b>: '.$confidence.'<br>';
			//printf('Transcript: %s' . PHP_EOL, $transcript);
			//printf('Confidence: %s' . PHP_EOL, $confidence);
		}
	} finally {
		$client->close();
	}
	# [END speech_transcribe_sync]
	
?>